<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 28.08.2017
 * Time: 21:52
 */

namespace TSearch;

use Yii;
use PDO;
use TUtil;
use CHtml;
use CJSON;

/**
 * Class ShowcaseTour
 * @package TSearch
 */
class ShowcaseTour {

    const MAX_OPERATOR_PROCESSING = 5;

    /**
     * List of TOperator's objects
     * @var array
     */
    private $operators;

    /**
     * @var int
     */
    private $midnight;

    /**
     * Showcase tours table
     * @var string
     */
    private static $table = null;

    /**
     * @var array
     */
    private static $update_params = [
        // Количество взрослых
        'adults' => 2,

        // Ночей
        'duration' => 7,

        // Дата вылета
        'date' => 0,
    ];


    /**
     * @param mixed $operators
     */
    public function __construct($operators=null){
        $this->operators = TOperator::newOperators(empty($operators) ? null : (array)$operators);
        $this->midnight = strtotime('midnight');
    }

    /**
     * Returns settings
     * @return mixed
     */
    private static function getSettings(){
        return CJSON::decode(file_get_contents(__DIR__ . '/showcase_settings.json'));
    }

    /**
     * Sets setting's value
     * @param string $key
     * @param string $value
     * @return mixed
     */
    private static function setSetting($key, $value){
        $settings = self::getSettings();
        if( $value === null ){
            unset($settings[$key]);
        } else {
            $settings[$key] = $value;
        }
        return file_put_contents(__DIR__ . '/showcase_settings.json', CJSON::encode($settings));
    }

    /**
     * Return active showcase table
     * @return string
     */
    public static function table(){
        if( self::$table === null ) {
            $settings = self::getSettings();
            self::$table = $settings['tour_showcase_table'];
        }

        return self::$table;
    }

    /**
     * Returns operators ids
     * @return array
     */
    private function operatorsIds(){
        return array_keys($this->operators);
    }

    /**
     * @param array $params
     * @return array
     */
    private function collectUpdateUrls($params){
        self::$update_params['date'] = strtotime('+3 Days');
        $params = $params + self::$update_params;

        $dep_cities_raw = Yii::app()->db->createCommand()
            ->select('element_id, operator_id')
            ->from('{{operator_dep_cities}}')
            ->where(['AND', 'directory_id != 0', ['IN', 'operator_id', $this->operatorsIds()]])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $dep_cities = [];
        foreach($dep_cities_raw as $dc) {
            $dep_cities[$dc->operator_id][] = $dc->element_id;
        }

        $urls = [];
        foreach($this->operators as $oid => $operator ) {
            if( !empty($dep_cities[$oid]) ) {
                $params['depCity'] = $dep_cities[$oid];

                $_urls = $operator->buildUrls('hotel_min_prices', $params);
                if (!empty($_urls)) {
                    $urls[$oid] = $_urls;
                }
            }
        }

        return array_chunk($urls, self::MAX_OPERATOR_PROCESSING, true);
    }

    /**
     * Processing tours
     * @param string $table
     * @param array $params
     */
    private function processTours($table, $params=[]) {
        $all_urls = $this->collectUpdateUrls($params);

        foreach( $all_urls as $urls ) {

            $data = [];
            while (!empty($urls)) {

                $process_urls = [];
                foreach ($urls as $oid => $_urls) {

                    $process_urls[$oid] = array_splice($_urls, 0, $this->operators[$oid]->maxUrlRequestNumber());

                    if (empty($_urls)) {
                        unset($urls[$oid]);
                    } else {
                        $urls[$oid] = $_urls;
                    }
                }


                $this->loadTours($process_urls, $data);

                // Если еще есть урл, тогда ждем секунду, что бы не загружать серверы ТО
                if( !empty($urls) ) {
                    usleep(1000);
                }
            }

            // После того, как обработали допустимое число ТО(MAX_OPERATOR_PROCESSING) за один цикл - записываем даные в таблицу
            // И обнуляем выше массив $data для следующего цикла с допустимым числом ТО(MAX_OPERATOR_PROCESSING)
            TUtil::multipleInsertData($table, $data, 300);
        }
    }


    /**
     * Updates showcases tours
     * @param array $params
     */
    public function updateTours($params=[]) {
        $old_table = ShowcaseTour::table();
        $new_table = 'tour_showcase_tours_1';

        if( $old_table == 'tour_showcase_tours_1' ) {
            $new_table = 'tour_showcase_tours_2';
        }

        Yii::app()->db->createCommand()->truncateTable('{{' . $new_table . '}}');
        $this->processTours($new_table, $params);

        if( self::setSetting('tour_showcase_table', $new_table) ){
            Yii::app()->db->createCommand()->truncateTable('{{' . $old_table . '}}');
            self::$table = null;
        }
    }


