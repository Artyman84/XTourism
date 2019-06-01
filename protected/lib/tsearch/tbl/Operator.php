<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 15.01.2016
 * Time: 18:18
 */

namespace TSearch\tbl;
use TSearch\tbl\operators;

use TSearch\TOperator;
use TSearch\BindData;
use TSearch\MultiCURL;
use TUtil;
use Yii;
use PDO;


abstract class Operator {

    /**
     * Max operators count for processing at a time
     */
    const MAX_OPERATOR_PROCESSING = 3;

    /**
     * @var array
     */
    private static $tables_map = [];

    /**
     * @var string
     */
    private $table;

    /**
     * Constructor
     * @param string $table
     */
    protected function __construct($table){
        $this->table = $table;
    }

    /**
     * Creates and returns instance of operator's table
     * @param string $table
     * @return Operator
     */
    public static function table($table){
        if( !isset( self::$tables_map[$table] ) ){
            $class = "TSearch\\tbl\\operators\\" . str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
            self::$tables_map[$table] = new $class();
        }


        return self::$tables_map[$table];
    }

    /**
     * Loads operator's data from corresponding table
     * @param mixed $operators
     * @param array $params
     * @param bool $assoc
     * @return mixed
     */
    public function loadData($operators, $params=[], $assoc=false){
        $operators = array_keys(TOperator::operatorsInfo($operators));
        $elements = (array)$this->buildQuery($operators, $params)->queryAll();

        if( $assoc ){
            $_elements = [];
            foreach( $elements as $element ){
                $_elements[$element->operator_id][$element->element_id] = $element;
            }

            $elements = $_elements;
        }

        return $elements;
    }

    /**
     * Updates data table of operators
     * @param mixed $operators
     */
    public function updateData($operators){
        $operators = array_keys(TOperator::operatorsInfo($operators));

        // делим по 3 оператора на обработку за один раз
        $_operators = array_chunk($operators, self::MAX_OPERATOR_PROCESSING);

        foreach( $_operators as $o_ids ){

            // вытаскиваем свежие данные ТО и проверяем
            // если хоть что-то успешно загрузилось для дальнейшего обновления
            $oData = $this->operatorsData($o_ids);

            if( empty($oData) ) continue;

            $dbData = $this->loadData(array_keys($oData), [], true);
            foreach ($oData as $oid => $data) {

                $newElements = [];
                $elementIds = [];
//                $mismatched = [];

                foreach ($data['data'] as $element) {

                    $elementIds[] = $element_id = $element->element_id;

                    // Если элемент существует в базе делаем проверки ниже
                    if (isset($dbData[$oid]) && isset($dbData[$oid][$element_id])) {
                        $dbElement = $dbData[$oid][$element_id];

                        // Если элемент уже в базе есть, тогда проверяем его имя и флаг "f_deleted"
                        // И если хотя бы что-то не соответствует тому, что пришло от туроператора, - обновляем в базе этот элемент
                        if ($dbElement->name != $element->name || ( isset($dbElement->f_deleted) && $dbElement->f_deleted )) {

                            $update = [];
                            if( $dbElement->name != $element->name ) {
                                $update['name'] = $element->name;
                                $update['name_was_changed'] = 1;
                            }

                            if( isset($dbElement->f_deleted) && $dbElement->f_deleted ) {
                                $update['f_deleted'] = 0;
                            }

                            $this->updateElement($update, $oid, $element_id);
                            //$mismatched[] = $element_id;
                        }

                    } else {

                        // Собираем список несуществующих элементов
                        $newElements[] = $this->populateFields($element, $oid);
                    }
                }

//                if (!empty($mismatched)) {
//                    // Разъединить те элементы, у которых одинаковые ID, но разные имена
//                    BindData::inst($oid)->unbind($this->table, $mismatched);
//                    // Скрестить "по сравнению" те элементы, у которых одинаковые ID, но разные имена
//                    BindData::inst($oid)->bindComparing($this->table, null, $mismatched);
//                }

                // Удаление несуществующих элементов
                $this->deleteAllNotInElements($oid, $elementIds);

                // Вставляем новые элементы и скрещиваем заново все
                if (!empty($newElements)) {
                    TUtil::multipleInsertData('operator_' . $this->table, $newElements);
                    BindData::inst($oid)->bindComparing($this->table, null, $elementIds);
                }

                $this->setOperatorHash($data['hash'], $oid);
            }

        }
    }

