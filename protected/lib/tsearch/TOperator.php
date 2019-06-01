<?php

namespace TSearch;

use Yii;
use PDO;
use TLibXml;
use TUtil;
use TSearch\drivers\ICSTour;


abstract class TOperator{

    /******************************
     *         CONSTANTS           *
     ******************************/
    const TO_UNDEFINED_FORMAT = -201;


    /**
     * MAX NUMBER OF TOURS
     */
    const MAX_TOURS_NUMBER = 1000;


    /**
     * Max URL-requests number at once
     */
    const MAX_URL_REQUEST_NUMBER = 10;




    /******************************
     *         VARIABLES         *
     ******************************/


    /**
     * @var string
     */
    protected $authURL = '';


    /**
     * List with info of operators
     * @var null|array
     */
    private static $operatorsInfo = null;

    /**
     * List with hashes of operators
     * @var null|array
     */
    private static $operatorsHashes = null;

    /**
     * @var null
     */
    protected $operatorId = null;

    /**
     * @var string
     */
    protected $login = '';

    /**
     * @var string
     */
    protected $password = '';

    /**
     * @var array
     */
    protected $parametersSettings = array(
        'arrayBracket' => '[]'
    );

    protected $currency_rur = 'RUR';

    /**
     * @var array
     */
    protected $urls = array(
        'countries' => null, // УРЛ стран прилета
        'dep_cities' => null, // УРЛ городов вылета
        'tours' => null, // УРЛ списка туров
        'tour_info' => null, // УРЛ информации о туре
        'resorts' => null, // список всех курортов (страны)
        'hotels' => null, // список отелей
        'hotel_info' => null, // УРЛ информации об отеле
        'hotel_categories' => null, // список категорий отелей
        'meals' => null, // список типов питания
        'hotel_statuses' => null, // список статусов проживания в отелях
        'ticket_statuses' => null, // список статусов билетов
        'hotel_min_prices' => null, // список минимальных цен на отели страны
    );

    /**
     * @var array
     */
    protected $param_tours_keys = array(
        'country' => null, // страна прилета
        'depCity' => null, // город вылета
        'hotel' => null, // отели
        'meal' => null, // тип питания
        'category' => null, // категория отели
        'resort' => null, // курорт
        'availableDateFrom' => null, // Диапазон поиска туров: минимальная дата вылета: YYYY-MM-DD (2014-06-19)
        'availableDateTo' => null, // Диапазон поиска туров: максимальная дата вылета: YYYY-MM-DD (2014-06-19)
        'duration' => null, // продолжительность тура
        'durationFrom' => null, // Задает нижнюю границу продолжительности тура. Отменяет действие параметра duration
        'durationTo' => null, // Задает верхнюю границу продолжительности тура. Отменяет действие параметра duration
        'adults' => null, // количество взрослых
        'children' => null, // количество детей
        'chage' => null, // возрасть детей
        'maxPrice' => null, // Максимальная цена тура
        'currency' => null, // Валюта
        'pageSize' => null, // максимальное количество туров на одной странице
        'page' => null, // страница поиска
        'hotelNonstop' => null, // туры с отелями не в стопе
        'flightNonstop' => null, // туры с доступными билеты
    );

    /**
     * @var array
     */
    protected $param_tour_info_keys = array(
        'id' => null,    // Идентификатор тура /*REQUIRED*/
    );


    /**
     * @var array
     */
    protected $param_countries_keys = array(
        'depCity' => null
    );

    /**
     * @var array
     */
    protected $param_dep_cities_keys = array();

    /**
     * @var array
     */
    protected $param_resorts_keys = array(
        'country' => null,  // Страна прилета
    );

    /**
     * @var array
     */
    protected $param_hotels_keys = array(
        'country' => null,    // Страна прилета /*REQUIRED*/
        'depCity' => null,    // Город вылета
        'resort' => null,     // Курорт
        'category' => null,   // Категория отеля
    );

    /**
     * @var array
     */
    protected $param_hotel_info_keys = array(
        'id' => null,    // Идентификатор отеля /*REQUIRED*/
    );

    /**
     * @var array
     */
    protected $param_hotel_categories_keys = array();

    /**
     * @var array
     */
    protected $param_meals_keys = array();

    /**
     * @var array
     */
    protected $param_hotel_statuses_keys = array();

    /**
     * @var array
     */
    protected $param_ticket_statuses_keys = array();

