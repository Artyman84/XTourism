<?php

namespace TSearch\drivers;

use TSearch\TOperator;
use TSearch\TourHelper;
use TLibXml;
use SimpleXMLElement;
use stdClass;
use XMLReader;

class ICSTour extends TOperator {

    /**
     * Max number of tours( parameter "pageSize" for tours query )
     */
    const MAX_TOURS_NUMBER_PER_PAGE = 100;

    /**
     * Max URL-requests number at once
     */
    const MAX_URL_REQUEST_NUMBER = 20;

    /**
     * @var string
     */
    protected $authURL = '';


    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var array
     */
    protected $parametersSettings = [
        'arrayBracket' => ''
    ];

    /**
     * @var array
     */
    protected $param_tours_keys = [
        'country' => 'cnt', // страна прилета /*REQUIRED*/
        'depCity' => 'city', // город вылета /*REQUIRED*/
        'hotel' => 'hotel', // Отели
        'meal' => 'meal', // тип питания
        'category' => 'category', // категория отели
        'resort' => 'resort', // курорт
        'availableDateFrom' => 'date1', // Диапазон поиска туров: минимальная дата вылета: YYYY-MM-DD (2014-06-19)
        'availableDateTo' => 'date2', // Диапазон поиска туров: максимальная дата вылета: YYYY-MM-DD (2014-06-19)
        'duration' => 'duration', // продолжительность тура
        'durationFrom' => 'duration_from', // Задает нижнюю границу продолжительности тура. Отменяет действие параметра duration
        'durationTo' => 'duration_to', // Задает верхнюю границу продолжительности тура. Отменяет действие параметра duration
        'adults' => 'ad', // количество взрослых
        'children' => 'ch', // количество детей
        'chage' => 'chage', // возрасть детей
        'maxPrice' => 'max_price', // Максимальная цена тура
        'currency' => 'cur', // Валюта: используется только с параметром "max_price" 0 - рубли, 1 - валюта страны
        'pageSize' => 'pagesize', // максимальное количество туров на одной странице
        'page' => 'page', // страница поиска
        'hotelNonstop' => 'hotel_nonstop', // туры с отелями не в стопе
        'flightNonstop' => 'flight_nonstop', // туры с доступными билеты
    ];

    /**
     * @var array
     */
    protected $param_tour_info_keys = [
        'id' => 'tour_id',    // Идентификатор тура /*REQUIRED*/
    ];

    /**
     * @var array
     */
    protected $param_countries_keys = [
        'depCity' => 'city' // Город вылета
    ];

    /**
     * @var array
     */
    protected $param_dep_cities_keys = [];

    /**
     * @var array
     */
    protected $param_resorts_keys = [
        'country' => 'cnt',  // Страна прилета
    ];

    /**
     * @var array
     */
    protected $param_hotels_keys = [
        'country' => 'cnt',  // Страна прилета /*REQUIRED*/
        'depCity' => 'city',  // Город вылета
        'resort' => 'resort',   // Курорт
        'category' => 'category',   // Категория отеля
    ];

    /**
     * @var array
     */
    protected $param_hotel_info_keys = [
        'id' => 'id',    // Идентификатор отеля /*REQUIRED*/
    ];


    /**
     * @var array
     */
    protected $param_hotel_categories_keys = [];

    /**
     * @var array
     */
    protected $param_meals_keys = [];

    /**
     * @var array
     */
    protected $param_hotel_statuses_keys = [];

    /**
     * @var array
     */
    protected $param_ticket_statuses_keys = [];

    /**
     * @var array
     */
    protected $param_hotel_min_prices_keys = [
        'country' => 'cnt',          // Страна прилета /*REQUIRED*/
        'depCity' => 'city',         // Город вылета  /*REQUIRED*/
        'duration' => 'duration',    // Продолжительность тура  /*REQUIRED*/
        'date' => 'date',            // Дата вылета /*REQUIRED*/
        'adults' => 'ad',            // Количество взрослых /*REQUIRED*/
        'children' => 'ch',          // Количество детей
        'chage' => 'chage',          // Категория отеля
        'resort' => 'resort',        // Курорт
        'warranty' => 'warranty',    // Гарантия на туры
    ];