    /**
     * Multi loads tours data
     * @param array $urls
     * @param array $data
     * @return array
     */
    private function loadTours($urls, &$data){

        $o_ids = array_keys($urls);
        $meals = tbl\Operator::table('meals')->loadData($o_ids, ['related' => true], true);
        $dep_cities = tbl\Operator::table('dep_cities')->loadData($o_ids, ['related' => true], true);
        $hotels = tbl\Operator::table('hotels')->loadData($o_ids, ['related' => true], true);

        $request_count = 1;
        while (true) {

            $unprocess_urls = [];
            $cURL = new MultiCURL();

            foreach ($urls as $oid => $_urls) {
                foreach ($_urls as $url) {

                    $operator = $this->operators[$oid];
                    $cURL->add($url, function ($content, $info) use (&$unprocess_urls, $url, $operator, &$data, &$hotels, &$meals, &$dep_cities) {
                        $oid = $operator->operatorId();
                        $temp_hotels = $operator->getNormalizedData('hotel_min_prices', $content, $info);

                        if( $temp_hotels === null ) {
                            $unprocess_urls[$oid][] = $url;
                        } else {
                            foreach ($temp_hotels as $temp_hotel){

                                if( isset($hotels[$oid]) && isset($hotels[$oid][$temp_hotel->hotel_id]) &&
                                        isset($meals[$oid]) && isset($meals[$oid][$temp_hotel->meal_id]) &&
                                            isset($dep_cities[$oid]) && isset($dep_cities[$oid][$temp_hotel->depCity]) ){

                                    $dc_dir_id = $dep_cities[$oid][$temp_hotel->depCity]->directory_id;
                                    $h_dir_id = $hotels[$oid][$temp_hotel->hotel_id]->directory_id;
                                    $key = $oid . '_' . $dc_dir_id . '_' . $h_dir_id;

                                    if( !isset($data[$key]) || $data[$key]['price_rur'] > $temp_hotel->price_rur ){

                                        $data[$key] = [
                                            'o_id' => $oid,
                                            'h_dir_id' => $h_dir_id,
                                            't_id' => $temp_hotel->tour_id,
                                            'm_dir_id' => $meals[$oid][$temp_hotel->meal_id]->directory_id,
                                            'dc_dir_id' => $dep_cities[$oid][$temp_hotel->depCity]->directory_id,
                                            'start_date' => $temp_hotel->start_date,
                                            'end_date' => $temp_hotel->end_date,
                                            'nights' => $temp_hotel->nights,
                                            'adults' => $temp_hotel->adults,
                                            'kids' => $temp_hotel->kids,
                                            'room' => $temp_hotel->room,
                                            'price_rur' => $temp_hotel->price_rur,
                                            'price' => $temp_hotel->price,
                                            'currency' => $temp_hotel->currency,
                                        ];

                                    }
                                }
                            }

                        }
                    });

                }
            }

            $cURL->request();

            if( empty($unprocess_urls) || $request_count >= 3){
                break;
            } else {
                $urls = $unprocess_urls;
                $request_count++;
                usleep(500);
            }

        }
    }


