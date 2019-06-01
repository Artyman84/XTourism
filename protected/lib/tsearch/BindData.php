<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 09.01.15
 * Time: 16:09
 * To change this template use File | Settings | File Templates.
 */

namespace TSearch;

use Yii;
use PDO;
use TUtil;

//ini_set("mbstring.internal_encoding", "utf8");
set_time_limit(0);
ini_set('memory_limit', '-1');

class BindData {

    /**
     * Max resorts for hotels
     */
    const MAX_RESORTS_FOR_HOTELS = 100;

    /**
     * Max countries for resorts
     */
    const MAX_COUNTRIES_FOR_RESORTS = 20;

    /**
     * @var int
     */
    private $oid;

    /**
     * __construct
     * @param int $oid
     */
    private function __construct($oid){
        $this->oid = $oid;
    }

    /**
     * Returns instance of TBindData
     * @param int $oid
     * @return null|BindData
     */
    public static function inst($oid){
        $oid = (int)$oid;
        return $oid ? new self($oid) : null;
    }

    /**
     * Binds elements directly
     * @param string $table
     * @param array|int $elements
     * @param int $dirId
     * @return bool
     */
    public function bindDirectly($table, $elements, $dirId){

        $elements = (array)$elements;
        $dirId = (int)$dirId;

        $db = Yii::app()->db;
        $dirId = $db->createCommand()->select('id')->from('{{directory_' . $table . '}}')->where('id = :id', [':id' => $dirId])->queryScalar();

        if( $dirId ){

            $bindElements = Yii::app()->db->createCommand()
                ->select('element_id')
                ->from('{{operator_' . $table . '}}')
                ->where(['AND', 'operator_id = :oid', 'directory_id = 0', ['IN', 'element_id', $elements]], [':oid' => $this->oid])
                ->queryColumn();

            if( !empty($bindElements) ){
                $this->createRelations($bindElements, $dirId, $table);

                if( $table == 'countries' ) {

                    $this->bindComparingResorts($bindElements);

                } elseif( $table == 'resorts' ) {

                    $this->bindComparingHotels($bindElements);
                }

                return $bindElements;
            }
        }

        return false;
    }

    /**
     * Binds elements comparing
     * @param string $table
     * @param null|int|array $parents
     * @param null|int|array $elements
     * @return array
     */
    public function bindComparing($table, $parents=null, $elements=null) {
        switch( $table ){
            case 'resorts':
                return $this->bindComparingResorts($parents, $elements);

            case 'hotels':
                return $this->bindComparingHotels($parents, $elements);

            default:

                $db = Yii::app()->db;

                $condition = ['AND', 'operator_id = :oid', 'directory_id = 0'];
                if( !empty($elements) ){
                    $condition[] = ['IN', 'element_id', (array)$elements];
                }

                $data = $db->createCommand()
                    ->select('element_id, name')
                    ->from('{{operator_' . $table . '}}')
                    ->where($condition, [':oid' => $this->oid])
                    ->setFetchMode(PDO::FETCH_OBJ)
                    ->queryAll();


                $_elements = array();
                foreach( $data as $e ) {
                    $_elements[$e->element_id] = mb_strtolower($e->name, 'utf8');
                }

                $dirData = TUtil::listKey(tbl\Directory::loadData($table, null, false), 'name');
                $dirData = $this->arKeysToLower($dirData);

                $related = array();
                foreach( $_elements as $eID => $eName ){
                    if( isset( $dirData[$eName] ) ){
                        $this->createRelations($eID, $dirData[$eName]->id, $table);
                        $related[] = $eID;
                    }
                }

                if( !empty($related) && $table == 'countries' ){
                    $this->bindComparingResorts($related);
                }

                return $related;
        }
    }