    /**
     * @var array
     */
    protected $urls = array(
        'countries'          => 'http://api.icstrvl.ru/tour-api/getCountries.xml', // страны прилета
        'dep_cities'         => 'http://api.icstrvl.ru/tour-api/getDepartures.xml', // города вылета
        'tours'              => 'http://api.icstrvl.ru/tour-api/getTours.xml', // список туров
        'tour_info'          => 'http://api.icstrvl.ru/tour-api/getTourInfo.xml', // УРЛ информации о туре
        'resorts'            => 'http://api.icstrvl.ru/tour-api/getResorts.xml', // список всех курортов (страны)
        'hotels'             => 'http://api.icstrvl.ru/tour-api/getHotels.xml', // список отелей
        'hotel_info'         => 'http://api.icstrvl.ru/tour-api/getHotelInfo.xml', // УРЛ информации об отеле
        'hotel_categories'   => 'http://api.icstrvl.ru/tour-api/getHotelCategories.xml', // список категорий отелей
        'meals'              => 'http://api.icstrvl.ru/tour-api/getMealBasis.xml', // список типов питания
        'hotel_statuses'     => 'http://api.icstrvl.ru/tour-api/getHotelStatuses.xml', // список статусов проживания в отелях
        'ticket_statuses'    => 'http://api.icstrvl.ru/tour-api/getTicketStatuses.xml', // список статусов билетов
        'hotel_min_prices'   => 'http://api.icstrvl.ru/tour-api/getMinHotelPrices.xml', // список минимальных цен на отели страны
    );


    /******************************************************************************************************
     *                                          Private Methods                                           *
     ******************************************************************************************************/

    /**
     * Normalizes date
     * @param string $date
     * @param bool $timestamp
     * @return string
     */
    private function normalizeDate($date, $timestamp=false){
        $datePieces = explode('-', $date);
        $date = $datePieces[2] . '/' . $datePieces[1] . '/' . $datePieces[0];
        return $timestamp ? strtotime($datePieces[1] . '/' . $datePieces[2] . '/' . $datePieces[0]) : $date;
    }

    /**
     * Converts date to ICS Tour format
     * @param string|integer $date
     * @return string
     */
    private function convertDateToICS($date){
        if( is_int($date) ){
            $date = date('d.m.Y', $date);
        }

        $datePieces = explode('.', $date);
        $date = $datePieces[2] . '-' . $datePieces[1] . '-' . $datePieces[0];
        return $date;
    }

    /**
     * Builds urls for countries
     * @param string $query
     * @param array $params
     * @return array
     */
    private function buildUrlsFor_countries($query, $params=[]){

        if( !empty( $params['depCity'] ) ){

            // Города вылета
            $depCities = (array)$params['depCity'];
            $urls = [];

            foreach( $depCities as $depCity ){
                $urls[] = $this->buildUrl($query, ['depCity' => $depCity]);
            }

            return $urls;
        }

        return [ $this->buildUrl($query) ];
    }

    /**
     * Builds urls for resorts
     * @param string $query
     * @param array $params
     * @return array
     */
    private function buildUrlsFor_resorts($query, $params=[]){
        if( !empty( $params['country'] ) ){

            // Страны
            $countries = (array)$params['country'];
            $urls = [];

            foreach( $countries as $country ){
                $urls[] = $this->buildUrl($query, ['country' => $country]);
            }

            return $urls;
        }

        return [ $this->buildUrl($query) ];
    }

    /**
     * Builds urls for hotels
     * @param string $query
     * @param array $params
     * @return array
     */
    private function buildUrlsFor_hotels($query, $params=[]){
        $urls = [];
        $hParams = [];

        if( empty( $params['country'] ) ){

            $dbCountries = \TSearch\tbl\Operator::table('countries')->loadData($this->operatorId);

            $params['country'] = [];
            foreach( $dbCountries as $dbCountry ){
                $params['country'][] = $dbCountry->element_id;
            }

        } elseif( !empty( $params['resort'] ) ) {

            // Курорты
            $hParams['resort'] = (array)$params['resort'];
        }

        // Категории отеля
        if( !empty( $params['category'] ) ){
            $hParams['category'] = $params['category'];
        }

        $countries = (array)$params['country'];

        foreach( $countries as $country ){
            // Страна
            $hParams['country'] = $country;

            $urls[] = $this->buildUrl($query, $hParams);
        }

        return $urls;
    }