    /**
     * Returns list of countries which depends of departure cities
     * @param bool $json
     * @param bool $countries
     * @return mixed
     */
    public function countriesOfDepCities($countries=null, $json=true){

        $db = Yii::app()->db;

        $condition = ['AND', 't.start_date > ' . $this->midnight, 'c.disabled = 0', 'h.disabled = 0'];

        if( !empty($countries) ){
            $condition[] = ['IN', 'c.id', $countries];
        }

        $countries = $db->createCommand()
            ->select('c.id, c.name, CONCAT(",", GROUP_CONCAT(DISTINCT t.dc_dir_id ORDER BY t.dc_dir_id SEPARATOR ","), ",") AS cities')
            ->from('{{' . self::table() . '}} AS t')
            ->join('{{directory_hotels}} AS h', 'h.id = t.h_dir_id')
            ->join('{{directory_countries}} AS c', 'c.id = h.dir_country_id')
            ->where($condition)
            ->group('c.id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        return $json ? CJSON::encode($countries) : $countries;
    }


    /**
     * Returns list of departure cities
     * @param bool $list
     * @return array
     */
    public function depCitiesList($list=true){
        $db = Yii::app()->db;

        $condition = ['AND',
            't.start_date > ' . $this->midnight,
            'c.disabled = 0',
            ['IN', 'o.operator_id', $this->operatorsIds()]
        ];

        $dep_cities = $db->createCommand()
            ->select('c.id, c.name')
            ->from('{{' . self::table() . '}} AS t')
            ->join('{{directory_dep_cities}} AS c', 'c.id = t.dc_dir_id')
            ->join('{{operator_dep_cities}} AS o', 'o.directory_id = t.dc_dir_id')
            ->where($condition)
            ->order('c.name')
            ->group('c.id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        return $list ? CHtml::listData( $dep_cities, 'id', 'name' ) : $dep_cities;
    }

    /**
     * Returns list of countries
     * @param int $dep_city_id
     * @param array $countries
     * @param bool $list
     * @return mixed
     */
    public function countriesList($dep_city_id, $countries=null, $list=true){

        $db = Yii::app()->db;

        $condition = ['AND',
            't.dc_dir_id = :dcid',
            't.start_date > ' . $this->midnight,
            'c.disabled = 0',
            'h.disabled = 0',
            ['IN', 'o.operator_id', $this->operatorsIds()]
        ];

        $params = [':dcid' => $dep_city_id];

        if( !empty($countries) ){
            $condition[] = ['IN', 'c.id', $countries];
        }

        $countries = $db->createCommand()
            ->select('c.id, c.name')
            ->from('{{' . self::table() . '}} AS t')
            ->join('{{directory_hotels}} AS h', 'h.id = t.h_dir_id')
            ->join('{{directory_countries}} AS c', 'c.id = h.dir_country_id')
            ->join('{{operator_countries}} AS o', 'o.directory_id = c.id')
            ->where($condition, $params)
            ->order('c.name')
            ->group('c.id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        return $list ? CHtml::listData( $countries, 'id', 'name' ) : $countries;
    }

    /**
     * Returns list of resorts
     * @param int $dep_city_id
     * @param int $country
     * @param bool $list
     * @return mixed
     */
    public function resortsList($dep_city_id, $country, $list=true){

        // Данный метод вытаскивает все курорты, кроме дочерних
        $db = Yii::app()->db;

        $condition = ['AND',
            't.dc_dir_id = :dcid',
            'h.dir_country_id = :country',
            't.start_date > ' . $this->midnight,
            'r.disabled = 0',
            'h.disabled = 0',
            ['IN', 'o.operator_id', $this->operatorsIds()]
        ];

        $params = [':dcid' => $dep_city_id, ':country' => $country];

        $resorts_ids = $db->createCommand()
            ->select('IF(r.parent_id = 0, r.id, r.parent_id) AS resort_id')
            ->from('{{' . self::table() . '}} AS t')
            ->join('{{directory_hotels}} AS h', 'h.id = t.h_dir_id')
            ->join('{{directory_resorts}} AS r', 'r.id = h.dir_resort_id')
            ->join('{{operator_resorts}} AS o', 'o.directory_id = r.id')
            ->where($condition, $params)
            ->group('resort_id')
            ->queryColumn();

        $resorts = $db->createCommand()
            ->select('id, name')
            ->from('{{directory_resorts}}')
            ->where(['IN', 'id', $resorts_ids])
            ->order('name')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        return $list ? CHtml::listData($resorts, 'id', 'name') : $resorts;
    }

    /**
     * Returns list of hotels categories
     * @param int $dep_city_id
     * @param bool $list
     * @return mixed
     */
    public function categoriesList($dep_city_id, $list=true){

        $db = Yii::app()->db;

        $condition = ['AND',
            't.dc_dir_id = :dcid',
            't.start_date > ' . $this->midnight,
            'c.disabled = 0',
            'h.disabled = 0',
            ['IN', 'o.operator_id', $this->operatorsIds()]
        ];
        $params = [':dcid' => $dep_city_id];

        $categories = $db->createCommand()
            ->select('c.id, c.name')
            ->from('{{' . self::table() . '}} AS t')
            ->join('{{directory_hotels}} AS h', 'h.id = t.h_dir_id')
            ->join('{{directory_hotel_categories}} AS c', 'c.id = h.dir_category_id')
            ->join('{{operator_hotel_categories}} AS o', 'o.directory_id = c.id')
            ->where($condition, $params)
            ->order('c.position')
            ->group('c.id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        return $list ? CHtml::listData( $categories, 'id', 'name' ) : $categories;
    }

}