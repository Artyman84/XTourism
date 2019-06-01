<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 07.03.2015
 * Time: 18:04
 */

set_time_limit(0);

class TMyScripts {

    private static $countries = array(
        'AD' => '[Андорра]',
        'AE' => '[ОАЭ]',
        'AF' => '[Афганистан]',
        'AG' => '[Антигуа и Барбуда]',
        'AI' => '[Ангилья]',
        'AL' => '[Албания]',
        'AM' => '[Армения]',
        //    'AN' => '[Антильские Острова (Нидерландские)]',
        'AO' => '[Ангола]',
        //    'AQ' => 'AQ[Антарктика]',
        'AR' => '[Аргентина]',
        'AS' => '[Американское Самоа]',
        'AT' => '[Австрия]',
        'AU' => '[Австралия]',
        'AW' => '[Аруба]',
        'AZ' => '[Азербайджан]',
        'BA' => '[Босния и Герцеговина]',
        'BB' => '[Барбадос]',
        'BD' => '[Бангладеш]',
        'BE' => '[Бельгия]',
        'BF' => '[Буркина-Фасо]',
        'BG' => '[Болгария]',
        'BH' => '[Бахрейн]',
        'BI' => '[Бурунди]',
        'BJ' => '[Бенин]',

        'BM' => '[Бермудские Острова]',
        'BN' => '[Бруней]',
        'BO' => '[Боливия]',
        'BR' => '[Бразилия]',
        'BS' => '[Багамские Острова]',
        'BT' => '[Бутан]',
        //    'BV' => 'BV[Буве, остров]',
        'BW' => '[Ботсвана]',
        'BY' => '[Беларусь]',
        'BZ' => '[Белиз]',
        'CA' => '[Канада]',
        'CC' => '[Кокосовые острова]',
        'CF' => '[ЦАР]',
        'CG' => '[Конго]',
        'CH' => '[Швейцария]',
        'CI' => '[Кот-д\'Ивуар]',
        'CK' => '[Кука, Острова]',
        'CL' => '[Чили]',
        'CM' => '[Камерун]',
        'CN' => '[Китай]',
        'CO' => '[Колумбия]',
        'CR' => '[Коста-Рика]',
        'CU' => '[Куба]',

        'CV' => '[Кабо-Верде]',
        'CX' => '[Рождества(Кристмас), Остров]',
        'CY' => '[Кипр]',
        'CZ' => '[Чешская Республика]',
        'DE' => '[Германия]',
        'DJ' => '[Джибути]',
        'DK' => '[Дания]',
        'DM' => '[Доминика]',
        'DO' => '[Доминиканская Республика]',
        'DZ' => '[Алжир]',
        'EC' => '[Эквадор]',
        'EE' => '[Эстония]',
        'EG' => '[Египет]',
        'EH' => '[Западная Сахара]',
        'ER' => '[Эритрея]',
        'ES' => '[Испания]',
        'ET' => '[Эфиопия]',
        'FI' => '[Финляндия]',
        'FJ' => '[Фиджи]',
        'FK' => '[Фолклендские (Мальвинские) Острова]',
        'FM' => '[Микронезия (Федеративные Штаты Микронезии)]',
        'FO' => '[Фарерские острова]',

        'FR' => '[Франция]',
        //    'FX' => '[Франция, Метрополия]',
        'GA' => '[Габон]',
        'GB' => '[Великобритания]',
        'GD' => '[Гренада]',
        'GE' => '[Грузия]',
        'GF' => '[Гвиана Французская]',
        'GH' => '[Гана]',
        'GI' => '[Гибралтар]',
        'GL' => '[Гренландия]',
        'GM' => '[Гамбия]',
        'GN' => '[Гвинея]',
        'GP' => '[Гваделупа]',
        'GQ' => '[Экваториальная Гвинея]',
        'GR' => '[Греция]',
        'GS' => '[Южная Георгия и Южные Сандвичевы острова]',
        'GT' => '[Гватемала]',
        'GU' => '[Гуам]',
        'GW' => '[Гвинея-Бисау]',
        'GY' => '[Гайана]',
        'HK' => '[Сянган (Гонконг)]',
        'HM' => '[Херд и Макдональд, острова]',
        'HN' => '[Гондурас]',
        'HR' => '[Хорватия]',
        'HT' => '[Гаити]',
        'HU' => '[Венгрия]',
        'ID' => '[Индонезия]',
        'IE' => '[Ирландия]',
        'IL' => '[Израиль]',
        'IN' => '[Индия]',
        'IO' => '[Британская территория в Индийском океане]',
        'IQ' => '[Ирак]',
        'IR' => '[Иран]',
        'IS' => '[Исландия]',
        'IT' => '[Италия]',

        'JM' => '[Ямайка]',
        'JO' => '[Иордания]',
        'JP' => '[Япония]',
        'KE' => '[Кения]',
        'KG' => '[Киргизия]',
        'KH' => '[Камбоджа]',
        'KI' => '[Кирибати]',
        'KM' => '[Коморские Острова]',
        'KN' => '[Сент-Китс и Невис]',
        'KP' => '[Корейская Народно-Демократическая Республика]',
        'KR' => '[Корея, Республика]',
        'KW' => '[Кувейт]',
        'KY' => '[Кайман, Острова]',
        'KZ' => '[Казахстан]',
        'LA' => '[Лаос]',
        'LB' => '[Ливан]',
        'LC' => '[Сент-Люсия]',
        'LI' => '[Лихтенштейн]',
        'LK' => '[Шри-Ланка]',
        'LR' => '[Либерия]',
        'LS' => '[Лесото]',
        'LT' => '[Литва]',
        'LU' => '[Люксембург]',
        'LV' => '[Латвия]',
        'LY' => '[Ливия]',
        'MA' => '[Марокко]',
        'MC' => '[Монако]',
        'MD' => '[Молдова]',
        'MG' => '[Мадагаскар]',
        'ME' => '[Черногория]',
        'MH' => '[Маршалловы Острова]',
        'MK' => '[Македония]',
        'ML' => '[Мали]',
        'MM' => '[Мьянма]',
        'MN' => '[Монголия]',
        'MO' => '[Аомынь (Макао)]',
        'MP' => '[Северные Марианские Острова]',
        'MQ' => '[Мартиника]',
        'MR' => '[Мавритания]',
        'MS' => '[Монтсеррат]',
        'MT' => '[Мальта]',
        'MU' => '[Маврикий]',
        'MV' => '[Мальдивы]',
        'MW' => '[Малави]',

        'MX' => '[Мексика]',
        'MY' => '[Малайзия]',
        'MZ' => '[Мозамбик]',
        'NA' => '[Намибия]',
        'NC' => '[Новая Каледония]',
        'NE' => '[Нигер]',
        'NF' => '[Норфолк]',
        'NG' => '[Нигерия]',
        'NI' => '[Никарагуа]',
        'NL' => '[Нидерланды]',
        'NO' => '[Норвегия]',
        'NP' => '[Непал]',
        'NR' => '[Науру]',
        'NU' => '[Ниуэ]',
        'NZ' => '[Новая Зеландия]',
        'OM' => '[Оман]',
        'PA' => '[Панама]',
        'PE' => '[Перу]',
        'PF' => '[Французская Полинезия]',
        'PG' => '[Папуа — Новая Гвинея]',
        'PH' => '[Филиппины]',
        'PK' => '[Пакистан]',
        'PL' => '[Польша]',
        'PM' => '[Сен-Пьер и Микелон]',
        'PN' => '[Питкэрн]',
        'PR' => '[Пуэрто-Рико]',
        'PT' => '[Португалия]',
        'PW' => '[Палау]',
        'PY' => '[Парагвай]',
        'QA' => '[Катар]',
        'RE' => '[Реюньон]',

        'RO' => '[Румыния]',
        'RS' => '[Сербия]',
        'RU' => '[Россия]',
        'RW' => '[Руанда]',
        'SA' => '[Саудовская Аравия]',
        'SB' => '[Соломоновы Острова]',
        'SC' => '[Сейшельские Острова]',
        'SD' => '[Судан]',
        'SE' => '[Швеция]',
        'SG' => '[Сингапур]',
        'SH' => '[Святой Елены, Остров]',
        'SI' => '[Словения]',
        'SJ' => '[Свальбард и Ян-Майен]',
        'SK' => '[Словакия]',
        'SL' => '[Сьерра-Леоне]',
        'SM' => '[Сан-Марино]',
        'SN' => '[Сенегал]',
        'SO' => '[Сомали]',
        'SR' => '[Суринам]',
        'ST' => '[Сан-Томе и Принсипи]',
        'SV' => '[Сальвадор]',
        'SY' => '[Сирия]',
        'SZ' => '[Свазиленд]',
        'TC' => '[Теркс и Кайкос]',
        'TD' => '[Чад]',
        'TF' => '[Французские Южные территории]',
        'TG' => '[Того]',
        'TH' => '[Таиланд]',
        'TJ' => '[Таджикистан]',
        'TK' => '[Токелау]',
        'TM' => '[Туркменистан]',
        'TN' => '[Тунис]',
        'TO' => '[Тонга]',
        'TP' => '[Восточный Тимор]',
        'TR' => '[Турция]',
        'TT' => '[Тринидад и Тобаго]',
        'TV' => '[Тувалу]',
        'TW' => '[Тайвань]',
        'TZ' => '[Танзания]',
        'UA' => '[Украина]',
        'UG' => '[Уганда]',
        'UM' => '[Мелкие отдаленные острова США]',
        'US' => '[США]',
        'UY' => '[Уругвай]',
        'UZ' => '[Узбекистан]',
        'VA' => '[Ватикан]',
        'VC' => '[Сент-Винсент и Гренадины]',
        'VE' => '[Венесуэла]',
        'VG' => '[Британские Виргинские острова]',
        'VI' => '[Американские Виргинские острова]',
        'VN' => '[Вьетнам]',
        'VU' => '[Вануату]',
        'WF' => '[Уоллис и Футуна]',
        'WS' => '[Самоа]',
        'YE' => '[Йемен]',
        'YT' => '[Майотта]',
        'YU' => '[Югославия]',
        'ZA' => '[ЮАР]',
        'ZM' => '[Замбия]',
        'ZR' => '[Заир]',
        'ZW' => '[Зимбабве]',
        'XA' => '[Абхазия]',
        'XC' => '[Крым]',
    );