    /**
     * Builds urls for hotel info
     * @param string $query
     * @param array $params
     * @return array
     */
    private function buildUrlsFor_hotel_info($query, $params){

        $urls = [];
        if( !empty( $params['id'] ) ){
            $urls[] = $this->buildUrl($query, ['id' => $params['id']]);
        }

        return $urls;
    }

    /**
     * Builds urls for tours
     * @param string $query
     * @param array $params
     * @return array
     */
    private function buildUrlsFor_tours($query, $params){
        $urls = [];

        if( !empty( $params['depCity'] ) && !empty( $params['country'] ) && !empty($params['availableDateFrom']) && !empty($params['availableDateTo']) ){

            // По умолчанию вытаскиваем максимальное количество туров для данного туроператора
            if( empty( $params['pageSize']) ){
                $params['pageSize'] = ICSTour::MAX_TOURS_NUMBER_PER_PAGE;
            }

            // Подсчитываем количество страниц поиска
            $pages = ceil( $params['pageSize']/ICSTour::MAX_TOURS_NUMBER_PER_PAGE );
            $pageSize = $params['pageSize'];

            // Если заданное количество туров превышает максимальное число - уменьшаем его до TOICSTour::MAX_TOURS_NUMBER
            if( $params['pageSize'] > ICSTour::MAX_TOURS_NUMBER_PER_PAGE ){
                $pageSize = ICSTour::MAX_TOURS_NUMBER_PER_PAGE;
            }

            // Количество взрослых
            $adults = !empty( $params['adults'] ) ? (int)$params['adults'] : 2;
            if( $adults > 4 ){
                $adults = 4;
            } elseif( $adults < 2 ){
                $adults = 2;
            }

            $tParams = [
                // Количество взрослых
                'adults' => $adults,

                // Диапизон поиска туров: дата начала
                'availableDateFrom' => $this->convertDateToICS($params['availableDateFrom']),

                // Диапазон поиска тура: дата окончания
                'availableDateTo' => $this->convertDateToICS($params['availableDateTo'])
            ];

            // Отели
            if( !empty( $params['hotel'] ) ){

                $tParams['hotel'] = (array)$params['hotel'];

            } else {

                // Курорты
                if( !empty( $params['resort'] ) ){
                    $tParams['resort'] = (array)$params['resort'];
                }

                // Категории отеля
                if( !empty( $params['category'] ) ){
                    $tParams['category'] = (array)$params['category'];
                }
            }

            // Типы питания
            if( !empty( $params['meal'] ) ){
                $tParams['meal'] = (array)$params['meal'];
            }

            // Количество детей
            if( !empty( $params['children'] ) ){
                $tParams['children'] = $params['children'];

                // Возрасты детей
                if( !empty( $params['chage'] ) ) {
                    $tParams['chage'] = (array)$params['chage'];
                }
            }

            // Максимальная цена тура
            if( !empty( $params['maxPrice'] ) && isset( $params['currency'] ) ) {
                $tParams['maxPrice'] = $params['maxPrice'];
                $tParams['currency'] = $params['currency'];
            }

            // Продолжительность тура
            if( !empty( $params['durationFrom'] ) && !empty( $params['durationTo'] ) ) {
                $tParams['durationFrom'] = $params['durationFrom'];
                $tParams['durationTo'] = $params['durationTo'];

            } elseif( !empty( $params['duration'] ) ){
                $tParams['duration'] = (array)$params['duration'];
            }

            // туры с отелями не в стопе
            if( !empty( $params['hotelNonstop'] ) ){
                $tParams['hotelNonstop'] = $params['hotelNonstop'];
            }

            // туры, у которых есть доступные билеты
            if( !empty( $params['flightNonstop'] ) ){
                $tParams['flightNonstop'] = $params['flightNonstop'];
            }

            $depCities = (array)$params['depCity'];
            $countries = (array)$params['country'];

            foreach( $depCities as $depCity ){

                // Город вылета
                $tParams['depCity'] = $depCity;

                foreach( $countries as $country ){

                    // Страна
                    $tParams['country'] = $country;


                    // Паджинация
                    for( $page=1; $page<=$pages; ++$page ){

                        $tPageSize = $pageSize;

                        if( $pageSize*$page > $params['pageSize'] ){
                            $tPageSize = $params['pageSize'] - $pageSize*($page - 1);
                        }

                        $tParams['pageSize'] = $tPageSize;
                        $tParams['page'] = $page;

                        $urls[] = $this->buildUrl($query, $tParams);
                    }

                }
            }
        }

        return $urls;
    }


