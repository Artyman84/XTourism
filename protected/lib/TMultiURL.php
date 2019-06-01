<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 23.07.14
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */


/**
 * TMultiURL class
 */
class TMultiURL{
    /**
     * TMU_EMPTY_URL_LIST
     */
    const TMU_EMPTY_URL_LIST = 'Список адресов пуст.';

    /**
     * list of urls
     * @var array
     */
    private $urls = array();

    /**
     * Max count of URLs which can be transmitted at once
     * @var int
     */
    private $maxURLCount = 30;

    /**
     * list of tasks
     * @var array
     */
    private $tasks = array();

    /**
     * max connection timeout
     * @var int
     */
    private $connectTimeout = 30;

    /**
     * max timeout
     * @var int
     */
    private $timeout = 35;

    /**
     * "ret header" setting
     * @var int
     */
    private $retHeader = false;

    /**
     * @var resource
     */
    private $cmh;

    /**
     * Loads urls content
     * @param $urls
     * @param array $settings
     * @return array
     */
    public static function load($urls, $settings=array()){

        $urls = (array)$urls;
        $normalizedUrls = array();

        foreach( $urls as $key => $tUrl ){

            if( is_array($tUrl) ){
                foreach( $tUrl as $i => $url  ){
                    $normalizedUrls[$key . '_url_' . $i] = $url;
                }
            } else {
                $normalizedUrls[$key] = $tUrl;
            }
        }

        $mu = new self( $normalizedUrls, $settings );

        $max_execution_time = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);

        $_result = $mu->exec();

        ini_set('max_execution_time', $max_execution_time);


        $result = array();
        foreach( $_result as $k => $r ){

            if( strpos( $k, '_url_' ) !== false ){

                $keys = explode('_url_', $k);
                $result[$keys[0]][$keys[1]] = $r;

            } else {
                $result[$k] = $r;
            }

        }

        return $result;
    }

    /**
     * __construct
     * @param array $settings
     * @param array $urls
     */
    private function __construct($urls, $settings=array()){
        if( isset( $settings['connectTimeout'] ) ){
            $this->connectTimeout = $settings['connectTimeout'];
        }

        if( isset( $settings['timeout'] ) ){
            $this->timeout = $settings['timeout'];
        }

        if( isset( $settings['retHeader'] ) ){
            $this->retHeader = $settings['retHeader'];
        }

        if( isset( $settings['maxURLCount'] ) ){
            $this->maxURLCount = $settings['maxURLCount'];
        }

        // инициализируем "контейнер" для отдельных соединений (мультикурл)
        $this->cmh = curl_multi_init();

        // инициализируем список адресов
        $this->urls = $urls;
    }

    /**
     * __destruct
     */
    public function __destruct(){
        // закрываем мультикурл
        curl_multi_close($this->cmh);
        //echo '<br/><b>Kill cmh</b><br/>';
    }


    /**
     * Executes CURL
     * @return array
     * @throws CException
     */
    private function exec(){
        if( !count( $this->urls ) ){
            throw new CException(TMultiURL::TMU_EMPTY_URL_LIST);
        }

        $this->populateUrlHandlesContent();
        return $this->result();
    }

    /**
     * Prepares result
     */
    private function result(){
        $ret = array();
        foreach( $this->urls as $keyVal => $urlVal ){
            $ret[$keyVal] = $this->tasks[$urlVal];
        }
        return $ret;
    }


    /**
     * Populates handles contents
     */
    private function populateUrlHandlesContent(){

        $this->setUrlHandles($this->urls);

        // количество активных потоков
        $active = null;

        // запускаем выполнение потоков
        do {
            $mrc = curl_multi_exec($this->cmh, $active);
        }
        while ($mrc == CURLM_CALL_MULTI_PERFORM);

        // выполняем, пока есть активные потоки
        while ($active && ($mrc == CURLM_OK)) {
            // если какой-либо поток готов к действиям
            if (curl_multi_select($this->cmh) == -1) {
                usleep(100);
            }

            // запускаем выполнение потоков
            do {
                $mrc = curl_multi_exec($this->cmh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }

        foreach ($this->tasks as $url => $task) {
            $this->tasks[$url] = curl_multi_getcontent($task);
            curl_multi_remove_handle($this->cmh, $task);
            curl_close($task);
        }
    }

    /**
     * Sets url's handles
     */
    private function setUrlHandles($url){

        foreach( $url as $urlVal ){
            $ch = $this->initializeCurl($urlVal);

            // добавляем дескриптор потока в мультикурл
            curl_multi_add_handle($this->cmh, $ch);
        }
    }


    /**
     * Initializes CURL
     * @param string $url
     * @return resource
     */
    private function initializeCurl($url){
        // инициализируем отдельное соединение (поток)
        $ch = curl_init($url);
//        // если будет редирект - перейти по нему
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        // возвращать результат
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // не возвращать http-заголовок
        curl_setopt($ch, CURLOPT_HEADER, $this->retHeader);
        // таймаут соединения
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        // таймаут ожидания
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        // добавляем дескриптор потока в массив заданий
        $this->tasks[$url] = $ch;

        return $ch;
    }

}