    /**
     * @param null|array $operators
     * @return array
     */
    private function operatorsData($operators=null){

        $operators = TOperator::newOperators($operators);
        $query = $this->table;
        $urls = [];

        foreach($operators as $oid => $operator ) {

            $_urls = $operator->buildUrls($query);
            if( !empty($_urls) ) {
                $urls[$oid] = $_urls;
            }
        }

        $raw = [];
        while( !empty($urls) ){

            $process_urls = [];
            foreach($urls as $oid => $_urls){

                $process_urls[$oid] = array_splice($_urls, 0, $operators[$oid]::MAX_URL_REQUEST_NUMBER);

                if( empty($_urls) ){
                    unset($urls[$oid]);
                } else {
                    $urls[$oid] = $_urls;
                }
            }

            $process_data = $this->loadMismatches($operators, $process_urls);

            foreach($process_data as $oid => $data){

                if( !isset($raw[$oid]) ){
                    $raw[$oid] = ['info' => [], 'contents' => []];
                }

                $raw[$oid]['info'] += $data['info'];
                $raw[$oid]['contents'] += $data['contents'];
            }
        }

        $ret = [];
        foreach ($raw as $oid => $data) {

            if (($new_hash = $operators[$oid]->mismatchedHash($data['contents'], $query))) {

                $ret[$oid] = ['data' => [], 'hash' => $new_hash];
                foreach ($data['contents'] as $url => $content) {

                    $temp_data = $operators[$oid]->getNormalizedData($query, $content, $data['info'][$url]);

                    // Если хотя бы один запрос вернет неправильные данные - значит все запросы считать невалидными,
                    // А сам туроператор исключить из списка.
                    if( null === $temp_data ){
                        unset($ret[$oid]);
                        break;
                    }

                    TUtil::fastArrayMerge($ret[$oid]['data'], $temp_data);
                }
            }
        }

        return $ret;
    }


    /**
     * Multi loads data which mismatched with data from db
     * @param array $operators
     * @param array $urls
     * @return array
     */
    private function loadMismatches($operators, $urls){

        $raw = [];
        $request_count = 1;

        while (true) {

            $cURL = new MultiCURL();

            foreach ($urls as $oid => $_urls) {
                foreach ($_urls as $url) {

                    $cURL->add($url, function ($content, $info) use (&$raw, $oid, $url, $operators) {
                        $raw[$oid]['info'][$url] = $info;
                        $raw[$oid]['contents'][$url] = null;

                        if( $operators[$oid]->validateRawContent($content, $info) ){
                            $raw[$oid]['contents'][$url] = $content;
                        }
                    });

                }
            }

            $cURL->request();

            $urls = [];
            foreach ($raw as $oid => $data) {
                foreach ($data['contents'] as $_url => $content) {
                    if( null === $content ){
                        $urls[$oid][] = $_url;
                    }
                }
            }

            $request_count++;

            if(!empty($urls) && $request_count <= 3){
                usleep(500);
            } else {
                break;
            }
        }

        return $raw;
    }


    /**
     * Updates element
     * @param array $data
     * @param int $oid
     * @param int $element
     */
    private function updateElement($data, $oid, $element){
        Yii::app()->db->createCommand()->update(
            '{{operator_' . $this->table . '}}',
            $data,
            'operator_id = :oid AND element_id = :eid',
            array(':oid' => $oid, ':eid' => $element)
        );
    }

    /**
     * Deletes elements
     * @param int $oid
     * @param array $elements
     */
    protected function deleteAllNotInElements($oid, $elements){
        Yii::app()->db->createCommand()->delete(
            '{{operator_' . $this->table . '}}',
            ['AND', 'operator_id = :oid', ['NOT IN', 'element_id', $elements]],
            [':oid' => $oid]
        );
    }

    /**
     * Sets operator's hash
     * @param string $hash
     * @param string $oid
     */
    private function setOperatorHash($hash, $oid){
        $hash_field = $this->table . '_hash';
        Yii::app()->db->createCommand()->update('{{operators}}', [$hash_field => $hash], 'id = :oid', [':oid' => $oid]);
    }

    /**
     * Populates table's fields
     * @param stdClass $element
     * @param integer $oid
     * @return array
     */
    protected function populateFields($element, $oid){
        return [
            'operator_id' => $oid,
            'element_id' => $element->element_id,
            'directory_id' => 0,
            'unread' => 1,
            'name' => $element->name,
        ];
    }


    /**
     * @param null|array $params
     * @param array $operators
     * @return CDbCommand
     */
    private function buildQuery($operators, $params=null){

        $operator = Yii::app()->db->createCommand();
        $operator->select('operator.*');
        $operator->from('{{operator_' . $this->table . '}} AS operator');
        $operator->order('operator.operator_id, operator.name');
        $operator->setFetchMode(PDO::FETCH_OBJ);

        $condition = ['AND', ['IN', 'operator.operator_id', $operators]];
        $placeholders = [];

        if( isset($params['related']) ){
            $condition[] = 'operator.directory_id ' . ($params['related'] ? '!=0' : '=0');
        }
        unset($params['related']);

        if( is_array($params) && !empty($params) ){
            foreach( $params as $key => $value ){

                if( $key == 'name' ){
                    $condition[] = ['LIKE', 'operator.name', '%' . $value . '%'];

                } elseif( is_array($value) ){
                    $condition[] = ['IN', 'operator.' . $key, $value];

                } else {
                    $condition[] = 'operator.' . $key . '=:' . $key;
                    $placeholders[':' . $key] = $value;
                }
            }
        }

        $operator->where($condition, $placeholders);
        return $operator;
    }
}