    /**
     * Builds urls for tour info
     * @param string $query
     * @param array $params
     * @return array
     */
    private function buildUrlsFor_tour_info($query, $params){

        $urls = [];
        if( !empty( $params['id'] ) ){
            $urls[] = $this->buildUrl($query, ['id' => $params['id']]);
        }

        return $urls;
    }

    /**
     * Builds urls for hotel min prices
     * @param string $query
     * @param array $params
     * @return array
     */
    private function buildUrlsFor_hotel_min_prices($query, $params){

        $urls = [];

        if( !empty( $params['depCity'] ) && !empty( $params['duration'] ) && !empty( $params['date'] )  && !empty( $params['adults'] ) ){

            $tParams = [
                // Количество взрослых
                'adults' => $params['adults'],

                // Продолжительность тура
                'duration' => $params['duration'],

                // Дата вылета
                'date' => $this->convertDateToICS($params['date']),

                // Туры с отелями не в стопе
                'warranty' => !empty( $params['warranty'] ) ? $params['warranty'] : 0
            ];

            if( empty( $params['country'] ) ){

                $dbCountries = \TSearch\tbl\Operator::table('countries')->loadData($this->operatorId);

                $params['country'] = [];
                foreach( $dbCountries as $dbCountry ){
                    $params['country'][] = $dbCountry->element_id;
                }

            } elseif( !empty( $params['resort'] ) ) {

                // Курорты
                $tParams['resort'] = (array)$params['resort'];
            }

            // Количество детей
            if( !empty( $params['children'] ) ){
                $tParams['children'] = $params['children'];

                // Возрасты детей
                if( !empty( $params['chage'] ) ) {
                    $tParams['chage'] = (array)$params['chage'];
                }
            }

            $depCities = (array)$params['depCity'];
            $countries = (array)$params['country'];

            foreach( $depCities as $depCity ){

                // Город вылета
                $tParams['depCity'] = $depCity;

                foreach( $countries as $country ){

                    // Страна
                    $tParams['country'] = $country;

                    $urls[] = $this->buildUrl($query, $tParams);

                }
            }
        }

        return $urls;
    }



    /******************************************************************************************************
     *                                          Abstract Methods                                          *
     ******************************************************************************************************/

    /**
     * Returns class name
     * @return string
     */
    protected function getClassName(){
        return get_class();
    }


    /**
     * @param string $query
     * @param array $params
     * @return array
     */
    public function buildUrls($query, $params=[]){

        if( in_array($query, ['dep_cities', 'hotel_categories', 'meals', 'hotel_statuses', 'ticket_statuses']) ){
            return [ $this->buildUrl($query) ];
        }

        $buildUrl = 'buildUrlsFor_' . $query;
        return $this->$buildUrl($query, $params);
    }

    /**
     * Validates raw-content and returns his normalized data
     * @param string $content
     * @param array $info
     * @return SimpleXMLElement|bool
     */
    public function validateRawContent($content, $info){
        $xmlData = null;

        if( $content && isset($info['http_code']) && $info['http_code'] == 200 ){
            if( isset($info['content_type']) ){
                $type = explode('/', $info['content_type'])[1];
                if( $type == 'xml' ){
                    $xmlData = TLibXml::getData($content);
                };
            }
        }

        return $xmlData instanceof SimpleXMLElement ? $xmlData : null;
    }

    /***************************   Normalizing   ******************************/

    /**
     * Normalizes XML countries data
     * @param SimpleXMLElement $xmlData
     * @param array $params
     * @return array|null
     */
    protected function normalize_countries($xmlData, $params=[]){
        $countries = null;

        if( $xmlData->countries ){

            $countries = [];
            $depCityId = (int)$xmlData->countries['city'];

            $i = 0;
            foreach( $xmlData->countries->country as $country ){
                $countries[$i] = new stdClass();

                $countries[$i]->element_id = (string)$country['id'];
                $countries[$i]->name = (string)$country['name'];
                $countries[$i]->position = (string)$country['position'];
                $countries[$i]->depCityId = $depCityId;
                $i++;
            }
        }

        return $countries;
    }