    /**************************   COLLECTS HOTELS FROM *.JSON FILES   **************************/

    private static function insertResorts($hData, $regions){
        // Получаем свежие страны
        $dbCom = Yii::app()->db->createCommand();
        $countries = $dbCom->select()->from('{{directory_countries}}')->Order('position ASC')->setFetchMode(PDO::FETCH_OBJ)->queryAll();


        // Получаем свежие регионы
        $dbCom = Yii::app()->db->createCommand();
        $_regions = $dbCom->select()->from('{{directory_regions}}')->setFetchMode(PDO::FETCH_OBJ)->queryAll();
        $r = array();
        foreach($_regions as $_r){
            $r[$_r->dir_country_id][$_r->name] = $_r->id;
        }

        // Получаем существующие курорты
        $oResorts = Yii::app()->db->createCommand()
            ->select('c.name AS country_name, r.name AS resort_name, r.id')
            ->from('{{directory_countries}} AS c')
            ->join('{{directory_resorts}} AS r', 'r.dir_country_id = c.id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $oldResorts = array();
        foreach( $oResorts as $res ){
            $oldResorts[$res->country_name][$res->resort_name] = $res->id;
        }

        $db = Yii::app()->db;
        // Вставляем курорты
        $ins = array();
        foreach( $countries as $country ){
            if( !isset($hData[$country->name]) ) {
                continue;
            }
            foreach( $hData[$country->name] as $resort => $hotels ){

                if( !isset($oldResorts[$country->name][$resort]) ){

                    if( isset($regions[$country->name]) ){

                        $resorts_to_regions = [];
                        $cities_to_regions = [];
                        $resort_id = $city_id = 0;

                        foreach( $regions[$country->name] as $region => $_resorts ){

                            if( isset($_resorts[$resort]) ){
                                $regionId = (int)$r[$country->id][$region];

                                $data = [
                                    'name' => $resort,
                                    'dir_country_id' => $country->id,
                                ];

                                if( !$resort_id ) {
                                    $db->createCommand()->insert('{{directory_resorts}}', $data);
                                    $resort_id = $db->getLastInsertID();
                                }

                                $resorts_to_regions[] = ['dir_resort_id' => $resort_id, 'dir_region_id' => $regionId];

                                if( !$city_id ) {
                                    $db->createCommand()->insert('{{directory_cities}}', $data);
                                    $city_id = $db->getLastInsertID();
                                }

                                $cities_to_regions[] = ['dir_city_id' => $city_id, 'dir_region_id' => $regionId];
                            }
                        }

                        if( empty($cities_to_regions) ){

                            $ins[] = array(
                                'name' => $resort,
                                'dir_country_id' => $country->id,
                            );

                        } else {

                            TUtil::multipleInsertData('directory_resorts_to_regions', $resorts_to_regions, 300);
                            TUtil::multipleInsertData('directory_cities_to_regions', $cities_to_regions, 300);
                        }

                    } else {

                        $ins[] = array(
                            'name' => $resort,
                            'dir_country_id' => $country->id,
                        );
                    }
                } else {

                    if( isset($regions[$country->name]) ){

                        $resorts_to_regions = [];
                        $cities_to_regions = [];

                        foreach( $regions[$country->name] as $region => $_resorts ){

                            if( isset($_resorts[$resort]) ){
                                $regionId = (int)$r[$country->id][$region];

                                $resorts_to_regions[] = '(' . (int)$oldResorts[$country->name][$resort] . ', ' . (int)$regionId . ')';
                                $cities_to_regions[] = '(' . (int)$oldResorts[$country->name][$resort] . ', ' . (int)$regionId . ')';
                            }
                        }

                        if( !empty($resorts_to_regions) ){
                            $resorts_to_regions = implode(',', $resorts_to_regions);
                            $cities_to_regions = implode(',', $cities_to_regions);

                            $db->createCommand("REPLACE INTO {{directory_resorts_to_regions}} (`dir_resort_id`, `dir_region_id`) VALUES $resorts_to_regions")->execute();
                            $db->createCommand("REPLACE INTO {{directory_cities_to_regions}} (`dir_city_id`, `dir_region_id`) VALUES $cities_to_regions")->execute();

                        }
                    }

                }
            }
        }

        if( !empty($ins) ) {
            TUtil::multipleInsertData('directory_resorts', $ins, 300);
            TUtil::multipleInsertData('directory_cities', $ins, 300);
        }
    }

    private static function insertRegions($regions){
        // Получаем свежие страны
        $dbCom = Yii::app()->db->createCommand();
        $countries = $dbCom->select()->from('{{directory_countries}}')->Order('position ASC')->setFetchMode(PDO::FETCH_OBJ)->queryAll();

        // Получаем существующие регионы
        $_regions = Yii::app()->db->createCommand()
            ->select('c.name AS country_name, r.name AS region_name, r.id')
            ->from('{{directory_countries}} AS c')
            ->join('{{directory_regions}} AS r', 'r.dir_country_id = c.id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $oldRegions = array();
        foreach($_regions as $_region){
            $oldRegions[$_region->country_name][$_region->region_name] = $_region->id;
        }

        // Вставляем регионы
        $ins = array();
        foreach( $countries as $country ){
            if( isset($regions[$country->name]) ){
                foreach( $regions[$country->name] as $region => $cities ){
                    if( !isset($oldRegions[$country->name][$region]) ){

                        $region_type = current($cities);

                        $ins[] = array(
                            'name' => $region,
                            'type' => $region_type,
                            'dir_country_id' => $country->id
                        );
                    }
                }
            }
        }
        TUtil::multipleInsertData('directory_regions', $ins, 300);
    }

    private static function insertCountries($hData){
        $cCountries = array_flip(Yii::app()->db->createCommand()->select('name')->from('{{directory_countries}}')->queryColumn());

        $ins = array();
        foreach ($hData as $country => $resorts) {

            if (!isset($cCountries[$country])) {
                $url = current(current($resorts))['url'];
                $code = strtoupper(explode('/', str_replace('http://www.booking.com/hotel/', '', $url))[0]);

                $ins[] = array( 'name' => $country, 'code' => $code );
            }

        }

        TUtil::multipleInsertData('directory_countries', $ins, 300);
    }

    private static function insertHotelCategories($hCategories){
        $cCategories = array_flip(Yii::app()->db->createCommand()->select('name')->from('{{directory_hotel_categories}}')->queryColumn());
//        $hCategories = array_diff_key($hCategories, $cCategories);

        $hCategories = array_diff_ukey($hCategories, $cCategories, function ($key1, $key2) {
            if (mb_strtolower($key1, 'utf8') == mb_strtolower($key2, 'utf8'))
                return 0;
            else if ($key1 > $key2)
                return 1;
            else
                return -1;
        });

        $ins = array();
        foreach( $hCategories as $hCategory => $v ){
            $ins[] = array( 'name' => $hCategory );
        }

        TUtil::multipleInsertData('directory_hotel_categories', $ins, 300);
    }

    private static function insertHotelBadges($badges){
        foreach ($badges as $table => $data) {

            $cBadges = array_flip(Yii::app()->db->createCommand()->select('name')->from('{{' . $table . '}}')->queryColumn());
//            $newBadges = array_diff_key($data, $cBadges);
            $newBadges = array_diff_ukey($data, $cBadges, function ($key1, $key2) {
                if (mb_strtolower($key1, 'utf8') == mb_strtolower($key2, 'utf8'))
                    return 0;
                else if ($key1 > $key2)
                    return 1;
                else
                    return -1;
            });


            $ins = array();
            foreach ($newBadges as $newBadge => $v) {
                $ins[] = array('name' => $newBadge, 'description' => $newBadge);
            }

            TUtil::multipleInsertData($table, $ins, 300);
        }
    }

    private static function insertHotelPhotos($photos, $hData){
        $ins = array();
        foreach( $photos as $countryName => $resorts ){
            foreach( $resorts as $resortName => $hotels ){
                foreach( $hotels as $hotelUrl => $photoUrls ){

                    if( isset( $hData[$countryName][$resortName][$hotelUrl] ) ){
                        $ins[] = array(
                            'dir_hotel_id' => $hData[$countryName][$resortName][$hotelUrl],
                            'photos' => json_encode($photoUrls),
                            'count' => count($photoUrls)
                        );
                    }
                }
            }
        }
        TUtil::multipleInsertData('hotel_photos', $ins, 100);
    }

    private static function insertHotelRatings($ratings, $hData){
        $ins = array();
        foreach( $ratings as $countryName => $resorts ){
            foreach( $resorts as $resortName => $_ratings ){
                foreach( $_ratings as $hotelUrl => $rating ){

                    if( isset( $hData[$countryName][$resortName][$hotelUrl] ) && !empty($rating) ){
                        $ins[] = array(
                            'dir_hotel_id' => (int)$hData[$countryName][$resortName][$hotelUrl],
                            'rating' => (float)$rating['rating'],
                            'voices' => TUtil::getStrDigits($rating['voices']),
                            'scores' => json_encode($rating['scores']),
                        );
                    }
                }
            }
        }

        TUtil::multipleInsertData('hotel_ratings', $ins, 300);
    }

    private static function insertHotelResidence($residence, $hData){
        $ins = array();
        foreach( $residence as $countryName => $resorts ){
            foreach( $resorts as $resortName => $_residence ){
                foreach( $_residence as $hotelUrl => $res ){

                    if( isset( $hData[$countryName][$resortName][$hotelUrl] ) ){
                        foreach( $res as $r ){
                            $ins[] = array(
                                'dir_hotel_id' => $hData[$countryName][$resortName][$hotelUrl],
                                'name' => $r['name'],
                                'value' => $r['value']
                            );
                        }
                    }
                }
            }
        }

        TUtil::multipleInsertData('hotel_residence', $ins, 300);
    }

    private static function insertHotelServices($services, $hData){
        $ins = array();
        foreach( $services as $countryName => $resorts ){
            foreach( $resorts as $resortName => $_services ){
                foreach( $_services as $hotelUrl => $serv ){

                    if( isset( $hData[$countryName][$resortName][$hotelUrl] ) ){
                        foreach( $serv as $s ){
                            $ins[] = array(
                                'dir_hotel_id' => $hData[$countryName][$resortName][$hotelUrl],
                                'name' => $s['name'],
                                'value' => $s['value']
                            );
                        }
                    }
                }
            }
        }

        TUtil::multipleInsertData('hotel_services', $ins, 300);
    }

    private static function insertCards($hCards){
        $cCards = array_flip(Yii::app()->db->createCommand()->select('name')->from('{{currency_cards}}')->queryColumn());
//        $hCards = array_diff_key($hCards, $cCards);

        $hCards = array_diff_ukey($hCards, $cCards, function ($key1, $key2) {
            if (mb_strtolower($key1, 'utf8') == mb_strtolower($key2, 'utf8'))
                return 0;
            else if ($key1 > $key2)
                return 1;
            else
                return -1;
        });


        $ins = array();
        foreach( $hCards as $hCard => $v ){
            $ins[] = array( 'name' => $hCard );
        }

        TUtil::multipleInsertData('currency_cards', $ins, 300);
    }

    private static function insertHotelCards($cards, $hData){
        // Получаем свежие кредитные карты
        $dbCom = Yii::app()->db->createCommand();
        $_hCards = $dbCom->select('id, name')->from('{{currency_cards}}')->setFetchMode(PDO::FETCH_OBJ)->queryAll();
        $hCards = array();
        foreach( $_hCards as $hc ){
            $hCards[$hc->name] = $hc->id;
        }

        $ins = array();
        foreach( $cards as $countryName => $resorts ){
            foreach( $resorts as $resortName => $_cards ){
                foreach( $_cards as $hotelUrl => $card ){

                    if( isset( $hData[$countryName][$resortName][$hotelUrl] ) ){
                        foreach( $card as $name ){
                            if( isset($hCards[$name]) ){
                                $ins[] = array(
                                    'dir_hotel_id' => $hData[$countryName][$resortName][$hotelUrl],
                                    'card_id' => $hCards[$name],
                                );
                            }
                        }
                    }
                }
            }
        }

        TUtil::multipleInsertData('hotel_cards', $ins, 300);
    }

    public static function clearBkHotelTables(){

        Yii::app()->db->createCommand()->truncateTable('{{directory_countries}}');
        Yii::app()->db->createCommand()->truncateTable('{{directory_regions}}');
        Yii::app()->db->createCommand()->truncateTable('{{hotel_photos}}');
        Yii::app()->db->createCommand()->truncateTable('{{hotel_ratings}}');
        Yii::app()->db->createCommand()->truncateTable('{{hotel_residence}}');
        Yii::app()->db->createCommand()->truncateTable('{{hotel_services}}');
        Yii::app()->db->createCommand()->truncateTable('{{hotel_cards}}');
        Yii::app()->db->createCommand()->truncateTable('{{directory_resorts}}');
        Yii::app()->db->createCommand()->truncateTable('{{directory_cities}}');
        Yii::app()->db->createCommand()->truncateTable('{{directory_cities_to_regions}}');
        Yii::app()->db->createCommand()->truncateTable('{{directory_resorts_to_regions}}');
        Yii::app()->db->createCommand()->truncateTable('{{directory_hotels}}');
        Yii::app()->db->createCommand()->truncateTable('{{directory_hotel_categories}}');
        Yii::app()->db->createCommand()->truncateTable('{{currency_cards}}');
        Yii::app()->db->createCommand()->truncateTable('{{hotel_badges}}');
        Yii::app()->db->createCommand()->truncateTable('{{hotel_facility_badges}}');
    }






    /**
     * Вставляем страны, курорты, регионы и отели
     * @param array $hData
     * @param array $regions
     * @param array $hCategories
     * @param array $hCards
     * @param array $badges
     */
    private static function insertHotelData($hData, $regions, $hCategories, $hCards, $badges){
        // Убираем из списка отелей (который нужно вставить в базу)
        // уже существующие отели
        self::filterHotelsData($hData);

//        echo '1) Фильтрация<br/>';

        // Вставляем категории отелей
        self::insertHotelCategories($hCategories);

        // Вставляем значки отелей
        self::insertHotelBadges($badges);

//        echo '2) категории отелей<br/>';
        // Вставляем валютные карты
        self::insertCards($hCards);
//        echo '3) валютные карты<br/>';
        // Вставляем страны
        self::insertCountries($hData);
//        echo '4) страны<br/>';
        // Вставляем регионы
        self::insertRegions($regions);
//        echo '5) регионы<br/>';
        // Вставляем курорты
        self::insertResorts($hData, $regions);
//        echo '6) курорты<br/>';

        // Получаем свежие категории отелей
        $hCategories = TUtil::listKey(TSearch\tbl\Directory::loadData('hotel_categories', null, false), 'name');

        // Получаем свежие значки отелей
        $hotel_badges = TUtil::listKey(Yii::app()->db->createCommand()->select('id, name')->from('{{hotel_badges}}')->setFetchMode(PDO::FETCH_OBJ)->queryAll(), 'name');

        // Получаем свежие значки-предложения отелей
        $hotel_facility_badges = TUtil::listKey(Yii::app()->db->createCommand()->select('id, name')->from('{{hotel_facility_badges}}')->setFetchMode(PDO::FETCH_OBJ)->queryAll(), 'name');

        // Получаем свежие страны
        $hCountries = TUtil::listKey(TSearch\tbl\Directory::loadData('countries', null, false), 'name');

        // Получаем свежие страны и курорты
        $dbCom = Yii::app()->db->createCommand();
        $countries = $dbCom->select('c.name AS country_name, r.name AS resort_name, r.id AS resort_id')
            ->from('{{directory_countries}} c')
            ->join('{{directory_resorts}} r', 'r.dir_country_id = c.id')
            ->Order('c.position, r.name ASC')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        // Группируем свежие страны и курорты
        $dResortData = array();
        foreach( $countries as $country ) {
            $dResortData[$country->country_name][$country->resort_name] = $country->resort_id;
        }

        // Получаем свежие страны и города(должны быть равны курортам)
        $dbCom = Yii::app()->db->createCommand();
        $countries = $dbCom->select('c.name AS country_name, ct.name AS city_name, ct.id AS city_id')
            ->from('{{directory_countries}} c')
            ->join('{{directory_cities}} ct', 'ct.dir_country_id = c.id')
            ->Order('c.position, ct.id ASC')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        // Группируем свежие страны и города(должны быть равны курортам)
        $dCitiesData = array();
        foreach( $countries as $country ) {
            $dCitiesData[$country->country_name][$country->city_name] = $country->city_id;
        }

        // Вставляем отели
        $photos = $ratings = $residence = $services = $cards = array();
        $ins = array();
        foreach( $hData as $countryName => $resorts ){
            foreach( $resorts as $resortName => $hotels ){

                $resortId = (int)$dResortData[$countryName][$resortName];
                $cityId = (int)$dCitiesData[$countryName][$resortName];
                foreach( $hotels as $hotel ){

                    $photos[$countryName][$resortName][$hotel['url']] = $hotel['photos'];
                    $ratings[$countryName][$resortName][$hotel['url']] = $hotel['rating'];
                    $residence[$countryName][$resortName][$hotel['url']] = $hotel['residence'];
                    $services[$countryName][$resortName][$hotel['url']] = $hotel['services'];
                    $cards[$countryName][$resortName][$hotel['url']] = $hotel['cards'];

                    $hotel['dir_country_id'] = (int)$hCountries[$countryName]->id;
                    $hotel['dir_resort_id'] = $resortId;
                    $hotel['dir_city_id'] = $cityId;
                    $hotel['dir_category_id'] = (int)$hCategories[$hotel['category']]->id;
                    $hotel['facility_badge_id'] = isset($hotel_facility_badges[$hotel['facility_badge']]) ? $hotel_facility_badges[$hotel['facility_badge']]->id : 0;
                    $hotel['badge_id'] = isset($hotel_badges[$hotel['hotel_badge']]) ? $hotel_badges[$hotel['hotel_badge']]->id : 0;

                    unset($hotel['category'], $hotel['cards'], $hotel['photos'], $hotel['rating'], $hotel['residence'], $hotel['services'], $hotel['badge'], $hotel['hotel_badge']);
                    $ins[] = $hotel;
                }
            }
        }

        TUtil::multipleInsertData('directory_hotels', $ins, 300);
//        echo '7) вставил отели<br/>';


        $countries = self::getDbHotelsByHData($hData);
//        echo '8) Фильтрация 2<br/>';
        $hData = array();
        foreach( $countries as $country ) {
            //if( isset($hData[$country->country_name][$country->resort_name][$country->hotel_name]) ){
            $hData[$country->country_name][$country->resort_name][$country->hotel_url] = $country->hotel_id;
            //}
        }

//        echo '9) собираю свежие отели после вставки $hData<br/>';
        // Вставляем рейтинги отелей
        self::insertHotelRatings($ratings, $hData);
//        echo '10) рейтинги отелей<br/>';
        // Вставляем фото отелей
        self::insertHotelPhotos($photos, $hData);
//        echo '11) фото отелей<br/>';
        // Вставляем проживание отелей
        self::insertHotelResidence($residence, $hData);
//        echo '12) проживание отелей<br/>';
        // Вставляем услуги отелей
        self::insertHotelServices($services, $hData);
//        echo '13) услуги отелей<br/>';
        // Вставляем кредитные карты отелей
        self::insertHotelCards($cards, $hData);
//        echo '14) кредитные карты отелей<br/>';
    }

    private static function filterHotelsData(&$hData){
        $hotels = self::getDbHotelsByHData($hData);

        $i = 0;
        foreach( $hotels as $hotel ){

            if( isset( $hData[$hotel->country_name][$hotel->resort_name][$hotel->hotel_url] ) ){


                unset($hData[$hotel->country_name][$hotel->resort_name][$hotel->hotel_url]);

                if (empty($hData[$hotel->country_name][$hotel->resort_name])) {
                    unset($hData[$hotel->country_name][$hotel->resort_name]);

                    if (empty($hData[$hotel->country_name])) {
                        unset($hData[$hotel->country_name]);
                    }

                }

            }
        }
    }

    private static function getDbHotelsByHData($hData){
        $hResorts = array();
        $hHotels = array();
        foreach( $hData as $_resorts ){
            $hResorts = array_merge($hResorts, array_keys($_resorts));
            foreach( $_resorts as $_hotels ){
                $hHotels = array_merge($hHotels, array_keys($_hotels));
            }
        }

        $hResorts = array_unique($hResorts);
        $hHotels = array_unique($hHotels);

        // Вытаскиваем свежеиспеченные отели
        $dbCom = Yii::app()->db->createCommand();
        $countries = $dbCom->select('c.name AS country_name, r.name AS resort_name, h.url AS hotel_url, h.id AS hotel_id, h.url')
            ->from('{{directory_countries}} c')
            ->join('{{directory_resorts}} r', array('AND', 'r.dir_country_id = c.id', array('IN', 'r.name', $hResorts)))
            ->join('{{directory_hotels}} h', array('AND', 'h.dir_resort_id = r.id', array('IN', 'h.url', $hHotels)))
            ->where(array('IN', 'c.name', array_keys($hData)))
            ->Order('c.position, r.position ASC')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        return $countries;
    }







    public static function saveNewHotels($country){

        $dir = 'E:/workout_hotels/' . $country .'/';
        $places = $regions = $hCategories = $hCards = $badges = array();
        $count_files = 0;
        foreach(scandir($dir) as $j => $file){

            if( strpos($file, 'json') === false ) continue;

            $hotels = explode("\n", file_get_contents($dir . $file));

            foreach( $hotels as $hotel ){
                $hotel = json_decode($hotel, true);

                if( empty($hotel) || !isset( $hotel['hotel_name'] ) || !isset( $hotel['url'] ) || !isset( $hotel['place'] ) ){
                    continue;
                }

                //////////////////////////////////////////////////////////////////////////////////


                // Собираем страны, регионы, города(курорты) и отели

                $_places = ['country' => '', 'resort' => '', 'city' => '', 'regions' => []];
                $wasCountry = false;
                foreach( $hotel['place'] as $p ){

                    switch($p['track']){
                        case 'country':

                            if( empty($_places['country']) ) {
                                $_places['country'] = $p['value'];

                                if( empty($_places['country']) ){
                                    $code = strtoupper(explode('/', str_replace('http://www.booking.com/hotel/', '', $hotel['url']))[0]);
                                    $_places['country'] = isset(self::$countries[$code]) ? str_replace(array('[', ']'), '', self::$countries[$code]) : '';
                                }

                                $wasCountry = true;
                            }

                            break;

                        case 'free_region':
                        case 'region':
                        case 'province':
                        case 'island':
                        case 'district':

                            if( $wasCountry && !isset($_places['regions'][$p['track']]) ){
                                $_places['regions'][$p['track']] = $p['value'];
                            }

                            break;

                        case 'city':

                            if( $_places['city'] == '' && $p['value'] != 'Дома и апартаменты' && $p['value'] != 'Варианты для отпуска') {
                                $_places['city'] = $_places['resort'] = $p['value'];
                            }

                            break;
                    }
                }


                if( isset($_places['regions']) ){
                    foreach( $_places['regions'] as $region_type => $region_name ) {
                        $regions[$_places['country']][$region_name][$_places['city']] = $region_type;
                    }
                }

                $category = 'Apartment';
                if( !empty($hotel['rating_stars']) ){
                    $category = (int)TUtil::getStrDigits($hotel['rating_stars']);
                } elseif( !empty($hotel['rating_circles']) ){
                    $category = (int)TUtil::getStrDigits($hotel['rating_circles']);
                }

                $hCategories[$category] = 1;

                if( !empty($hotel['hotel_badge']) ){
                    $badges['hotel_badges'][$hotel['hotel_badge']] = 1;
                }

                if( !empty($hotel['badge']) ){
                    $badges['hotel_facility_badges'][$hotel['badge']] = 1;
                }

                $cards = (array)$hotel['credit_cards'];
                if( !empty($cards) ){
                    foreach( $cards as $index => $card ){
                        if( is_array($card) && isset($card['name']) ){
                            $cards[$index] = $card['name'];
                        } elseif( is_string($card) ) {
                            $cards[$index] = trim($card);
                        } else {
                            unset($cards[$index]);
                            continue;
                        }

                        $hCards[$cards[$index]] = 1;
                    }
                }

                $rating = [];
                if( isset($hotel['rating']) && isset($hotel['rating']['voices']['total']) ){

                    $rating['voices'] = trim($hotel['rating']['voices']['total']);
                    $rating['rating'] = trim($hotel['rating']['rating']);
                    $rating['scores'] = [];

                    foreach( $hotel['rating']['scores'] as $s ){
                        $rating['scores'][] = array(
                            'name' => trim($s['name']),
                            'value' => trim($s['value']),
                        );
                    }
                }

                $residence = [];
                if( isset($hotel['facilities']) ){

                    foreach( $hotel['facilities'] as $r ){

                        if( !empty($r['name']) && !empty($r['options']) ) {

                            $value = '';
                            foreach($r['options'] as $option) {

                                $option['value'] .= ($option['value'][strlen($option['value']) - 1] != '.' ? '.' : '');
                                $value .= '<' . $option['type'] . '>' . $option['value'] . '</' . $option['type'] . '>';
                            }

                            $residence[] = array(
                                'name' => trim($r['name']),
                                'value' => $value,
                            );

                        }

                    }
                }

                $services = [];
                if( isset($hotel['policies']) ){

                    foreach( $hotel['policies'] as $s ){

                        if( !empty($s['name']) && !empty($s['value']) && $s['name'] != "Отмена/\nпредоплата" ) {

                            $value = '';
                            foreach($s['value'] as $_value) {

                                if( strpos($_value['value'], 'Пожалуйста, введите даты проживания') !== false || !$_value['value'] ){
                                    continue;
                                }

                                $_value['value'] .= ($_value['value'][strlen($_value['value']) - 1] != '.' ? '.' : '');
                                $value .= '<' . $_value['type'] . '>' . $_value['value'] . '</' . $_value['type'] . '>';
                            }

                            if( $value ) {
                                $services[] = array(
                                    'name' => trim($s['name']),
                                    'value' => $value,
                                );
                            }

                        }
                    }
                }

//                if( isset($places[$_places['country']][$_places['resort']][$hotel['hotel_name']]) && $places[$_places['country']][$_places['resort']][$hotel['hotel_name']]['url'] != $hotel['url'] ){
//                    $hotel['hotel_name'] = $hotel['hotel_name'] . '__|__' . uniqid();
//                }

                $places[$_places['country']][$_places['resort']][$hotel['url']] = [
                    'name' => $hotel['hotel_name'],
                    'description' => $hotel['description'],
                    'need_credit_card' => !empty($hotel['needCreditCard']),
                    'dir_country_id' => 0,
                    'dir_city_id' => 0,
                    'dir_resort_id' => 0,
                    'dir_category_id' => 0,
                    'badge_id' => 0,
                    'facility_badge_id' => 0,
                    'hotel_badge' => !empty($hotel['hotel_badge']) ? $hotel['hotel_badge'] : '',
                    'facility_badge' => !empty($hotel['badge']) ? $hotel['badge'] : '',
                    'address' => $hotel['address'],
                    'coords' => isset( $hotel['coords'] ) ? trim($hotel['coords']) : '',
                    'position' => 0,
                    'disabled' => 0,
                    'url' => $hotel['url'],
                    'category' => $category,
                    'cards' => $cards,
                    'photos' => isset($hotel['photos']) ? $hotel['photos'] : '',
                    'rating' => $rating,
                    'residence' => $residence,
                    'services' => $services,
                ];

            }


            unlink($dir . $file);
            $count_files++;

            if( $j >= 15 ){
                break;
            }

        }

        if( count($places) ) {
            self::insertHotelData($places, $regions, $hCategories, $hCards, $badges);
        }


        return $count_files;
    }


    public static function saveNewHotelsPhotos(){

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $dir = 'E:/workout_hotels/';

        $countries_names = [];
        foreach(scandir($dir) as $country){

            if( strpos($country, '[') !== false ) {
                $countries_names[] = $country;
            }
        }



        $db = Yii::app()->db;
        $_countries = array_chunk($countries_names, 5);

        foreach ($_countries as $countries) {

            $hotels_photos = [];

            foreach ($countries as $country) {

                $dir = 'E:/workout_hotels/' . $country .'/';

                foreach (scandir($dir) as $file) {

                    if (strpos($file, 'json') === false) continue;
                    $hotels = explode("\n", file_get_contents($dir . $file));

                    foreach ($hotels as $hotel) {
                        $hotel = json_decode($hotel, true);

                        if (empty($hotel) || !isset($hotel['photos']) || !isset($hotel['url'])) {
                            continue;
                        }

                        $hotels_photos[$hotel['url']] = json_encode($hotel['photos']);
                    }


                    unlink($dir . $file);

                }

                rmdir($dir);
            }

            $hotels = $db->createCommand()->select('id, url')->from('{{directory_hotels}}')->where(['IN', 'url', array_keys($hotels_photos)])->setFetchMode(PDO::FETCH_OBJ)->queryAll();

            foreach ($hotels as $hotel) {
                $db->createCommand()->update('{{hotel_photos}}', ['photos' => $hotels_photos[$hotel->url], 'count' => 0, 'count_all' => 0], 'dir_hotel_id = ' . $hotel->id);
            }

        }

        return true;
    }


    public static function processCountries(){

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $dir = 'E:/workout_hotels/';

        foreach(scandir($dir) as $country){

            if( strpos($country, '[') !== false ) {

                // Для сохранения отелей
                do {
                    $j = self::saveNewHotels($country);
                } while($j);

                rmdir($dir . $country);

            }
        }

        echo '<br><br>К О Н Е Ц !';
    }


    /*********************************************************************************/












    /************************* Additional Functions *************************/

    public static function deleteDirCountry($country){
        $db = Yii::app()->db;

        $db->createCommand()->delete('{{directory_countries}}', 'id = :country', [':country' => $country]);
        $db->createCommand()->delete('{{directory_regions}}', 'dir_country_id = :dir_country_id', [':dir_country_id' => $country]);

        $resorts = $db->createCommand()->select('id')->from('{{directory_resorts}}')->where('dir_country_id = :dir_country_id', [':dir_country_id' => $country])->queryColumn();

        $db->createCommand()->delete('{{directory_cities_to_regions}}', ['IN', 'dir_city_id', $resorts]);
        $db->createCommand()->delete('{{directory_resorts_to_regions}}', ['IN', 'dir_resort_id', $resorts]);

        $db->createCommand()->delete('{{directory_cities}}', 'dir_country_id = :dir_country_id', [':dir_country_id' => $country]);
        $db->createCommand()->delete('{{directory_resorts}}', 'dir_country_id = :dir_country_id', [':dir_country_id' => $country]);


        self::deleteDirHotels($country);
    }

    public static function deleteDirHotels($country){

        $db = Yii::app()->db;

        $hotels = $db->createCommand()->select('id')->from('{{directory_hotels}}')
            ->where('dir_country_id = :country', [':country' => $country])
            ->queryColumn();

        $db->createCommand()->delete('{{directory_hotels}}', ['IN', 'id', $hotels]);
        $db->createCommand()->delete('{{hotel_cards}}', ['IN', 'dir_hotel_id', $hotels]);
        $db->createCommand()->delete('{{hotel_photos}}', ['IN', 'dir_hotel_id', $hotels]);
        $db->createCommand()->delete('{{hotel_ratings}}', ['IN', 'dir_hotel_id', $hotels]);
        $db->createCommand()->delete('{{hotel_residence}}', ['IN', 'dir_hotel_id', $hotels]);
        $db->createCommand()->delete('{{hotel_services}}', ['IN', 'dir_hotel_id', $hotels]);

    }



    /**************************   COLLECTS IMAGES OF HOTELS **************************/

    /**
     * @param int $id
     */
    public static function saveHotelImages($id){

        $db = Yii::app()->db;

        $hObject = $db->createCommand()
            ->select('hp.photos, h.id, h.dir_city_id, h.dir_country_id')
            ->from('{{directory_hotels}} AS h')
            ->join('{{hotel_photos}} AS hp', 'hp.dir_hotel_id = h.id')
            ->where(['AND', 'hp.count = 0', 'id = :id'], [':id' => $id])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryRow();

        if( $hObject ){

            $base_dir = Yii::getPathOfAlias('webroot') . '/images/hotels/';
//            $base_dir = '../images/hotels/';
            $hotel_dir = $base_dir . $hObject->dir_country_id . '/' . $hObject->dir_city_id . '/' . $hObject->id . '/';

            if (!is_dir($hotel_dir)) {

                if( !is_dir($base_dir . $hObject->dir_country_id) ) mkdir($base_dir . $hObject->dir_country_id);

                if( !is_dir($base_dir . $hObject->dir_country_id . '/' . $hObject->dir_city_id) ) mkdir($base_dir . $hObject->dir_country_id . '/' . $hObject->dir_city_id);

                mkdir($base_dir . $hObject->dir_country_id . '/' . $hObject->dir_city_id . '/' . $hObject->id);

                $photos = json_decode($hObject->photos);

                $f = 0;
                foreach ($photos as $photo) {

                    $pf = @file_get_contents($photo);

                    if( ($pf) ) {
                        preg_match('/.+\.(\w+)$/xis', $photo, $pocket);

                        $file = $hotel_dir . '/' . ($f+1) . '.' . $pocket[1];
                        if(file_put_contents($file, $pf)){
                            ++$f;
                        }
                    }
                }

                $db->createCommand()->update('{{hotel_photos}}', ['count' => $f], 'dir_hotel_id = ' . $hObject->id);

            }
        }
    }

    public static function compareHotels(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $connection = new CDbConnection('mysql:host=127.0.0.1;dbname=xtourism', 'root', '');
        $connection->active = true;
        $xtourism_countries = array_flip($connection->createCommand()->select('name')->from('xt_directory_countries')->queryColumn());
        $connection->active = false;

        $hotel_countries = array_flip(Yii::app()->db->createCommand()->select('name')->from('{{directory_countries}}')->queryColumn());

        $countries = array_intersect_ukey($hotel_countries, $xtourism_countries, function ($key1, $key2) {

            if (mb_strtolower($key1, 'utf8') == mb_strtolower($key2, 'utf8'))
                return 0;
            else if (mb_strtolower($key1, 'utf8') > mb_strtolower($key2, 'utf8'))
                return 1;
            else
                return -1;
        });

        $new_hotels = [];
        foreach ($countries as $country_name => $_v){
            $x_country_id = $connection->createCommand()->select('id')->from('xt_directory_countries')->where('LOWER(name) = :name', [':name' => mb_strtolower($country_name, 'utf8')])->queryScalar();
            $h_country_id = Yii::app()->db->createCommand()->select('id')->from('{{directory_countries}}')->where('LOWER(name) = :name', [':name' => mb_strtolower($country_name, 'utf8')])->queryScalar();

            $x_hotels_urls = $connection->createCommand()->select('url')->from('xt_directory_hotels')->where('dir_country_id = ' . $x_country_id)->queryColumn();
            $h_hotels_urls = Yii::app()->db->createCommand()->select('url')->from('xt_directory_hotels')->where('dir_country_id = ' . $h_country_id)->queryColumn();

            $new_hotels[ Yii::app()->db->createCommand()->select('code')->from('xt_directory_countries')->where('id = ' . $h_country_id)->queryScalar() ] = array_values(array_diff($x_hotels_urls, $h_hotels_urls));
        }

        $new_hotels['CC'] = $connection->createCommand()->select('url')->from('xt_directory_hotels')->where('dir_country_id = 33')->queryColumn();
        $new_hotels['TD'] = $connection->createCommand()->select('url')->from('xt_directory_hotels')->where('dir_country_id = 180')->queryColumn();

        file_put_contents('E:/xtourism/additional_hotels.json', json_encode($new_hotels));
        TUtil::LogPre($new_hotels, true);

    }

    public static function groupSameHotels(){

        set_time_limit(0);
        ini_set('memory_limit', '-1');


        $db = Yii::app()->db;
        $hotels = $db->createCommand()->select('id, name, dir_country_id, dir_city_id')->from('{{directory_hotels}}')
            ->where(['LIKE', 'name', '%__|__%'])
            ->order('dir_country_id ASC, dir_city_id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();


        $grouped = [];
        foreach( $hotels as $hotel ) {
            $hName = explode('__|__', $hotel->name)[0];

            if( !isset($grouped[$hotel->dir_country_id]) || !isset($grouped[$hotel->dir_country_id][$hotel->dir_city_id]) || !isset($grouped[$hotel->dir_country_id][$hotel->dir_city_id][$hName]) ){

                $first = $db->createCommand()
                    ->select('id, name, dir_country_id, dir_city_id')
                    ->from('{{directory_hotels}}')
                    ->where(['AND', 'dir_country_id = :cn_id', 'dir_city_id = :ct_id', 'name = :name'], [':cn_id' => $hotel->dir_country_id, ':ct_id' => $hotel->dir_city_id, ':name' => $hName])
                    ->setFetchMode(PDO::FETCH_OBJ)
                    ->queryAll();

                $grouped[$hotel->dir_country_id][$hotel->dir_city_id][$hName][$first[0]->id] = $first[0]->id;
            }

            $grouped[$hotel->dir_country_id][$hotel->dir_city_id][$hName][$hotel->id] = $hotel->id;
        }

        TUtil::LogPre($grouped, true);

        foreach( $grouped as $countries ) {
            foreach( $countries as $cities ) {
                foreach( $cities as $hName => $hotel_ids ) {

                    if( count($hotel_ids) > 1 ) {
                        $i = 1;
                        foreach ($hotel_ids as $hId) {
                            echo $hName . ' #' . $i . '  -  ' . $hId . '<br>';
                            $db->createCommand()->update('{{directory_hotels}}', ['name' => $hName . ' #' . $i], 'id = ' . $hId);
                            ++$i;
                        }
                    }
                }
            }
        }

    }

    public static function downloadHotelsImages(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');


//        $db = Yii::app()->db;
//        $hotelPhotos = $db->createCommand()
//            ->select('h.url')
//            ->from('{{hotel_photos}} AS hp')
//            ->join('{{directory_hotels}} AS h', 'h.id = hp.dir_hotel_id')
//            ->where('hp.count != hp.count_all')
//            ->group('h.id')
//            ->order('h.id')
//            //->setFetchMode(PDO::FETCH_OBJ)
//            ->queryColumn();
//
//
//        file_put_contents(Yii::getPathOfAlias('webroot') . '/hotels.json', json_encode($hotelPhotos));
//        TUtil::LogPre(json_encode($hotelPhotos),true);



        for($len = 1; $len <= 10; ++$len) {

            $db = Yii::app()->db;
            $hotelPhotos = $db->createCommand()
                ->select('
                        hp.photos,
                        hp.dir_hotel_id AS id,
                        h.dir_country_id AS country,
                        h.dir_city_id AS city')
                ->from('{{hotel_photos}} AS hp')
                ->join('{{directory_hotels}} AS h', 'h.id = hp.dir_hotel_id')
                ->where('hp.count = 0 AND hp.count_all = 0')
                ->group('h.id')
                ->order('h.id')
                ->limit(1000)
                ->setFetchMode(PDO::FETCH_OBJ)
                ->queryAll();

//            TUtil::LogPre($hotelPhotos,true);

            $photos = [];
            $directories = [];

            foreach ($hotelPhotos as $i => $hPhotos) {

                $decoded_photos = json_decode($hPhotos->photos);
                foreach ($decoded_photos as $p_url) {
                    $photos[$hPhotos->id][$p_url] = $p_url;
                }

                $directories[$hPhotos->id] = ['country' => $hPhotos->country, 'city' => $hPhotos->city];
            }

            unset($hotelPhotos);
            $photos = array_chunk($photos, 3, true);
            //TUtil::LogPre($photos, true);


            foreach ($photos as $_photos) {
                $photosData = TMultiURL::load($_photos, ['maxURLCount' => 500, 'timeout' => 100]);

                foreach ($photosData as $hotel_id => $photoData) {

                    $f = 0;
                    $all = count($photoData);
                    foreach ($photoData as $url => $photo) {

                        if (isset($directories[$hotel_id]) && $photo && strpos($photo, '404 Not Found') === false) {

                            preg_match('/.+\.(\w+)$/xis', $url, $pocket);

                            $file = self::createImgPath($directories[$hotel_id]['country'], $directories[$hotel_id]['city'], $hotel_id) . ($f + 1) . '.' . $pocket[1];
                            if (file_put_contents($file, $photo)) {
                                ++$f;
                            }

                        }
                    }

                    $db->createCommand()->update('{{hotel_photos}}', ['count' => $f, 'count_all' => $all], 'dir_hotel_id = :hid', [':hid' => $hotel_id]);
                }
            }
        }


        die('END');
    }

    /**
     * Creates Images hotel path
     * @param integer $country
     * @param integer $city
     * @param integer $hotel
     * @return string
     */
    private static function createImgPath($country, $city, $hotel) {
        $roots = ['D:', 'E:', 'F:'];
        $baseDir = '/hotels/';
        foreach ($roots as $root){
            $dir = $root . $baseDir . $country . '/' . $city . '/' . $hotel;

            if( is_dir($dir) ){
                return $dir . '/';
            }
        }

        $baseDir = 'F:/hotels/';

        if( !is_dir($baseDir . $country) ) {
            mkdir($baseDir . $country);
        }

        if( !is_dir($baseDir . $country . '/' . $city) ) {
            mkdir($baseDir . $country . '/' . $city);
        }

        if( !is_dir($baseDir . $country . '/' . $city . '/' . $hotel) ) {
            mkdir($baseDir . $country . '/' . $city . '/' . $hotel);
        }

        return $baseDir . $country . '/' . $city . '/' . $hotel . '/';
    }


    public static function updateDbHotelPhotos() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $dir = 'E:/workout_hotels/';
        $db = Yii::app()->db;
        $_hotelIds = $db->createCommand()->select('id, url')->from('{{directory_hotels}}')->setFetchMode(PDO::FETCH_OBJ)->queryAll();

        $hotelIds = [];
        foreach($_hotelIds as $h ){
            $hotelIds[$h->url] = $h->id;
        }

        unset($_hotelIds);

        foreach (scandir($dir) as $j => $file) {

            if (strpos($file, 'json') === false) continue;

            $hotels = explode("\n", file_get_contents($dir . $file));

            foreach ($hotels as $i => $hotel) {

                $hotel = json_decode($hotel, true);

                if( isset($hotelIds[$hotel['url']]) )
                    $db->createCommand()->update('{{hotel_photos}}', ['photos' => json_encode($hotel['photos']), 'count_all' => 0], 'dir_hotel_id = :hid', [':hid' => $hotelIds[$hotel['url']]]);
            }

        }
    }


    public static function collectServices(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $db = Yii::app()->db;
        $services = $db->createCommand()->select('name')->from('{{hotel_services}}')->where('name="Озеро"')->group('name')->queryColumn();
        TUtil::LogPre($services, true);

    }


































    /************ Эти скрипты надо выполнить на сервере **************/

    public static function populateHotels_To_Facility_Badges(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $db = Yii::app()->db;
        $countries = $db->createCommand()->select('id')->from('{{directory_countries}}')->queryColumn();

        foreach ($countries as $country){
            $hotels = $db->createCommand()
                ->select('id, facility_badge_id')
                ->from('{{directory_hotels}}')
                ->where(['AND', 'dir_country_id = ' . $country, 'facility_badge_id != 0'])
                ->setFetchMode(PDO::FETCH_OBJ)
                ->queryAll();

            $hotels_to_facility_badges = [];
            foreach ($hotels as $hotel){
                if( $hotel->facility_badge_id == 3 ){
                    $hotels_to_facility_badges[] = ['dir_hotel_id' => $hotel->id, 'facility_badge_id' => 1];
                    $hotels_to_facility_badges[] = ['dir_hotel_id' => $hotel->id, 'facility_badge_id' => 2];
                } else {
                    $hotels_to_facility_badges[] = ['dir_hotel_id' => $hotel->id, 'facility_badge_id' => $hotel->facility_badge_id];
                }
            }

            TUtil::multipleInsertData('hotel_to_facility_badges', $hotels_to_facility_badges);
        }

        Yii::app()->db->createCommand( 'ALTER TABLE `xt_directory_hotels` DROP `facility_badge_id`;' )->query();
    }

    public static function clearResidenceFromPrime4anie(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        Yii::app()->db->createCommand()->delete('{{hotel_residence}}', 'name LIKE "* Примечания"');

    }

    public static function changeServicesProperties(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        Yii::app()->db->createCommand()->update('{{hotel_services}}', ['name' => 'Ванная'], 'name LIKE "Bathroom"');
        Yii::app()->db->createCommand()->update('{{hotel_services}}', ['name' => 'Питание и напитки'], 'name LIKE "Food & Drink"');
        Yii::app()->db->createCommand()->update('{{hotel_services}}', ['name' => 'Медиа'], 'name LIKE "Media/Technology"');
        Yii::app()->db->createCommand()->update('{{hotel_services}}', ['name' => 'На свежем воздухе'], 'name LIKE "Outdoor/View"');
        Yii::app()->db->createCommand()->update('{{hotel_services}}', ['name' => 'Удобства'], 'name LIKE "Room Amenities"');

    }

    public static function cleanDescriptionFromRatings(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $patterns = array(
            '/\n\n\nУслуги и удобства позитивно оценили \d+ гостей\n\n\n/',
            '/\n\n\nУслуги и удобства позитивно оценили \d+ гостей/',
            '/\n\n\nУслуги позитивно оценили \d+ гостей\n\n\n/',
            '/\n\n\nУслуги позитивно оценили \d+ гостей/',
            '/\n\n\nРасположение позитивно оценили \d+ гостей\n\n\n/',
            '/\n\n\nРасположение позитивно оценили \d+ гостей/',
            '/\n\n\nУдобства позитивно оценили \d+ гостей\n\n\n/',
            '/\n\n\nУдобства позитивно оценили \d+ гостей/',
            '/\n\n\nУдобства в номерах позитивно оценили \d+ гостей\n\n\n/',
            '/\n\n\nУдобства в номерах позитивно оценили \d+ гостей/',
            '/\n\n\nУдобство расположения позитивно оценили \d+ гостей\n\n\n/',
            '/\n\n\nУдобство расположения позитивно оценили \d+ гостей/',
            '/\n\n\nЗавтрак позитивно оценили \d+ гостей\n\n\n/',
            '/\n\n\nЗавтрак позитивно оценили \d+ гостей/',
            '/\n\n\nИнтерьер позитивно оценили \d+ гостей\n\n\n/',
            '/\n\n\nИнтерьер позитивно оценили \d+ гостей/',
            '/\n\n\nОкружающий район позитивно оценили \d+ гостей\n\n\n/',
            '/\n\n\nОкружающий район позитивно оценили \d+ гостей/',
            '/\n\n\nКафе и рестораны позитивно оценили \d+ гостей\n\n\n/',
            '/\n\n\nКафе и рестораны позитивно оценили \d+ гостей/',
            '/\n\n\nОздоровительные услуги позитивно оценили \d+ гостей\n\n\n/',
            '/\n\n\nОздоровительные услуги позитивно оценили \d+ гостей/',
            '/\n\n\nВозможности досуга поблизости позитивно оценили \d+ гостей\n\n\n/',
            '/\n\n\nВозможности досуга поблизости позитивно оценили \d+ гостей/',
        );

        $hotels = Yii::app()->db->createCommand( 'SELECT `id`, `description` FROM `xt_directory_hotels` WHERE `description` LIKE "%позитивно оценили%"' )->setFetchMode(PDO::FETCH_OBJ)->queryAll();
        foreach ($hotels as $hotel){
            Yii::app()->db->createCommand()->update('{{directory_hotels}}', ['description' => preg_replace($patterns, '', $hotel->description)], 'id = ' . $hotel->id);
        }

        die('Happy End!');
    }

    public static function set_is_not_translated(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $hotels = Yii::app()->db->createCommand( 'SELECT `id`, `description` FROM `xt_directory_hotels` WHERE `description` LIKE "%Мы работаем над переводом этого описания на ваш язык. Приносим извинения за неудобства.\n%"' )->setFetchMode(PDO::FETCH_OBJ)->queryAll();

        foreach ($hotels as $hotel){
            Yii::app()->db->createCommand()->update(
                '{{directory_hotels}}',
                [
                    'description' => preg_replace('/Мы работаем над переводом этого описания на ваш язык. Приносим извинения за неудобства.\n/', '', $hotel->description),
                    'is_not_translated' => 1
                ],
                'id = ' . $hotel->id
            );
        }

        die('Happy End!');
    }

    public static function addHotelFields(){
        Yii::app()->db->createCommand( 'ALTER TABLE  `xt_directory_hotels` ADD `is_not_translated` TINYINT( 1 ) NOT NULL AFTER `url`' )->execute();
    }


    public static function maxRatingsHotel(){

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $db = Yii::app()->db;

        $countries = $db->createCommand()->select('id')->from('{{directory_countries}}')->queryColumn();
        $max_scores_hotel = 0;
        $max_scores_count = 0;

        foreach( $countries as $country ){
            $hotels = $db->createCommand()
                ->select('r.dir_hotel_id, r.scores')
                ->from('{{hotel_ratings}} AS r')
                ->join('{{directory_hotels}} AS h', 'h.id = r.dir_hotel_id')
                ->where('h.dir_country_id = ' . $country)
                ->queryAll();

            foreach( $hotels as $hotel ){
                $temp_scores_count = count(json_decode($hotel['scores']));

                if( $max_scores_count < $temp_scores_count ){
                    $max_scores_count = $temp_scores_count;
                    $max_scores_hotel = $hotel['dir_hotel_id'];
                }

            }
        }

        TUtil::LogPre($max_scores_hotel, true);

    }


    public static function max_hotel_residence(){

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $db = Yii::app()->db;
        $residences = $db->createCommand('SELECT `dir_hotel_id`, COUNT(`name`) AS c FROM `xt_hotel_residence` GROUP BY `dir_hotel_id` HAVING c = 5 LIMIT 10')->queryAll();

        TUtil::LogPre($residences, true);
    }

    public static function max_hotel_services(){

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $db = Yii::app()->db;
        $residences = $db->createCommand('SELECT `dir_hotel_id`, COUNT(`name`) AS c FROM `xt_hotel_services` GROUP BY `dir_hotel_id` HAVING c = 13 LIMIT 10')->queryAll();

        TUtil::LogPre($residences, true);
    }

    public static function clean_from_price_hotel_services(){

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $db = Yii::app()->db;
        $db->createCommand('DELETE FROM `xt_hotel_services` WHERE LOWER(`name`) = "цены"')->query();

        die('Finish!');
    }

    public static function collectHotelsWithoutImg(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $db = Yii::app()->db;
        $hotels = $db->createCommand()
            ->select('c.code, h.url')
            ->from('{{hotel_photos}} AS p')
            ->join('{{directory_hotels}} AS h', 'h.id = p.dir_hotel_id')
            ->join('{{directory_countries}} AS c', 'c.id = h.dir_country_id')
            ->where('p.count = 0')
            ->order('c.code')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $ret = [];
        foreach ($hotels as $hotel){
            $ret[$hotel->code][] = $hotel->url;
        }

        file_put_contents('E:/xtourism/hotels_photos.json', json_encode($ret));
        TUtil::LogPre($ret, true);
    }


    public static function changeEncodingDB(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $db = Yii::app()->db;

        $tables = $db->createCommand('SHOW TABLES')->queryColumn();
        $log = [];

        foreach ($tables as $table) {
            $columns = $db->createCommand('SHOW FULL COLUMNS FROM `' . $table . '`')->setFetchMode(PDO::FETCH_OBJ)->queryAll();

            foreach ($columns as $column) {
                if (isset($column->Collation) && $column->Collation !== 'utf8_unicode_ci') {
                    $log[] = $table . '.' . $column->Field;
                    $db->createCommand('ALTER TABLE `' . $table . '` CHANGE `' . $column->Field . '` `' . $column->Field . '` ' . $column->Type . ' CHARACTER SET utf8 COLLATE utf8_unicode_ci ' . ($column->Null == "NO" ? 'NOT NULL' : 'NULL DEFAULT ' . json_encode($column->Default)))->execute();
                }
            }
        }

        echo '<pre>' . var_export($log, true) . '</pre>';
        die();
    }


    /**
     * @param int $dest
     * @param int $src
     */
    public static function copyCrimeHotelsToRussia($dest, $src){
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $db = Yii::app()->db;

        /********************* REGIONS *********************/
        $data = $db->createCommand()
            ->select('*')
            ->from('{{directory_regions}}')
            ->where('dir_country_id = :dc_id', [':dc_id' => $src])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $new_regions = $regions = [];
        foreach ($data as $region){
            $db->createCommand()
                ->insert('{{directory_regions}}', [
                    'name' => $region->name,
                    'type' => $region->type,
                    'description' => '',
                    'dir_country_id' => $dest
            ]);

            $new_region_id = $db->getLastInsertID();

            $new_regions[$region->name] = $new_region_id;
            $regions[$region->id] = $region->name;
        }

        /********************* RESORTS *********************/
        $data = $db->createCommand()
            ->select('*')
            ->from('{{directory_resorts}}')
            ->where('dir_country_id = :dc_id', [':dc_id' => $src])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $new_resorts = $resorts = [];
        foreach ($data as $resort){
            $db->createCommand()
                ->insert('{{directory_resorts}}', [
                    'name' => $resort->name,
                    'parent_id' => 0,
                    'description' => '',
                    'dir_country_id' => $dest,
                    'is_combined' => 0,
                    'position' => 0,
                    'rating' => 0,
                    'disabled' => 0,
            ]);

            $new_resort_id = $db->getLastInsertID();

            $new_resorts[$resort->name] = $new_resort_id;
            $resorts[$resort->id] = $resort->name;
        }

        $resorts_to_regions = $db->createCommand()
            ->select('*')
            ->from('{{directory_resorts_to_regions}}')
            ->where(['IN', 'dir_resort_id', array_keys($resorts)])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        foreach ($resorts_to_regions as $r_to_r){
            $db->createCommand()->insert('{{directory_resorts_to_regions}}', [
                'dir_resort_id' => $new_resorts[$resorts[$r_to_r->dir_resort_id]],
                'dir_region_id' => $new_regions[$regions[$r_to_r->dir_region_id]],
            ]);
        }


        /********************* CITIES *********************/
        $data = $db->createCommand()
            ->select('*')
            ->from('{{directory_cities}}')
            ->where('dir_country_id = :dc_id', [':dc_id' => $src])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $new_cities = $cities = [];
        foreach ($data as $city){
            $db->createCommand()
                ->insert('{{directory_cities}}', [
                    'name' => $city->name,
                    'description' => '',
                    'dir_country_id' => $dest,
            ]);

            $new_city_id = $db->getLastInsertID();

            $new_cities[$city->name] = $new_city_id;
            $cities[$city->id] = $city->name;
        }

        $cities_to_regions = $db->createCommand()
            ->select('*')
            ->from('{{directory_cities_to_regions}}')
            ->where(['IN', 'dir_city_id', array_keys($cities)])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        foreach ($cities_to_regions as $c_to_r){
            $db->createCommand()->insert('{{directory_cities_to_regions}}', [
                'dir_city_id' => $new_cities[$cities[$c_to_r->dir_city_id]],
                'dir_region_id' => $new_regions[$regions[$c_to_r->dir_region_id]],
            ]);
        }


        /********************* HOTELS *********************/
        $data = $db->createCommand()
            ->select('*')
            ->from('{{directory_hotels}}')
            ->where('dir_country_id = :dc_id', [':dc_id' => $src])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        foreach ($data as $hotel){
            $db->createCommand()
                ->insert('{{directory_hotels}}', [
                    'name' => $hotel->name,
                    'description' => $hotel->description,
                    'dir_country_id' => $dest,
                    'dir_city_id' => $new_cities[$cities[$hotel->dir_city_id]],
                    'dir_resort_id' => $new_resorts[$resorts[$hotel->dir_resort_id]],
                    'need_credit_card' => $hotel->need_credit_card,
                    'dir_category_id' => $hotel->dir_category_id,
                    'address' => $hotel->address,
                    'coords' => $hotel->coords,
                    'badge_id' => $hotel->badge_id,
                    'position' => 0,
                    'rating' => 0,
                    'disabled' => 0,
                    'url' => $hotel->url,
                    'is_not_translated' => $hotel->is_not_translated,
            ]);

        }

        self::copyCrimeHotelDataToRussia($dest, $src);


        die('HAPPY END!');
    }


    public static function copyCrimeHotelDataToRussia($dest, $src){

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $tables = [
            'hotel_cards',
            'hotel_photos',
            'hotel_ratings',
            'hotel_residence',
            'hotel_services',
            'hotel_to_facility_badges'
        ];


        $db = Yii::app()->db;

        $_hotels = $db->createCommand()
            ->select('id, url')
            ->from('{{directory_hotels}}')
            ->where('dir_country_id = :dc_id', [':dc_id' => $src])
            ->order('url')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $xc_hotels = [];
        foreach ($_hotels as $_hotel){
            $xc_hotels[$_hotel->url] = $_hotel->id;
        }

        $xc_cities = $db->createCommand()
            ->select('name')
            ->from('{{directory_cities}}')
            ->where('dir_country_id = :dc_id', [':dc_id' => $src])
            ->order('name')
            ->queryColumn();

        $ru_cities = $db->createCommand()
            ->select('id')
            ->from('{{directory_cities}}')
            ->where(['AND', 'dir_country_id = :dc_id', ['IN', 'name', $xc_cities]], [':dc_id' => $dest])
            ->group('id')
            ->queryColumn();

        $_hotels = $db->createCommand()
            ->select('id, url')
            ->from('{{directory_hotels}}')
            ->where(['AND', 'dir_country_id = :dc_id', ['IN', 'dir_city_id', $ru_cities]], [':dc_id' => $dest])
            ->order('url')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $ru_hotels = [];
        foreach ($_hotels as $_hotel){
            $ru_hotels[$_hotel->url] = $_hotel->id;
        }

        $ru_hotels = array_intersect_key($ru_hotels, $xc_hotels);

        foreach ($xc_hotels as $url => $xc_hotel_id){

            foreach ($tables as $table){
                $data = $db->createCommand()
                    ->select('*')
                    ->from('{{' . $table . '}}')
                    ->where('dir_hotel_id = :h_id', [':h_id' => $xc_hotel_id])
                    ->queryAll();

                $ru_hotel_data = [];
                foreach ($data as $element){
                    unset($element['id']);
                    $element['dir_hotel_id'] = $ru_hotels[$url];
                    $ru_hotel_data[] = $element;
                }

                TUtil::multipleInsertData($table, $ru_hotel_data);
            }

        }


        die('HAPPY END!');
    }


    public static function deleteCrimeHotelDataFromRussia(){

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $ru_country_id = 172;
        $xc_country_id = 213;

        $tables = [
            'hotel_cards',
            'hotel_photos',
            'hotel_ratings',
            'hotel_residence',
            'hotel_services',
            'hotel_to_facility_badges'
        ];

        $db = Yii::app()->db;

        $_hotels = $db->createCommand()
            ->select('id, url')
            ->from('{{directory_hotels}}')
            ->where('dir_country_id = :dc_id', [':dc_id' => $xc_country_id])
            ->order('url')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $xc_hotels = [];
        foreach ($_hotels as $_hotel){
            $xc_hotels[$_hotel->url] = $_hotel->id;
        }

        $xc_cities = $db->createCommand()
            ->select('name')
            ->from('{{directory_cities}}')
            ->where('dir_country_id = :dc_id', [':dc_id' => $xc_country_id])
            ->order('name')
            ->queryColumn();

        $ru_cities = $db->createCommand()
            ->select('id')
            ->from('{{directory_cities}}')
            ->where(['AND', 'dir_country_id = :dc_id', ['IN', 'name', $xc_cities]], [':dc_id' => $ru_country_id])
            ->group('id')
            ->queryColumn();

        $_hotels = $db->createCommand()
            ->select('id, url')
            ->from('{{directory_hotels}}')
            ->where(['AND', 'dir_country_id = :dc_id', ['IN', 'dir_city_id', $ru_cities]], [':dc_id' => $ru_country_id])
            ->order('url')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $ru_hotels = [];
        foreach ($_hotels as $_hotel){
            $ru_hotels[$_hotel->url] = $_hotel->id;
        }

        $hotels = array_intersect_key($ru_hotels, $xc_hotels);

        foreach ($hotels as $ru_hotel_id){
            foreach ($tables as $table){
                $db->createCommand()->delete('{{' . $table . '}}', 'dir_hotel_id = :h_id', [':h_id' => $ru_hotel_id]);
            }

        }


        die('HAPPY END!');
    }




    public static function copyHotelsImages() {

        $createImgPath = function($country, $city, $hotel){
            $baseDir = 'F:/saved_hotels/';

            if( !is_dir($baseDir . $country) ) {
                mkdir($baseDir . $country);
            }

            if( !is_dir($baseDir . $country . '/' . $city) ) {
                mkdir($baseDir . $country . '/' . $city);
            }

            if( !is_dir($baseDir . $country . '/' . $city . '/' . $hotel) ) {
                mkdir($baseDir . $country . '/' . $city . '/' . $hotel);
            }

            return $baseDir . $country . '/' . $city . '/' . $hotel . '/';
        };

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $db = Yii::app()->db;

        $roots = ['D:', 'E:', 'F:'];

        $hotels = $db->createCommand()
            ->select('dh.id, dh.dir_city_id AS city, dh.dir_country_id AS country, p.count')
            ->from('{{operator_hotels}} AS h')
            ->join('{{directory_hotels}} AS dh', 'dh.id = h.directory_id')
            ->join('{{hotel_photos}} AS p', 'p.dir_hotel_id = dh.id')
            ->where('h.directory_id != 0 AND p.is_on_server = 0')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

//        TUtil::LogPre($hotels, true);

        foreach ($hotels as $hotel){
            $src_dir = '';
            foreach ($roots as $root){
                $temp_src_dir = $root . '/hotels/' . $hotel->country . '/' . $hotel->city . '/' . $hotel->id . '/';

                if( is_dir($temp_src_dir) ){
                    $src_dir = $temp_src_dir;
                    break;
                }
            }

            if( $src_dir != '' ){

                $dest_dir = $createImgPath($hotel->country, $hotel->city, $hotel->id);

                $count = $hotel->count;
                $j = 1;
                for($i=1; $i<=$hotel->count; ++$i){
                    if( !@copy($src_dir . $i . '.jpg', $dest_dir . $j . '.jpg') ){
                        $count -= 1;
                        $db->createCommand()->update('{{hotel_photos}}', ['count' => $count], 'dir_hotel_id = :id', [':id' => $hotel->id]);
                        echo 'Not copied photo for hotel: ' . $hotel->id . ' with photo number: ' . $i . '<br>';
                    } else {
                        ++$j;
                    }
                }

                $db->createCommand()->update('{{hotel_photos}}', ['is_on_server' => 1], 'dir_hotel_id = :id', [':id' => $hotel->id]);

            } else {
                echo ('Not found photos for hotel: /hotels/' . $hotel->country . '/' . $hotel->city . '/' . $hotel->id . '<br>');
            }

        }

        die('Happy End!');

    }


    /**
     * Updates data table of operators
     * @param mixed $operators
     */
    public static function updateOperatorHotelsCountries(){

        $db = Yii::app()->db;

        $hotels = $db->createCommand()
            ->select('h.id, r.country')
            ->from('{{operator_hotels}} AS h')
            ->join('{{operator_resorts}} AS r', 'r.element_id = h.resort AND r.operator_id = h.operator_id')
            ->where('h.country = 0')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        foreach( $hotels as $hotel ){
            $db->createCommand()
                ->update('{{operator_hotels}}',
                    ['country' => $hotel->country],
                    'id = :id',
                    [':id' => $hotel->id]
                );
        }

        die('Все отели были обновлены!');
    }


    public static function replaceHotelPhotos(){
        set_time_limit(0);
        ini_set('memory_limit', -1);

        $db = Yii::app()->db;
        $photos = $db->createCommand()->select('dir_hotel_id, count')->from('{{hotel_photos}}')->where('is_on_server = 1')->setFetchMode(PDO::FETCH_OBJ)->queryAll();

        $dump_name = "E:/hotel_photos.sql";
        file_put_contents($dump_name, "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\nSET time_zone = '+00:00';\n");

        $str = "";
        foreach ($photos as $row){
            $str .= "UPDATE `xt_hotel_photos` SET `count` = {$row->count}, `is_on_server` = 1 WHERE `dir_hotel_id` = {$row->dir_hotel_id};\n";
        }


        file_put_contents($dump_name, "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\nSET time_zone = '+00:00';\n" . $str);
        die('Happy End!');
    }


    public static function generateSQLDump($table, $files_count, $max_select_rows=200000, $max_insert_rows=300){
        set_time_limit(0);
        ini_set('memory_limit', -1);

        $link = mysqli_connect('localhost', 'root', '', 'xtourism');
        $db = Yii::app()->db;
        $count = $db->createCommand()->select('COUNT(*)')->from('{{' . $table . '}}')->queryScalar();

        $rows_in_file = floor($count/$files_count);
        $rest_rows = $count%$files_count;
        $files_rows = [];

        for($i=0; $i<$files_count; ++$i){
            if( $files_count - 1 == $i ){
                $files_rows[] = $rows_in_file + $rest_rows;
            } else {
                $files_rows[] = $rows_in_file;
            }
        }

        $columns = $db->createCommand('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "xtourism" AND TABLE_NAME = "xt_' . $table . '"')->queryColumn();
        $columns = '`' . implode('`,`', $columns) . '`';

        $offset = 0;
        foreach ($files_rows as $i => $file_rows){

            $dump_name = "E:/" . time() . " _$i.sql";
            file_put_contents($dump_name, "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\nSET time_zone = '+00:00';\n");

            $steps = TUtil::chunk_number($file_rows, $max_select_rows);

            foreach ($steps as $step){
                $data = $db->createCommand()->select('*')->from('{{' . $table . '}}')->limit($step, $offset)->queryAll();

                $str_data = [];
                $inserts = 1;

                foreach ($data as $j => $row){
                    foreach ($row as &$value) {$value = '"' . mysqli_real_escape_string($link, $value) . '"';}

                    $str_data[] = '(' . implode(',', $row) . ')';
                    $data[$j] = null;

                    if( $inserts >= $max_insert_rows ){
                        file_put_contents($dump_name, " INSERT INTO `xt_$table` ($columns) VALUES \n " . implode(',', $str_data) . ";\n", FILE_APPEND);
                        unset($str_data);
                        $str_data = [];
                        $inserts = 0;
                    }

                    ++$inserts;
                }

                if( !empty($str_data) ){
                    file_put_contents($dump_name, " INSERT INTO `xt_$table` ($columns) VALUES \n " . implode(',', $str_data) . ";\n", FILE_APPEND);
                    unset($str_data);
                }

                $offset += $step;
            }


            $path = "E:/$table" . "_partial_sql_dump_" . ($i+1) . ".zip";
            $zip = new ZipArchive();
            $zip->open($path, ZipArchive::CREATE);
            $zip->addFile($dump_name);
            $zip->close();

            unlink($dump_name);
        }

        die('Happy End!');
    }

}