    /**
     * Binds comparing resorts
     * @param null|integer|array $countryIds
     * @param null|integer|array $elements
     * @return array
     */
    private function bindComparingResorts($countryIds=null, $elements=null){

        $condition = ['AND',
            'resort.operator_id = :oid',
            'country.operator_id = :oid',
            'resort.directory_id = 0',
            'country.directory_id != 0'
        ];

        if( !empty($countryIds) ){
            $condition[] = ['IN', 'resort.country', (array)$countryIds];
        }

        if( !empty($elements) ){
            $condition[] = ['IN', 'resort.element_id', (array)$elements];
        }

        $db = Yii::app()->db;
        $data = $db->createCommand()
            ->select('country.directory_id as dir_country_id, resort.element_id, resort.name')
            ->from('{{operator_resorts}} resort')
            ->join('{{operator_countries}} country', 'country.element_id = resort.country')
            ->where($condition, [':oid' => $this->oid])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $duplicate = $countries = [];
        foreach( $data as $resort ){
            $resortName = $this->prepareName($resort->name);

            if (!isset($countries[$resort->dir_country_id][$resortName]) && !isset($duplicate[$resort->dir_country_id . '-' . $resortName])) {

                $countries[$resort->dir_country_id][$resortName] = $resort->element_id;

            } else if (isset($countries[$resort->dir_country_id][$resortName])) {

                unset($countries[$resort->dir_country_id][$resortName]);
                $duplicate[$resort->dir_country_id . '-' . $resortName] = true;
            }

        }


        ///////////////////////// Collects directory data /////////////////////////
        $related = [];
        $dividedMainElements = array_chunk(array_keys($countries), self::MAX_COUNTRIES_FOR_RESORTS);

        foreach($dividedMainElements as $mainElements) {

            $data = $db->createCommand()
                ->select('resort.dir_country_id, resort.id, resort.name, resort.is_combined')
                ->from('{{directory_resorts}} resort')
                ->where(['IN', 'resort.dir_country_id', $mainElements])
                ->setFetchMode(PDO::FETCH_OBJ)
                ->queryAll();

            $dirCountries = [];

            foreach ($data as $resort) {
                $resortName = $this->prepareName($resort->name);
                $dirCountries[$resort->dir_country_id][$resortName] = $resort->id;
            }

            ///////////////////////////////////////////////////////////////////////////

            foreach ($dirCountries as $country_id => $dirResorts) {
                $resorts = $countries[$country_id];

                foreach ($dirResorts as $resortName => $resort_id) {
                    if (isset($resorts[$resortName])) {

                        $this->createRelations($resorts[$resortName], $resort_id, 'resorts');
                        $related[] = $resorts[$resortName];
                        unset($countries[$country_id][$resortName]);
                    }
                }
            }

        }

        //-------------------------------------------------------------------------------------------


        // Скрещивание курортов по курортам других туроператоров
        foreach($dividedMainElements as $mainElements) {

            $data = $db->createCommand()
                ->select('country.directory_id as dir_country_id, resort.directory_id as dir_resort_id, resort.name')
                ->from('{{operator_resorts}} resort')
                ->join('{{operator_countries}} country', 'country.element_id = resort.country')
                ->where(['AND', 'resort.operator_id != :oid', 'country.operator_id != :oid', 'resort.directory_id != 0', ['IN', 'country.directory_id', $mainElements]], [':oid' => $this->oid])
                ->setFetchMode(PDO::FETCH_OBJ)
                ->queryAll();

            foreach ($data as $resort) {

                $resortName = $this->prepareName($resort->name);

                // Если курорт скрещиваемого туроператора существует в скрещенных курортах других туроператоров
                // И если скрещиваемый курорт не был скрещен выше, тогда скрещиваем его.
                if (isset($countries[$resort->dir_country_id][$resortName]) && !in_array($countries[$resort->dir_country_id][$resortName], $related) ) {

                    $this->createRelations($countries[$resort->dir_country_id][$resortName], $resort->dir_resort_id, 'resorts');
                    $related[] = $countries[$resort->dir_country_id][$resortName];
                }
            }
        }

        if( !empty($related) ){
            $this->bindComparingHotels($related);
        }

        return $related;
    }