    /**
     * Normalizes XML departure cities data
     * @param SimpleXMLElement $xmlData
     * @param array $params
     * @return array|null
     */
    protected function normalize_dep_cities($xmlData, $params=[]){
        $cities = null;

        if( $xmlData->cities ) {
            $cities = [];

            if ($xmlData->cities->city) {
                $i = 0;
                foreach ($xmlData->cities->city as $city) {
                    $cities[$i] = new stdClass();

                    $cities[$i]->element_id = (string)$city['id'];;
                    $cities[$i]->name = (string)$city['name'];
                    $i++;
                }
            }
        }

        return $cities;
    }


    /**
     * Normalizes XML tours data
     * @param SimpleXMLElement $xmlData
     * @param array $params
     * @return array|null
     */
    protected function normalize_tours($xmlData, $params=[]){
        $tours = null;

        if( $xmlData->tours ) {
            $tours = [];

            if ($xmlData->tours->tour) {

                $currency = (string)$xmlData->tours['currency'];

                foreach ($xmlData->tours->tour as $xmlTour) {

                    /*********************************** Additional Filter  ***********************************/

                    /*
                     * Если передали типы питания и текущий тур не содержит ни один из них,
                     * тогда не записываем этот тур в список
                     */
                    if (!empty($params['meal']) && !in_array($xmlTour->hotel[0]['meal'], (array)$params['meal'])) {
                        continue;
                    }


                    /******************************************************************************************/

                    if (count($xmlTour->hotel) == 1 && isset($xmlTour->hotel[0])) {

                        $hotel = new stdClass();

                        $hotel->oId = (string)$this->operatorId;
                        $hotel->hotelId = (string)$xmlTour->hotel[0]['id'];
                        $hotel->status = (string)$xmlTour->hotel[0]['status'];
                        $hotel->name = (string)$xmlTour->hotel[0]['name'];
                        //$hotel->resort = (string)$xmlTour->hotel[0]['resort'];
                        $hotel->categoryId = (string)$xmlTour->hotel[0]['cat'];
                        //$hotel->categoryName = (string)$xmlTour->hotel[0]['cat_name'];
                        $hotel->startResDate = $this->normalizeDate((string)$xmlTour->hotel[0]['date1'], true);
                        $hotel->endResDate = $this->normalizeDate((string)$xmlTour->hotel[0]['date2'], true);
                        $hotel->room = (string)$xmlTour->hotel[0]['room'];
                        //$hotel->residenceType = (string)$xmlTour->hotel[0]['type'];
                        $hotel->mealId = (string)$xmlTour->hotel[0]['meal'];
                        $hotel->mealName = (string)$xmlTour->hotel[0]['meal_name'];
                        $hotel->depCity = $params['depCity'];
                        //$hotel->duration = (string)$xmlTour->hotel[0]['duration'];

                        $tour = new stdClass();
                        $tour->tourId = (string)$xmlTour['id'];
                        $tour->eTicketId = (string)$xmlTour['ticket_status_econom'];
                        $tour->bTicketId = (string)$xmlTour['ticket_status_business'];
                        $tour->departureDate = $this->normalizeDate((string)$xmlTour['date'], true);
                        $tour->nights = (string)$xmlTour['nights'];
                        $tour->programId = (string)$xmlTour['program'];
                        $tour->chAge = (string)$xmlTour['ch_age'];
                        $tour->canBook = (string)$xmlTour['can_book'];
                        $tour->adults = (string)$xmlTour['ad'];
                        $tour->children = (string)$xmlTour['ch'];

                        if (empty($params['currency'])) {
                            $tour->price = (string)$xmlTour['price_rur'];
                            $tour->currency = $this->currency_rur;
                        } else {
                            $tour->price = (string)$xmlTour['price'];
                            $tour->currency = $currency;
                        }


                        $tour->hotel = $hotel;

                        $tours[] = $tour;

                    }
                }
            }
        }

        return $tours;
    }


