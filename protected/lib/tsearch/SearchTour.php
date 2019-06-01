<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 26.11.14
 * Time: 19:21
 * To change this template use File | Settings | File Templates.
 */

namespace TSearch;

use ArOperators;
use ArHotelPhotos;
use stdClass;
use TUtil;
use PDO;
use Yii;
use CJSON;

/**
 * Class SearchTour
 * @package TSearch
 */
class SearchTour {

    /**
     * @var array
     */
    private $directory_params;

    /**
     * @var array
     */
    private $operators_params;


    /**
     * SearchTour constructor.
     * @param array $params
     */
    public function __construct($params){
        $this->directory_params = $params;
        $this->operators_params = $this->operatorsParams($params);
    }

    /**
     * Process params by operators
     * @param array $params
     * @return array
     */
    private function operatorsParams($params){
        $operators = array_keys(TOperator::operatorsInfo(empty($params['operators']) ? null : $params['operators']));

        $depCities = [];
        if( !empty( $params['dirDepCity'] ) ) {
            $depCities = tbl\Operator::table('dep_cities')->loadData($operators, ['directory_id' => $params['dirDepCity']], true);
            $operators = array_intersect($operators, array_keys($depCities));
        }

        $countries = [];
        if( !empty( $params['dirCountry'] ) ) {
            $countries = tbl\Operator::table('countries')->loadData($operators, ['directory_id' => $params['dirCountry']], true);
            $operators = array_intersect($operators, array_keys($countries));
        }

        $categories = [];
        if( !empty( $params['dirHotelCategory'] ) ) {
            $categories = tbl\Operator::table('hotel_categories')->loadData($operators, ['directory_id' => $params['dirHotelCategory']], true);
            $operators = array_intersect($operators, array_keys($categories));
        }

        $meals = [];
        if( !empty( $params['dirMeals'] ) ) {
            $meals = tbl\Operator::table('meals')->loadData($operators, ['directory_id' => $params['dirMeals']], true);
            $operators = array_intersect($operators, array_keys($meals));
        }

        $resorts = [];
        if( !empty( $params['dirResort'] ) ) {
            $resorts = tbl\Operator::table('resorts')->loadData($operators, ['directory_id' => $params['dirResort']], true);
            $operators = array_intersect($operators, array_keys($resorts));
        }

        $hotels = [];
        if( !empty( $params['dirHotels'] ) ) {
            $hotels = tbl\Operator::table('hotels')->loadData($operators, ['directory_id' => $params['dirHotels']], true);
            $operators = array_intersect($operators, array_keys($hotels));
        }

        $p = [];
        foreach( $operators as $oid ){

            /************************** Directory params **************************/

            if( isset($depCities[$oid]) ){
                $p[$oid]['depCity'] = array_keys($depCities[$oid]);
            }

            if( isset($countries[$oid]) ){
                $p[$oid]['country'] = array_keys($countries[$oid]);
            }

            if( isset($categories[$oid]) ){
                $p[$oid]['category'] = array_keys($categories[$oid]);
            }

            if( isset($meals[$oid]) ){
                $p[$oid]['meal'] = array_keys($meals[$oid]);
            }

            if( isset($resorts[$oid]) ){
                $p[$oid]['resort'] = array_slice(array_keys($resorts[$oid]), 0, 10);
            }

            if( isset($hotels[$oid]) ){
                $p[$oid]['hotel'] = array_slice(array_keys($hotels[$oid]), 0, 10);
            }


            /************************** Database params **************************/
            if( !empty( $params['availableDateFrom'] ) ){
                $p[$oid]['availableDateFrom'] = $params['availableDateFrom'];
            }

            if( !empty( $params['availableDateTo'] ) ){
                $p[$oid]['availableDateTo'] = $params['availableDateTo'];
            }

            if( !empty( $params['nightFrom'] ) ){
                $p[$oid]['durationFrom'] = $params['nightFrom'];
            }

            if( !empty( $params['nightTo'] ) ){
                $p[$oid]['durationTo'] = $params['nightTo'];
            }

            if( !empty( $params['adults'] ) ){
                $p[$oid]['adults'] = $params['adults'];
            }

            if( !empty( $params['children'] ) ){

                $p[$oid]['children'] = count($params['children']);
                foreach( $params['children'] as $chage ){
                    $p[$oid]['chage'][] = $chage;
                }
            }

            if( !empty( $params['minPrice'] ) ){
                $p[$oid]['minPrice'] = $params['minPrice'];
            }

            if( !empty( $params['maxPrice'] ) ){
                $p[$oid]['maxPrice'] = $params['maxPrice'];
            }

            if( isset( $params['currency'] ) ){
                $p[$oid]['currency'] = (int)$params['currency'];
            }

            if( isset( $params['hotelNonstop'] ) ){
                $p[$oid]['hotelNonstop'] = (int)$params['hotelNonstop'];
            }

            if( isset( $params['flightNonstop'] ) ){
                $p[$oid]['flightNonstop'] = (int)$params['flightNonstop'];
            }

        }

        return $p;
    }