    /**
     * @var array
     */
    protected $param_hotel_min_prices_keys = [
        'country' => null,  // Страна прилета /*REQUIRED*/
        'depCity' => null,  // Город вылета  /*REQUIRED*/
        'duration' => null, // Продолжительность тура  /*REQUIRED*/
        'date' => null,     // Дата вылета /*REQUIRED*/
        'adults' => null,   // Количество взрослых /*REQUIRED*/
        'children' => null, // Количество детей
        'chage' => null,    // Категория отеля
        'resort' => null,   // Курорт
        'warranty' => null, // Гарантия на туры
    ];



    /******************************
     *      ABSTRACT METHODS      *
     ******************************/

    /**
     * @return string
     */
    abstract protected function getClassName();

    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    abstract protected function normalize_tours($data, $params=[]);

    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    abstract protected function normalize_dep_cities($data, $params=[]);

    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    abstract protected function normalize_countries($data, $params=[]);

    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    abstract protected function normalize_resorts($data, $params=[]);

    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    abstract protected function normalize_hotel_categories($data, $params=[]);

    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    abstract protected function normalize_meals($data, $params=[]);

    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    abstract protected function normalize_hotel_statuses($data, $params=[]);

    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    abstract protected function normalize_ticket_statuses($data, $params=[]);

    /**
     * @param string $query
     * @param array $params
     * @return mixed
     */
    abstract public function buildUrls($query, $params=[]);

    /**
     * @param string $content
     * @param array $info
     * @return mixed
     */
    abstract public function validateRawContent($content, $info);

    /**
     * @param int $oid
     * @return TOperator
     */
    public static function newOperator($oid){
        $operators = self::newOperators($oid);
        return isset( $operators[$oid] ) ? $operators[$oid] : null;
    }

    /**
     * @param mixed $operators
     * @return array
     */
    public static function newOperators($operators=[]){
        $operators = self::operatorsInfo($operators);
        $ret = [];

        foreach( $operators as $operator ){
            $class = "TSearch\\drivers\\" . $operator->class;
            $ret[$operator->id] = new $class($operator->id);
        }

        return $ret;
    }

//    /**
//     * Returns operator's info
//     * @return \stdClass|null
//     */
//    public function operatorInfo(){
//        $operators = self::operatorsInfo($this->operatorId);
//        return isset($operators[$this->operatorId]) ? $operators[$this->operatorId] : null;
//    }

    /**
     * Returns list of operator's info
     * @param mixed $operators
     * @return array
     */
    public static function operatorsInfo($operators=null) {
        if( empty($operators) ){
            return self::operatorsMap();
        } else {
            $operators = (array)$operators;
            return array_intersect_key(self::operatorsMap(), array_flip($operators));
        }
    }

    /**
     * Collects operators map
     * @return array
     */
    private static function operatorsMap() {
        if (null === self::$operatorsInfo) {

            $dbCommand = Yii::app()->db->createCommand()
                ->select('id, name, class, url, position')
                ->from('{{operators}}')
                ->where('blocked = 0')
                ->order('position, name')
                ->setFetchMode(PDO::FETCH_OBJ);

            $dbOperators = $dbCommand->queryAll();
            self::$operatorsInfo = [];

            foreach ($dbOperators as $dbOperator) {
                self::$operatorsInfo[$dbOperator->id] = $dbOperator;
            }
        }

        return self::$operatorsInfo;
    }

    /**
     * Returns operator's hashes
     * @return stdClass
     */
    private function operatorHashes(){

        if( null === self::$operatorsHashes ){
            $dbCom = Yii::app()->db->createCommand()
                ->select('id, countries_hash, dep_cities_hash, resorts_hash, hotels_hash, hotel_categories_hash, meals_hash, hotel_statuses_hash, ticket_statuses_hash')
                ->from('{{operators}}')
                ->group('id')
                ->setFetchMode(PDO::FETCH_OBJ);
            self::$operatorsHashes = TUtil::listKey($dbCom->queryAll(), 'id');
        }

        return self::$operatorsHashes[$this->operatorId];
    }


    /**
     * __construct
     * @param $id int
     */
    protected function __construct($id){
        $this->operatorId = $id;
    }

    /**
     * Returns authenticate response
     * @param array $params
     * @return mixed|null
     */
    protected function getAuthenticateResponse($params=[]){
        $curl = curl_init();

        if( !$curl || !$this->authURL ) {
            return null;
        }

        $post_params = '';
        foreach ($params as $key => $value){
            $post_params .= ($post_params ? '&' : '') . "$key=$value";
        }


        curl_setopt($curl, CURLOPT_URL, $this->authURL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_params);
        $out = curl_exec($curl);
        curl_close($curl);

        return $out;
    }


    /**
     * Maximum URL request number
     * @return int
     */
    public function maxUrlRequestNumber(){
        return self::MAX_URL_REQUEST_NUMBER;
    }