    /**
     * Normalizes XML tour info
     * @param SimpleXMLElement $xmlData
     * @param array $params
     * @return array|null
     */
    protected function normalize_tour_info($xmlData, $params=[]){
        $tour = null;

        if( $xmlData->tour ) {
            $tour = [];

            if ($xmlData->tour->content->part) {

                $tour[0]['city'] = (string)$xmlData->tour['city'];
                $tour[0]['currency'] = (string)$xmlData->tour['currency'];
                $tour[0]['startDate'] = $this->normalizeDate((string)$xmlData->tour['date'], true);
                $tour[0]['adults'] = (int)$xmlData->tour->accomodation->adults['n'];
                $tour[0]['kids'] = 0;

                if ($xmlData->tour->accomodation->kids) {
                    $tour[0]['kids'] = (int)$xmlData->tour->accomodation->kids['n'];
                    $tour[0]['kidsAge'] = (string)$xmlData->tour->accomodation->kids['age'];
                }

                $tour[0]['residence'] = TourHelper::getResidence($tour[0]['adults'], $tour[0]['kids'], true);

                // Инфо о ценах
                if ($xmlData->tour->price_info->price) {
                    $tour[0]['prices'] = [];
                    foreach ($xmlData->tour->price_info->price as $price) {
                        $tour[0]['prices'][(string)$price['currency']] = (string)$price;
                    }
                }

                // Инфо о проживании
                foreach ($xmlData->tour->content->part as $part) {
                    if ($part->info) {

                        $interval = date_diff( date_create((string)$xmlData->tour['date']), date_create((string)$part['date2']) );

                        $tour[0]['hotelId'] = (string)$part->info->hotel['id'];
                        $tour[0]['endDate'] = $this->normalizeDate((string)$part['date2'], true);
                        $tour[0]['nights'] = $interval->days;
                        $tour[0]['mealId'] = (string)$part->info->meal['id'];
                        $tour[0]['room'] = (string)$part->info->hotel['room'];

                        break;
                    }
                }

            }
        }

        return $tour;
    }

    /**
     * Normalizes XML resorts
     * @param SimpleXMLElement $xmlData
     * @param array $params
     * @return array|null
     */
    protected function normalize_resorts($xmlData, $params=[]){
        $resorts = null;

        if( $xmlData->resorts) {
            $resorts = [];
            if ($xmlData->resorts->resort) {

                $i = 0;
                foreach ($xmlData->resorts->resort as $resort) {
                    $resorts[$i] = new stdClass();

                    $resorts[$i]->element_id = (string)$resort['id'];;
                    $resorts[$i]->name = (string)$resort['name'];
                    $resorts[$i]->country = (string)$resort['country'];
                    $i++;
                }

            }
        }

        return $resorts;
    }

    /**
     * Normalizes XML hotels
     * @param SimpleXMLElement $xmlData
     * @param array $params
     * @return array|null
     */
    protected function normalize_hotels($xmlData, $params=[]){
        $hotels = null;

        if( $xmlData->hotels ) {
            $hotels = [];

            if ($xmlData->hotels->hotel) {

                $i = 0;
                foreach ($xmlData->hotels->hotel as $hotel) {
                    $hotels[$i] = new stdClass();

                    $hotels[$i]->element_id = (string)$hotel['id'];
                    $hotels[$i]->name = (string)$hotel['name'];
                    $hotels[$i]->country = $params['country'];
                    $hotels[$i]->resort = (string)$hotel['resort'];
                    $hotels[$i]->category_name = (string)$hotel['category_name'];
                    $hotels[$i]->category = (string)$hotel['category'];
                    $i++;
                }
            }
        }

        return $hotels;
    }

    /**
     * Normalizes XML hotel info
     * @param SimpleXMLElement $xmlData
     * @param array $params
     * @return array|null
     */
    protected function normalize_hotel_info($xmlData, $params=[]){
        $hotel = null;

        if( $xmlData->hotel ){
            $hotel = [
                0 => [
                    'name' => (string)$xmlData->hotel['name'],
                    'resort' => (string)$xmlData->hotel['resort'],
                    'category_name' => (string)$xmlData->hotel['category_name'],
                    'category' => (string)$xmlData->hotel['category'],
                    'description' => (string)$xmlData->hotel->description,
                    'images' => []
                ]
            ];

            if( $xmlData->hotel->main_image ){
                $hotel[0]['images'][] = (string)$xmlData->hotel->main_image['image_url'];
            }

            if( $xmlData->hotel->image ){
                foreach ($xmlData->hotel->image as $image) {
                    $hotel[0]['images'][] = (string)$image['preview_image_url'];
                }
            }

        }

        return $hotel;
    }