    /**
     * Loads tours data
     * @param integer $mode
     * @param mixed $callback
     * @param bool $echo
     * @return bool
     *
     */
    public function loadTours($mode=0, $callback=null, $echo=true){

        if( empty( $this->operators_params ) )
            return false;

        $params = $this->operators_params;
        $operators = TOperator::newOperators(array_keys($params));
        $MAX_OPERATOR_TOURS = ceil(TOperator::MAX_TOURS_NUMBER / count($operators));

        // Set for all operators max page size
        foreach( $operators as $oid => $o ){
            $params[$oid]['pageSize'] = $MAX_OPERATOR_TOURS;
        }

        $urls = [];

        foreach($operators as $oid => $operator ) {

            $_urls = $operator->buildUrls('tours', isset($params[$oid]) ? $params[$oid] : []);
            if( !empty($_urls) ) {
                $urls[$oid] = $_urls;
            }
        }

        if( !empty($urls) ) {

            $cURL = new MultiCURL();

            if( $echo ) ob_start();

            foreach( $urls as $oid => $_urls ) {
                foreach($_urls as $url) {

                    $o = $operators[$oid];

                    $cURL->add($url, function($content, $info) use($o, $callback, $echo, $mode){

                        $_data = $o->getNormalizedData('tours', $content, $info);
                        $output = ['oid' => $o->operatorId(), 't' => false];

                        if( null !== $_data ) {

                            $output['t'] = null;
                            if( !empty($_data) ) {

                                $output['t'] = $this->processTours([$o->operatorId() => $_data], $mode);

                                if (is_callable($callback)) {
                                    $output['t'] = call_user_func($callback, $output['t']);
                                }
                            }
                        }

                        if( $echo ) {
                            echo CJSON::encode($output) . "\n";
                            ob_flush();
                            flush();
                        }

                    });

                }
            }

            $cURL->request();
        }

        return true;
    }