    /**
     * Binds comparing resorts
     * @param null|integer|array $resortIds
     * @param null|integer|array $elements
     * @return array
     */
    private function bindComparingHotels($resortIds=null, $elements=null){

        $condition = ['AND',
            'hotel.operator_id = :oid',
            'resort.operator_id = :oid',
            'hotel.directory_id = 0',
            'resort.directory_id != 0'
        ];

        if( !empty($resortIds) ){
            $condition[] = ['IN', 'hotel.resort', (array)$resortIds];
        }

        if( !empty($elements) ){
            $condition[] = ['IN', 'hotel.element_id', (array)$elements];
        }

        $db = Yii::app()->db;
        $data = $db->createCommand()
            ->select('resort.directory_id AS dir_resort_id, hotel.element_id, hotel.name')
            ->from('{{operator_hotels}} hotel')
            ->join('{{operator_resorts}} resort', 'resort.element_id = hotel.resort')
            ->where($condition, [':oid' => $this->oid])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $duplicate = $resorts = [];
        foreach( $data as $hotel ){
            $hotelName = $this->prepareName($hotel->name, ['hotel', 'отель']);

            if (!isset($resorts[$hotel->dir_resort_id][$hotelName]) && !isset($duplicate[$hotel->dir_resort_id . '-' . $hotelName])) {

                $resorts[$hotel->dir_resort_id][$hotelName] = $hotel->element_id;

            } else if (isset($resorts[$hotel->dir_resort_id][$hotelName])) {

                unset($resorts[$hotel->dir_resort_id][$hotelName]);
                $duplicate[$hotel->dir_resort_id . '-' . $hotelName] = true;
            }

        }

        ///////////////////////// Collects directory data /////////////////////////
        $related = [];
        $dividedMainElements = array_chunk(array_keys($resorts), self::MAX_RESORTS_FOR_HOTELS);

        foreach($dividedMainElements as $mainElements) {

            // Вытаскиваем отели для комбинированных курортов.
            $data_combined = $db->createCommand()
                ->select('main.id AS dir_resort_id, hotel.id, hotel.name')
                ->from('{{directory_resorts}} AS main')
                ->join('{{directory_resorts}} AS detail', 'detail.parent_id = main.id')
                ->join('{{directory_hotels}} AS hotel', 'hotel.dir_resort_id = detail.id')
                ->where(['AND', 'main.is_combined = 1', ['IN', 'main.id', $mainElements]])
                ->setFetchMode(PDO::FETCH_OBJ)
                ->queryAll();

            // Вытаскиваем отели для некомбинированных курортов.
            $data_not_combined = $db->createCommand()
                ->select('hotel.dir_resort_id, hotel.id, hotel.name')
                ->from('{{directory_hotels}} hotel')
                ->join('{{directory_resorts}} AS resort', 'resort.id = hotel.dir_resort_id')
                ->where(['AND', 'resort.is_combined = 0', ['IN', 'hotel.dir_resort_id', $mainElements]])
                ->setFetchMode(PDO::FETCH_OBJ)
                ->queryAll();

            $data = array_merge($data_combined, $data_not_combined);

            $duplicate = $dirResorts = [];

            foreach ($data as $hotel) {
                $hotelName = $this->prepareName($hotel->name, ['hotel', 'отель']);

                if (!isset($dirResorts[$hotel->dir_resort_id][$hotelName]) && !isset($duplicate[$hotel->dir_resort_id . '-' . $hotelName])) {

                    $dirResorts[$hotel->dir_resort_id][$hotelName] = $hotel->id;

                } elseif (isset($dirResorts[$hotel->dir_resort_id][$hotelName])) {

                    unset($dirResorts[$hotel->dir_resort_id][$hotelName]);
                    $duplicate[$hotel->dir_resort_id . '-' . $hotelName] = true;
                }

            }

            ///////////////////////////////////////////////////////////////////////////

            foreach ($dirResorts as $dir_resort_id => $dirHotels) {
                $hotels = $resorts[$dir_resort_id];
                foreach ($dirHotels as $hotelName => $hotel_id) {
                    if (isset($hotels[$hotelName])) {
                        $this->createRelations($hotels[$hotelName], $hotel_id, 'hotels');
                        $related[] = $hotels[$hotelName];
                        unset($resorts[$dir_resort_id][$hotelName]);
                    }
                }
            }

            ///////////////////////////////////////////////////////////////////////////

        }

        // Скрещивание отелей по отелям других туроператоров
        foreach($dividedMainElements as $mainElements) {

            $data = $db->createCommand()
                ->select('resort.directory_id AS dir_resort_id, hotel.directory_id as dir_hotel_id, hotel.name')
                ->from('{{operator_hotels}} hotel')
                ->join('{{operator_resorts}} resort', 'resort.element_id = hotel.resort')
                ->where(['AND', 'hotel.operator_id != :oid', 'resort.operator_id != :oid', 'hotel.directory_id != 0', ['IN', 'resort.directory_id', $mainElements]], [':oid' => $this->oid])
                ->setFetchMode(PDO::FETCH_OBJ)
                ->queryAll();

            foreach ($data as $hotel) {
                $hotelName = $this->prepareName($hotel->name);

                // Если отель скрещиваемого туроператора существует в скрещенных отелях других туроператоров
                // И если скрещиваемый отель не был скрещен выше, тогда скрещиваем его.
                if (isset($resorts[$hotel->dir_resort_id][$hotelName]) && !in_array($resorts[$hotel->dir_resort_id][$hotelName], $related)) {

                    $this->createRelations($resorts[$hotel->dir_resort_id][$hotelName], $hotel->dir_hotel_id, 'hotels');
                    $related[] = $resorts[$hotel->dir_resort_id][$hotelName];
                }
            }
        }

        return $related;
    }