    /**
     * Normalizes XML hotel categories
     * @param SimpleXMLElement $xmlData
     * @param array $params
     * @return array|null
     */
    protected function normalize_hotel_categories($xmlData, $params=[]){
        $categories = null;

        if( $xmlData->categories) {
            $categories = [];
            if ($xmlData->categories->category) {

                $i = 0;
                foreach ($xmlData->categories->category as $category) {
                    $categories[$i] = new stdClass();

                    $categories[$i]->element_id = (string)$category['id'];;
                    $categories[$i]->name = (string)$category['name'];
                    $i++;
                }
            }
        }

        return $categories;
    }

    /**
     * Normalizes XML meal
     * @param SimpleXMLElement $xmlData
     * @param array $params
     * @return array|null
     */
    protected function normalize_meals($xmlData, $params=[]){
        $meals = null;

        if( $xmlData->meals) {
            $meals = [];

            if ($xmlData->meals->meal) {

                $i = 0;
                foreach ($xmlData->meals->meal as $meal) {
                    $meals[$i] = new stdClass();

                    $meals[$i]->element_id = (string)$meal['id'];;
                    $meals[$i]->name = (string)$meal['name'];
                    $i++;
                }
            }
        }

        return $meals;
    }

    /**
     * Normalizes XML hotel statuses
     * @param SimpleXMLElement $xmlData
     * @param array $params
     * @return array|null
     */
    protected function normalize_hotel_statuses($xmlData, $params=[]){
        $statuses = null;

        if( $xmlData->hotel_statuses ) {
            $statuses = [];

            if ($xmlData->hotel_statuses->hotel_status) {

                $i = 0;
                foreach ($xmlData->hotel_statuses->hotel_status as $status) {
                    $statuses[$i] = new stdClass();

                    $statuses[$i]->element_id = (string)$status['id'];
                    $statuses[$i]->name = (string)$status['name'];
                    $i++;
                }
            }
        }

        return $statuses;
    }

    /**
     * Normalizes XML ticket statuses
     * @param SimpleXMLElement $xmlData
     * @param array $params
     * @return array|null
     */
    protected function normalize_ticket_statuses($xmlData, $params=[]){
        $statuses = null;

        if( $xmlData->ticket_statuses ) {
            $statuses = [];

            if ($xmlData->ticket_statuses->ticket_status) {

                $i = 0;
                foreach ($xmlData->ticket_statuses->ticket_status as $status) {
                    $statuses[$i] = new stdClass();

                    $statuses[$i]->element_id = (string)$status['id'];;
                    $statuses[$i]->name = (string)$status['name'];

                    $persons = (string)$status['persons'];
                    if ($persons) {
                        $statuses[$i]->persons = $persons;
                    }

                    $i++;
                }
            }
        }

        return $statuses;
    }


    /**
     * Normalizes XML hotel min prices
     * @param SimpleXMLElement $xmlData
     * @param array $params
     * @return array|null
     */
    protected function normalize_hotel_min_prices($xmlData, $params=[]){
        $hotels = null;

        if( $xmlData->hotels ) {
            $hotels = [];
            $currency = (string)$xmlData->hotels['currency'];

            if ($xmlData->hotels->hotel) {

                $i = 0;
                foreach ($xmlData->hotels->hotel as $hotel) {
                    if( $hotel->tour ) {

                        $last_index = $hotel->count()-1;
                        $last_tour = $hotel->tour[$last_index];
                        $hotel_part = $last_tour->hotel_part;

                        $hotels[$i] = new stdClass();
                        $hotels[$i]->hotel_id = (string)$hotel['id'];

                        $hotels[$i]->nights = (string)$last_tour['nights'];
                        $hotels[$i]->price_rur = (string)$last_tour['price_rur'];
                        $hotels[$i]->price = (string)$last_tour['price'];
                        $hotels[$i]->currency = $currency;
                        $hotels[$i]->adults = (string)$last_tour['ad'];
                        $hotels[$i]->kids = (string)$last_tour['ch'];
                        $hotels[$i]->tour_id = (string)$last_tour['id'];

                        $hotels[$i]->meal_id = (string)$hotel_part['meal'];
                        $hotels[$i]->room = (string)$hotel_part['room'];
                        $hotels[$i]->start_date = $this->normalizeDate((string)$hotel_part['date1'], true);
                        $hotels[$i]->end_date = $this->normalizeDate((string)$hotel_part['date2'], true);

                        $hotels[$i]->depCity = $params['depCity'];

                        $i++;
                    }
                }
            }
        }

        return $hotels;
    }


}