    /**
     * Process tours data
     * @param array $data
     * @param int $mode
     * @return array
     */
    private function processTours($data, $mode=0){
        $ret = array();

        if( !empty( $data ) ){
            $oIds = array_keys($data);
            $operators = TOperator::operatorsInfo($oIds);

            $dbMeals = tbl\Operator::table('meals')->loadData($oIds, [], true);
            $dirMeals = tbl\Directory::loadData('meals', ['disabled' => 0]);

            $dbHStatuses = tbl\Operator::table('hotel_statuses')->loadData($oIds, [], true);
            $dirHStatuses = tbl\Directory::loadData('hotel_statuses', ['disabled' => 0]);

            $dbTStatuses = tbl\Operator::table('ticket_statuses')->loadData($oIds, [], true);
            $dirTStatuses = tbl\Directory::loadData('ticket_statuses', ['disabled' => 0]);

            $dbCategories = tbl\Operator::table('hotel_categories')->loadData($oIds, [], true);

            $hotelsElements = [];
            $hData = [];

            foreach( $data as $tours ){
                if( $tours ) {
                    foreach ($tours as $tour) {
                        $hotelsElements[] = $tour->hotel->hotelId;
                        $hData[$tour->hotel->oId][$tour->hotel->hotelId][] = $tour;
                    }
                }
            }

            $dirDepCity = 0;
            if( !empty($hData) && ($temp_hotel = current(current(current($hData)))->hotel) ) {
                $depCities = tbl\Operator::table('dep_cities')->loadData($temp_hotel->oId, ['element_id' => $temp_hotel->depCity]);
                if( $depCities ){
                    $dirDepCity = (int)current($depCities)->directory_id;
                }
            }


            unset($data);
            list($groupedHotels, $dHotels) = $this->collectDirectoryHotels($oIds, array_unique($hotelsElements));

            $checkingHotels = $scores = [];
            $path_separator = ',&nbsp;';
            foreach( $dHotels as $dHotel ){

                // Если пришел отель, у которого категория не находится в списке категорий параметров поисковика
                // тогда пропускаем такой отель!
                if( !empty($this->directory_params['dirHotelCategory']) && !in_array($dHotel->dir_category_id, $this->directory_params['dirHotelCategory']) ){
                    continue;
                }

                foreach($groupedHotels[$dHotel->id] as $oid => $element) {

                    if (isset($hData[$oid][$element])) {
                        foreach ($hData[$oid][$element] as $tour) {

                            $hotel = $tour->hotel;

                            // collect hotel data
                            $tHotel = new stdClass();
                            $tHotel->oId = $hotel->oId;
                            $tHotel->oName = $operators[$hotel->oId]->name;
                            $tHotel->oClass = $operators[$hotel->oId]->class;
                            $tHotel->oUrl = $operators[$hotel->oId]->url;
                            $tHotel->oImgPath = ArOperators::imgPath($operators[$hotel->oId]->class);

                            // По умлочанию данные отеля берем из справочника
                            $tHotel->hId = $dHotel->id;
                            $tHotel->hHashId = TUtil::encode_hotel_id($dHotel->id);
                            $tHotel->hName = $dHotel->name;

                            $photo_address = $dHotel->dir_country_id . '/' . $dHotel->dir_city_id . '/' . $dHotel->id . '/1.jpg';
                            $imgPath = ArHotelPhotos::imgBaseUrl() . $photo_address;

                            if( !file_exists(ArHotelPhotos::imgBasePath() . $photo_address) ){
                                $imgPath = ArHotelPhotos::imgBaseUrl() . 'no_photo.jpg';
                            }

                            $tHotel->hImgPath = $imgPath;
                            $tHotel->hCountry = $dHotel->country;
                            $tHotel->hResort = $dHotel->resort;
                            $tHotel->hResortPath = ($dHotel->resort_parent_name ? $dHotel->resort_parent_name . $path_separator : '') . $dHotel->resort;
                            $tHotel->hCategory = $dHotel->category_name;
                            $tHotel->hRating = $dHotel->rating;
                            $tHotel->hVoices = $dHotel->voices;

                            /*
                             * Если категория отеля ТО отличается от категории отеля из справочника
                             * помещаем отель в список для проверки!
                             */
                            $dbCategory = isset($dbCategories[$hotel->oId][$hotel->categoryId]) ? $dbCategories[$hotel->oId][$hotel->categoryId] : null;
                            if ($dbCategory && $dbCategory->directory_id != $dHotel->dir_category_id) {

                                /*
                                 * Для того что бы брать категорию из ТО - раскомментируйте строчку ниже.
                                 */
                                //$tHotel->hCategory = $dirCategories[$dbCategory->directory_id]->name;

                                $checkingHotels[] = $dHotel->id;
                            }

                            // Рейтинги
                            if (!isset($scores[$dHotel->id])) {
                                $scores[$dHotel->id] = $this->getHotelScores($dHotel->scores);
                            }

                            $tHotel->hScores = $scores[$dHotel->id];

                            // Размещение
                            if (!isset($hotel->residence)) {
                                $tHotel->hResidence = TourHelper::getResidence($tour->adults, $tour->children);
                            } else {
                                $tHotel->hResidence = $hotel->residence;
                            }

                            // Статус отеля
                            $tHotel->hCssStatus = 'icon-not-available';
                            $tHotel->hStatusDescription = 'нет';

                            if (isset($dbHStatuses[$hotel->oId][$hotel->status])) {
                                $dirStatusId = $dbHStatuses[$hotel->oId][$hotel->status]->directory_id;

                                if (isset($dirHStatuses[$dirStatusId])) {
                                    $tHotel->hCssStatus = 'icon-' . $dirHStatuses[$dirStatusId]->name;
                                    $tHotel->hStatusDescription = $dirHStatuses[$dirStatusId]->description;;
                                }
                            }

                            // Статусы эконом билетов туда и обратно
                            $tHotel->hCssETicketTo = $tHotel->hCssETicketFrom = 'icon-not-available';
                            $tHotel->hETicketDescriptionFrom = $tHotel->hETicketDescriptionTo = 'нет';

                            if (isset($dbTStatuses[$hotel->oId][$tour->eTicketId])) {
                                $dirStatusId = $dbTStatuses[$hotel->oId][$tour->eTicketId]->directory_id;

                                if (isset($dirTStatuses[$dirStatusId])) {
                                    $tHotel->hCssETicketTo = $tHotel->hCssETicketFrom = 'icon-' . $dirTStatuses[$dirStatusId]->name;
                                    $tHotel->hETicketDescriptionFrom = $tHotel->hETicketDescriptionTo = $dirTStatuses[$dirStatusId]->description;;
                                }
                            }

                            // Статусы бизнес-класс билетов туда и обратно
                            $tHotel->hCssBTicketFrom = $tHotel->hCssBTicketTo = 'icon-not-available';
                            $tHotel->hBTicketDescriptionFrom = $tHotel->hBTicketDescriptionTo = 'нет';

                            if (isset($dbTStatuses[$hotel->oId][$tour->bTicketId])) {
                                $dirStatusId = $dbTStatuses[$hotel->oId][$tour->bTicketId]->directory_id;

                                if (isset($dirTStatuses[$dirStatusId])) {
                                    $tHotel->hCssBTicketFrom = $tHotel->hCssBTicketTo = 'icon-' . $dirTStatuses[$dirStatusId]->name;
                                    $tHotel->hBTicketDescriptionFrom = $tHotel->hBTicketDescriptionTo = $dirTStatuses[$dirStatusId]->description;;
                                }
                            }

                            // collect tour data
                            $tHotel->tourId = $tour->tourId;
                            $tHotel->tPrice = $tour->price;
                            $tHotel->tNormalizedPrice = TourHelper::normalizePrice($tour->price);
                            $tHotel->tCurrency = $tour->currency;
                            $tHotel->tHtmlCurrency = TourHelper::htmlCurrency($tour->currency);
                            $tHotel->tDepDate = $tour->departureDate;
                            $tHotel->tStartResDate = $hotel->startResDate;
                            $tHotel->tStartResDateDM = date('d.m', $hotel->startResDate);
                            $tHotel->tEndResDate = $hotel->endResDate;
                            $tHotel->tStartWeekDay = date('d.m', $hotel->startResDate) . ', ' . TourHelper::weekDay($hotel->startResDate);
                            $tHotel->tEndWeekDay = date('d.m', $hotel->endResDate) . ', ' . TourHelper::weekDay($hotel->endResDate);
                            $tHotel->tRoom = $hotel->room;
                            $tHotel->tNights = $tour->nights;
                            $tHotel->tNightsTxt = TourHelper::getTourNights($tour->nights);
                            $tHotel->tDaysTxt = TourHelper::getTourNights($tour->nights + 1, false);
                            $tHotel->tMeal = $hotel->mealName;
                            $tHotel->tMealDescription = $hotel->mealName;

                            // Питание
                            $dirMealId = 0;
                            if (isset($dbMeals[$hotel->oId][$hotel->mealId])) {
                                $dirMealId = $dbMeals[$hotel->oId][$hotel->mealId]->directory_id;

                                if (isset($dirMeals[$dirMealId])) {
                                    //$tHotel->tMeal = $dirMeals[$dirMealId]->name;
                                    $tHotel->tMealDescription = $dirMeals[$dirMealId]->description;
                                    $tHotel->tMealDirId = $dirMealId;
                                }
                            }

                            $tHotel->tRequestParams = TUtil::base64url_encode(TUtil::encrypt(CJSON::encode([
                                'tid' => $tour->tourId,   'oid' => $hotel->oId,
                                'hid' => $dHotel->id,     'cid' => $dirDepCity,
                                'mid' => $dirMealId,      'cur' => $tour->currency,
                                'prc' => $tour->price,    'ngt' => $tour->nights,
                                'ad' => $tour->adults,    'ch' => $tour->children,
                                'rm' => $hotel->room,     'date' => $hotel->startResDate
                            ])));


                            if ($mode) {
                                $ret[] = $tHotel;
                            } else {
                                $ret["_" . $dHotel->id][] = $tHotel;
                            }
                        }
                    }

                }
            }

        }

        // Saves hotel which need to be checked by categories
        $this->saveCheckingHotel($checkingHotels);

        return $ret;
    }

