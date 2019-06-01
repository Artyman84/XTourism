<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 03.09.14
 * Time: 18:14
 * To change this template use File | Settings | File Templates.
 */

/**
 * TUtil class
 */
class TUtil {

    /**
     * Multiple inserts data
     * @param string $table
     * @param array $data
     * @param int $maxPortions
     */
    public static function multipleInsertData($table, $data, $maxPortions=5000){
        $dividedData = array_chunk($data, $maxPortions);
        $builder = Yii::app()->db->schema->commandBuilder;

        foreach( $dividedData as $insertData ){
            $command = $builder->createMultipleInsertCommand('{{' . $table . '}}', $insertData);
            $command->execute();
        }
    }

    /**
     * Multiple raw inserts data
     * @param string $table
     * @param array $data
     * @param int $maxPortions
     */
    public static function multipleRawInsertData($table, $data, $maxPortions=5000){
        $dividedData = array_chunk($data, $maxPortions);
        $table = '`xt_' . $table . '` ';
        $fields = '(`' . implode('`,`', array_keys($data[0])) . '`) ';
        $db = Yii::app()->db;

        foreach( $dividedData as $insertData ){

            $d = array();
            foreach($insertData as $insData){
                $d[] = '("' . implode('","', $insData) . '")';
            }

            $db->createCommand('INSERT INTO ' . $table . $fields . 'VALUES ' . implode(',', $d))->execute();
        }
    }

    /**
     * Merge arrays fast
     * @param array &$ar1
     * @param array $ar2
     */
    public static function fastArrayMerge(&$ar1, $ar2){
        foreach( $ar2 as $el2 ){
            $ar1[] = $el2;
        }
    }

    /**
     * Sorts array
     * @param array &$data
     * @param string $field
     * @return bool
     */
    public static function mSort(&$data, $field){
        $sorting = array();
        foreach($data as $key => $item){
            $sorting[$key] = $item->$field;
        }

        return array_multisort($sorting, SORT_ASC, $data);
    }

    /**
     * Returns list of elements grouped by key
     * @param array $array - list of objects
     * @param string $key
     * @return array
     */
    public static function listKey($array, $key='id') {

        $list = [];
        foreach ($array as $item) {
            $list[$item->$key] = $item;
        }
        return $list;
    }

    /**
     * Returns js-encoded list of elements
     * @param array $array - list of objects
     * @param string $key
     * @param string $value
     * @return string
     */
    public static function jsonList($array, $key='id', $value=null) {

        $list = [];
        foreach ($array as $item) {
            $list['_' . $item->$key] = $value ? $item->$value : $item;
        }
        return CJSON::encode($list);
    }

    /**
     * Returns list of keys of elements
     * @param array $array - list of objects
     * @param string $key
     * @return array
     */
    public static function keys($array, $key='id') {
        $keys = [];
        foreach ($array as $item) {
            $keys[] = $item->$key;
        }
        return $keys;
    }

    /**
     * Tears digits from a string
     * @param string $str
     * @return bool|int
     */
    public static function getStrDigits($str){
        $str = (string)$str;
        $number = '';

        for( $i=0, $len = strlen($str); $i<$len; ++$i  ){
            if( ($int = (int)$str[$i]) ){
                $number .= $int;
            }
        }

        return $number ? (int)$number : false;
    }


    /**
     * Returns google url
     * @param float $lat
     * @param float $lon
     * @return string
     */
    public static function googleUrl($lat, $lon){
        return 'http://maps.google.com/maps?q=loc:' . $lat . ',' . $lon;
    }


    /**
     * Creates full url address
     * @param string $route
     * @param array $params
     * @param string $ampersand
     * @return string
     */
    public static function createFullUrl($route, $params, $ampersand='&'){
        return Yii::app()->request->hostInfo . Yii::app()->createUrl($route, $params, $ampersand);
    }

    /**
     * @param $string
     * @return string
     */
    public static function base64url_encode($string) {
        return strtr(base64_encode($string), '+/', '-_');
    }

    /**
     * @param string $string
     * @return string
     */
    public static function base64url_decode($string) {
        return base64_decode(strtr($string, '-_', '+/'));
    }

    /**
     * @param string $string
     * @param null|string $key
     * @return string
     */
    public static function encrypt( $string, $key=null ) {
        $key = $key ? $key : Yii::app()->params['encrypted_salt'];
        $encrypted = '';
        $n = strlen( $string );
        $k = strlen( $key );
        $j = 0;

        for ($i = 0; $i < $n; $i++) {
            $string_char = ord( $string[$i] );
            $key_char = ord( $key[$j] );
            $crypt_char = $key_char ^ $string_char;
            $encrypted = $encrypted . chr( $crypt_char );
            if (++$j == $k)
                $j = 0;
        }
        return $encrypted;
    }

    /**
     * Encoding hotel's id
     * @param int $id
     * @return string
     */
    public static function encode_hotel_id($id){
        $id = Yii::app()->params['hotel_id_prefix'] . ':' . $id;
        return self::base64url_encode(self::encrypt($id));
    }

    /**
     * Decoding hotel's id
     * @param string $encoded_id
     * @return int|null
     */
    public static function decode_hotel_id($encoded_id){
        $data = self::encrypt(self::base64url_decode($encoded_id));
        $data = explode(':', $data);

        $ret = null;
        if( count($data) == 2 && $data[0] == Yii::app()->params['hotel_id_prefix'] && (int)$data[1] ) {
            $ret = $data[1];
        }

        return $ret;
    }

    /**
     * Returns request url
     * @return string
     */
    public static function request_url() {
        $result = '';
        $default_port = 80;

        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {
            $result .= 'https://';
            $default_port = 443;
        } else {
            $result .= 'http://';
        }

        $result .= $_SERVER['SERVER_NAME'];

        if ($_SERVER['SERVER_PORT'] != $default_port) {
            $result .= ':'.$_SERVER['SERVER_PORT'];
        }

        $result .= $_SERVER['REQUEST_URI'];

        return $result;
    }

    /**
     * @param $word
     * @return string
     */
    public static function mb_ucfirst ($word) {
        return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr(mb_convert_case($word, MB_CASE_LOWER, 'UTF-8'), 1, mb_strlen($word), 'UTF-8');
    }


    /**
     * Chunks number by steps and return it in array representation
     * @param int $number
     * @param int $step
     * @return array
     */
    public static function chunk_number($number, $step){

        $ret = [];

        if( ($count = floor($number/$step)) ) {
            $ret = array_fill(0, $count, $step);
        }

        if( ($rest = $number%$step) ){
            $ret[] = $rest;
        }

        return $ret;
    }

    /**
     * Log Pre
     * @param mixed $data
     * @param bool $die
     */
    public static function LogPre($data, $die=false){

        echo '<pre>';
            var_dump($data);
        echo '</pre;>';

        if( $die ){
            die();
        }
    }

}