    /**
     * Return operator's ID
     * @return int
     */
    public function operatorId(){
        return $this->operatorId;
    }

    /**
     * @param string $query
     * @return array
     */
    private function getParamKeys($query){
        $paramKeysNames = 'param_' . $query . '_keys';
        return isset($this->$paramKeysNames) ? $this->$paramKeysNames : [];
    }

    /**
     * Binds tour's params
     * @param array $params
     * @param array $paramKeys
     * @return array
     */
    private function bindParams($params, $paramKeys){
        $retParams = array();

        foreach( $params as $key => $param ){
            if( isset( $paramKeys[$key] ) ){

                $validKey = $paramKeys[$key];
                $retParams[$validKey] = $param;
            }
        }

        return $retParams;
    }


    /**
     * Returns a parsed string of tour parameters
     * @param array $params
     * @return string
     */
    private function getParametersString($params){
        $strTourParams = '';
        foreach( $params as $key => $param ){

            if( is_array($param) ){
                $bracket = $this->parametersSettings['arrayBracket'];
                $arParamName = $key . $bracket;

                foreach( $param as $arParam ){
                    $strTourParams .= ($strTourParams ? '&' : '') . $arParamName . '=' . $arParam;
                }

            } else {
                $strTourParams .= ($strTourParams ? '&' : '') . $key . '=' . $param;
            }
        }

        return $strTourParams;
    }

    /**
     * @param string $query
     * @param array $params
     * @return string
     */
    protected function buildUrl($query, $params=[]){
        // set url
        $url = $this->urls[$query];

        // bind params
        $paramKeys = $this->getParamKeys($query);
        $params = $this->bindParams($params, $paramKeys);

        $strParameters = $this->getParametersString($params);
        $url .= '?' . $strParameters;

        return $url;
    }

    /**
     * Returns normalized data of raw-content
     * @param string $query
     * @param string $content
     * @param array $info
     * @return array|bool
     */
    public function getNormalizedData($query, $content, $info) {
        $rawData = $this->validateRawContent($content, $info);
        $data = null;

        if ( $rawData ) {
            $params = $this->extractParamsFromUrl($info['url'], $query);
            $normalizeMethod = 'normalize_' . $query;
            $data = $this->$normalizeMethod($rawData, $params);
        }

        return $data;
    }

    /**
     * Extract params from url
     * @param string $url
     * @param string $query
     * @return array
     */
    private function extractParamsFromUrl($url, $query){
        $ret = [];
        $url = explode('?', $url);
        $keys = $this->getParamKeys($query);

        if( !empty($url[1]) ){
            $params = explode('&', $url[1]);

            foreach($params as $param) {
                $p = explode('=', $param);
                $k = array_search(str_replace($this->parametersSettings['arrayBracket'], '', $p[0]), $keys);
                $v = $p[1];

                if( isset($ret[$k]) ){

                    if( !is_array($ret[$k]) ){
                        $ret[$k] = [$ret[$k]];
                    }

                    $ret[$k][] = $v;

                } else {
                    $ret[$k] = $v;
                }
            }
        }

        return $ret;
    }


    /**
     * Gets hash of data
     * @param string $content
     * @return string
     */
    private function getDataHash($content){
        return (string)sha1(implode('-', (array)$content));
    }

    /**
     * Checks if hash of $raw mismatch with hash from db and returns it
     * @param string $raw
     * @param string $query
     * @return string|null
     */
    public function mismatchedHash($raw, $query){
        $hashes = $this->operatorHashes();
        $hash_field = $query . '_hash';

        if( property_exists($hashes, $hash_field) ){
            $hash = $this->getDataHash($raw);

            if( $hash != $hashes->$hash_field ) {
                return $hash;
            }
        }

        return null;
    }


    //////////////////////////////////////////////////////////
    //                  Loading Method                      //
    //////////////////////////////////////////////////////////


    /**
     * Loads data query
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public function load($query, $params=[]){
        $data = null;
        $urls = $this->buildUrls($query, $params);

        if( !empty($urls) ) {
            $cURL = new MultiCURL();
            $o = $this;
            $data = [];

            foreach( $urls as $url ) {
                $cURL->add($url, function($content, $info) use($o, &$data, $query){

                    // если хотя бы один запрос вернет неправильные данные - значит все запросы считать невалидными
                    if( $data !== null ) {
                        $_data = $o->getNormalizedData($query, $content, $info);
                        $_data === null ? $data = null : TUtil::fastArrayMerge($data, $_data);
                    }

                });
            }

            $cURL->request();
        }

        return $data;
    }

}