    /**
     * Collects directory hotels
     *
     * @param array $operators
     * @param array $oHotels
     * @return array
     *
     */
    private function collectDirectoryHotels($operators, $oHotels){
        $db = Yii::app()->db;
        $dirHotels = $db->createCommand()
            ->select('directory_id, operator_id, element_id')
            ->from('{{operator_hotels}}')
            ->where(['AND', 'directory_id != 0', ['AND', ['IN', 'operator_id', $operators], ['IN', 'element_id', $oHotels]] ])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $groupedHotels = [];
        foreach( $dirHotels as $hotel ){
            $groupedHotels[$hotel->directory_id][$hotel->operator_id] = $hotel->element_id;
        }


        $dHotels = $db->createCommand()
            ->select('
                  dh.*,
                  dr.name AS resort,
                  dr2.name AS resort_parent_name,
                  dc.name AS country,
                  dhc.id AS dir_category_id,
                  dhc.name AS category_name,
                  hr.rating,
                  hr.voices,
                  hr.scores')
            ->from('{{directory_hotels}}' . ' dh')
            ->join('{{directory_countries}}' . ' dc', 'dc.id = dh.dir_country_id')
            ->join('{{directory_resorts}}' . ' dr', 'dr.id = dh.dir_resort_id')
            ->leftJoin('{{directory_resorts}}' . ' dr2', 'dr.parent_id != 0 AND dr2.id = dr.parent_id')
            ->join('{{directory_hotel_categories}}' . ' dhc', 'dhc.id = dh.dir_category_id')
            ->join('{{hotel_ratings}} hr', 'hr.dir_hotel_id = dh.id')
            ->where(['AND', 'dh.disabled = 0', ['IN', 'dh.id', array_keys($groupedHotels)] ])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();


        return [$groupedHotels, $dHotels];
    }

    /**
     * Saves hotels which need to be checking by categories
     * @param array $checkingHotels
     */
    private function saveCheckingHotel($checkingHotels){

        if( !empty($checkingHotels) ) {
            $checkingHotels = array_unique($checkingHotels);
            $db = Yii::app()->db;
            $existsHotels = $db->createCommand()
                ->select('dir_hotel_id')
                ->from('{{directory_hotels_checking_categories}}')
                ->where(['IN', 'dir_hotel_id', $checkingHotels])
                ->queryColumn();

            $checkingHotels = array_diff($checkingHotels, $existsHotels);


            if( !empty($checkingHotels) ){

                $repData = [];
                foreach($checkingHotels as $hotel){
                    $repData[] = '(' . (int)$hotel . ', 0)';
                }

                $repData = implode(',', $repData);
                $db->createCommand("REPLACE INTO {{directory_hotels_checking_categories}} (`dir_hotel_id`, `checked`) VALUES $repData")->execute();
            }

        }
    }

    /**
     * Returns hotel scores
     * @param string $scores
     * @return string
     */
    private function getHotelScores($scores){
        $scr = '';
        $data = json_decode($scores, true);
        $i = 0;
        foreach($data as $obj){
            $delimiter = '';
            if( $scr ){
                $delimiter = ' | ';
            }

            $scr .= $delimiter . mb_strtolower($obj['name'], 'utf8') . ': ' . str_replace(',', '.', $obj['value']);
            ++$i;

            if( $i >= 5 ){
                break;
            }
        }

        return $scr;
    }

}