    /**
     * @param string $name
     * @param null|array $replace
     * @return string
     */
    private function prepareName($name, array $replace=null){
        // Удаляем все знаки "тире"
        $name = str_replace('-', ' ', mb_strtolower($name, 'utf8'));

        // Удаляем все слова в круглых скобках в конце названий
        $name = preg_replace('/\([^)]+\)$/', '', $name);

        // Удаляем все пробелы в начале и в конце
        $name = trim($name);

        if (!empty($replace)) {

            foreach ($replace as $_replace) {
                $name = str_replace(' ' . $_replace . ' ', ' ', $name);

                $len = mb_strlen($_replace) + 1;

                if (mb_strpos($name, $_replace . ' ') === 0) {
                    $name = mb_substr($name, $len);
                }
                if (mb_strpos($name, ' ' . $_replace, max(0, mb_strlen($name) - $len)) !== false) {
                    $name = mb_substr($name, 0, -$len);
                }
            }
        }

        return $name;
    }

    /**
     * Creates relation between elements
     * @param array $el
     * @param int $id
     * @param string $t
     */
    private function createRelations($el, $id, $t){
        Yii::app()->db->createCommand()->update(
            '{{operator_' . $t . '}}',
            [
                'directory_id' => $id,
                'unread' => 0
            ],
            ['AND', 'operator_id = :oid', ['IN', 'element_id', $el] ],
            [':oid' => $this->oid]
        );
    }


    /**
     * Unbinds elements
     * @param string $table
     * @param mixed $elements
     * @return bool
     */
    public function unbind($table, $elements){
        $elements = (array)$elements;

        if( empty($elements) ){
            return false;
        }

        switch( $table ){
            case 'countries':
                $resorts = Yii::app()->db->createCommand()
                    ->select('element_id')
                    ->from('{{operator_resorts}}')
                    ->where(array('AND', 'operator_id = :oid', array('IN', 'country', $elements)), array(':oid' => $this->oid))
                    ->setFetchMode(PDO::FETCH_OBJ)
                    ->queryColumn();

                $this->unbind('resorts', $resorts);
                break;

            case 'resorts':
                $hotels = Yii::app()->db->createCommand()
                    ->select('element_id')
                    ->from('{{operator_hotels}}')
                    ->where(array('AND', 'operator_id = :oid', array('IN', 'resort', $elements)), array(':oid' => $this->oid))
                    ->setFetchMode(PDO::FETCH_OBJ)
                    ->queryColumn();

                $this->unbind('hotels', $hotels);
                break;
        }

        $this->destroyRelations($table, $elements);
    }

    /**
     * @param string $t
     * @param array|int $el
     */
    private function destroyRelations($t, $el){
        Yii::app()->db->createCommand()->update(
            '{{operator_' . $t . '}}',
            array('directory_id' => 0),
            array('AND', 'operator_id = :oid', array('IN', 'element_id', $el) ),
            array(':oid' => $this->oid)
        );
    }

    /**
     * Transforms string array keys to lower format
     * @param array $arr
     * @return array
     */
    public static function arKeysToLower(&$arr){
        $ret = array();
        foreach( $arr as $key => $value ){
            $k = mb_strtolower($key, 'utf8');
            $ret[$k] = $value;
        }

        return $ret;
    }

}