<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 07.08.2016
 * Time: 17:32
 */

class HSearcher extends CWidget {

    /**
     * @var integer
     */
    public $hotel_id;

    /**
     * @var integer
     */
    public $user_id;

    /**
     * @var integer
     */
    public $panel_class='default';



    /**
     * @var bool
     */
    private $has_access = false;

    /**
     * @var ArDirHotels
     */
    private $hotel;

    /**
     * @var \TSearch\Searcher
     */
    private $searcher;

    /**
     * @var array
     */
    private $dep_cities;

    /**
     * @var array
     */
    private $operators_by_dep_cities = [];

    /**
     * @var SearcherStandardSettings
     */
    private $settings;

    /**
     * @var string
     */
    private $wPath = 'tsearch.searchers.widgets';

    /**
     * Init
     */
    public function init() {

        // TODO: Раскоментировать когда заработает поиск туров!!!
        $package = ArShopUsersPackages::model()->findByAttributes(['user_id' => $this->user_id]);
//        if( $package && $package->isValid() && $package->hasProduct(ArShopProductsTypes::PDT_SEARCHER) ) {
        if( $package && $package->isValid() && $package->hasProduct(ArShopProductsTypes::PDT_TOUR_SHOWCASE) ) {

            // TODO: Раскоментировать когда заработает поиск туров!!!
//            $this->settings = ArUserSearcher::model()->findByAttributes(['user_id' => $this->user_id])->searcherSettings();
            $this->settings = (object)[
                'dep_cities' => null,
                'depCity' => null,
                'meals' => null,
                'mealsMore' => null,
                'nightFrom' => null,
                'nightTo' => null,
                'minPrice' => null,
                'maxPrice' => null,
                'currency' => null,
                'adults' => null,
                'children' => null,
                'child1' => null,
                'child2' => null,
                'child3' => null,
                'operators' => null,
            ];

            // TODO: Раскоментировать когда заработает поиск туров!!!
//            $this->searcher = new \TSearch\Searcher($this->settings);
            $this->searcher = new \TSearch\Searcher();
            $this->hotel = ArDirHotels::model()->findByPk($this->hotel_id);

            $operators = empty($this->settings->operators) ? array_keys(\TSearch\TOperator::operatorsInfo()) : (array)$this->settings->operators;
            $where = ['AND', 'oc.directory_id = :dir_country', ['IN', 'oc.operator_id', $operators], 'oc.f_deleted = 0'];

            if( !empty($this->settings->dep_cities) ){
                $where[] = ['IN', 'odc.directory_id', $this->settings->dep_cities];
            }

            $dep_cities = Yii::app()->db->createCommand()
                ->select('odc.directory_id, odc.name, GROUP_CONCAT(odc.operator_id SEPARATOR ",") AS operators')
                ->from('{{operator_countries}} AS oc')
                ->join('{{operator_relations_dep_cities_countries}} AS rcc', 'rcc.country = oc.element_id AND rcc.operator_id = oc.operator_id')
                ->join('{{operator_dep_cities}} AS odc', 'odc.element_id = rcc.dep_city AND odc.operator_id = rcc.operator_id')
                ->where($where, [':dir_country' => $this->hotel->dir_country_id])
                ->group('odc.directory_id')
                ->setFetchMode(PDO::FETCH_OBJ)
                ->queryAll();


            $this->dep_cities = CHtml::listData($dep_cities, 'directory_id', 'name' );

            foreach( $dep_cities as $city ){
                $this->operators_by_dep_cities['_' . $city->directory_id] = explode(',', $city->operators);
            }

            $basePath = Yii::getPathOfAlias('tsearch.hotel.HSearcher');
            $baseUrl = Yii::app()->getAssetManager()->publish($basePath);
            Yii::app()->getClientScript()->registerScriptFile($baseUrl . '/' . 'HSearcher.js');

            if( !empty($this->operators_by_dep_cities) ) {
                $this->has_access = true;
            }
        }

        parent::init();
    }

    /**
     * Run
     */
    public function run() {

        // TODO: Раскоментировать когда заработает поиск туров!!!
        if( $this->has_access ) { ?>

            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tsearch/form.css"/>
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tsearch/spinners.css"/>

            <div id="t-hotel-searcher" style="margin-bottom: 45px;">

                <div class="panel panel-<?=$this->panel_class?> margin-bottom45">

                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span class="glyphicon glyphicon-triangle-bottom t-head-mini-filter-icon" style="float: right; cursor: pointer;"></span>
                            <a data-toggle="collapse" class="t-head-mini-filter" href="#" aria-expanded="true">
                                Поиск туров в <strong><?=CHtml::encode($this->hotel->name) ?></strong>
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse in t-body-mini-filter">
                        <div class="panel-body">

                            <div id="xtourism" class="xtourism xtourism-rounded-3">

                                <div class="row">
                                    <? //TODO: Поменять на class="col-md-8 col-sm-8 col-xs-8" когда заработает поиск(будет больше ТО) ?>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <div class="xtourism-section xtourism-section-direction">

                                                    <div class="xtourism-subsection">
                                                        <? $dep_city = $this->settings->depCity ? $this->settings->depCity : key($this->dep_cities) ?>
                                                        <? $this->widget($this->wPath . '.TSearcherDepCities.TSearcherDepCities', ['data' => $this->dep_cities, 'selectedId' => $dep_city]); ?>
                                                    </div>

                                                    <div class="xtourism-section-food">
                                                        <? $this->widget($this->wPath . '.TSearcherMeals.TSearcherMeals', ['data' => $this->searcher->meals()/*, 'selectedId' => $this->settings->meals, 'more' => $this->settings->mealsMore*/]); ?>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <div class="xtourism-section xtourism-section-dates">
                                                    <div class="xtourism-subsection">
                                                        <? $this->widget($this->wPath . '.TSearcherAvailableDates.TSearcherAvailableDates', []); ?>
                                                    </div>
                                                    <div class="xtourism-subsection xtourism-section-nights">
                                                        <? $this->widget($this->wPath . '.TSearcherDurations.TSearcherDurations'/*, ['selectedNightFrom' => $this->settings->nightFrom, 'selectedNightTo' => $this->settings->nightTo]*/); ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <div class="xtourism-section xtourism-section-price">
                                                    <? $this->widget($this->wPath . '.TSearcherPrice.TSearcherPrice'/*, ['minPrice' => $this->settings->minPrice, 'maxPrice' => $this->settings->maxPrice]*/); ?>
                                                </div>

                                                <div class="xtourism-section xtourism-section-currency">
                                                    <? $this->widget($this->wPath . '.TSearcherCurrency.TSearcherCurrency'/*, ['isCU' => $this->settings->currency]*/); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <div class="xtourism-section xtourism-section-people t-modPeople">
                                                    <? $this->widget($this->wPath . '.TSearcherPeople.TSearcherPeople'/*, ['selAdults' => $this->settings->adults, 'selChildren' => $this->settings->children, 'selChild1' => $this->settings->child1, 'selChild2' => $this->settings->child2, 'selChild2' => $this->settings->child3]*/); ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

<!--                                    <div class="col-md-4 col-sm-4 col-xs-4">-->
<!--                                        <div id="xtourism-section-operators" class="xtourism-section xtourism-section-operators t-modOperators"> --><?//
//                                            $data = [];
//                                            if( isset($this->operators_by_dep_cities['_' . $dep_city]) ) {
//                                                $data = CHtml::listData(\TSearch\TOperator::operatorsInfo($this->operators_by_dep_cities['_' . $dep_city]), 'id', 'name');
//                                            }
//                                            $this->widget($this->wPath . '.TSearcherOperators.TSearcherOperators', ['data' => $data, 'height' => 219]); ?>
<!--                                        </div>-->
<!--                                    </div>-->
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-4" style="margin-left: 30px;">
                                    <button type="button" class="btn btn-sm results-btn" id="buttonSearch"><span class="glyphicon glyphicon-search"></span> Найти туры</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12" style="padding: 0 45px 0 45px;">
                                    <hr>
                                </div>
                            </div><?

                            $this->widget('tsearch.result.TResult', [
                                'headerOptions' => [
                                    'style' => 'padding: 0 45px 0 45px;'
                                ],
                                'mainOptions' => [
                                    'style' => 'padding: 0 45px 0 45px;'
                                ],
                                'footerOptions' => [
                                    'style' => 'padding: 0 45px 0 45px;'
                                ],

                                'operators_count' => isset($this->operators_by_dep_cities['_' . $dep_city]) ? count($this->operators_by_dep_cities['_' . $dep_city]) : 0,
                                'result_url' => $this->controller->createUrl('FrontSearcher/searchByHotel', ['id' => $this->user_id, 'hid' => $this->hotel_id]),
                                'result_tbl_headers' => ['№', 'Оператор', 'Даты', 'Питание', 'Размещение', 'Цена', ''],
                                'result_tbl_row' => 'js:function (data, i) {
                                    return "<td style=\'width: 5%;\'>" + i + "</td>" +
                                    "<td><span><img src=\'" + data.oImgPath + "\' data-toggle=\'tooltip\' data-placement=\'top\' data-original-title=\'" + data.oName + "\' height=\'25\' width=\'30\' border=\'0/\'></span></td>" +
                                    "<td><span data-toggle=\'tooltip\' data-placement=\'top\' data-html=\'true\' data-original-title=\'" + data.tStartWeekDay + " - " + data.tEndWeekDay + "<br>" + data.tDaysTxt + ", " + data.tNightsTxt + "\'>" + data.tStartResDateDM + "<i>" + data.tNightsTxt + "</i></span></td>" +
                                    "<td><span data-toggle=\'tooltip\' data-placement=\'top\' data-original-title=\'" + data.tMealDescription + "\'>" + data.tMeal + "</span></td>" +
                                    "<td><span data-toggle=\'tooltip\' data-placement=\'top\' data-original-title=\'" + data.tRoom + "\'>" + data.hResidence + "</span></td>" +
                                    "<td>" + data.tNormalizedPrice + " " + data.tHtmlCurrency + "</td>" +
                                    "<td class=\'text-right\' ><button type=\'button\' rp=\'" + data.tRequestParams + "\' class=\'btn btn-sm results-btn t-tour-request\'>&nbsp;&nbsp;&nbsp;Заказ&nbsp;&nbsp;&nbsp;</button></td>";
                                }',
                                'result_error_func' => 'js:function(error_text){
                                    $("[id=\'t-hotel-searcher\']").html("<div class=\'alert alert-warning text-center\' style=\'margin-bottom:0;\'><span class=\'glyphicon glyphicon-warning-sign\'></span> Поиск туров временно недоступен</div>");
                                }'
                            ]); ?>

                        </div>
                    </div>
                </div>

            </div>


            <? $this->controller->renderPartial('common_views.tour_request.request_form', [
                'modal' => true,
                'request_func' => 'js:function(form, data, hasError){
                    if( !hasError ){
                        var $f = form;
                        $.sendRequest( {"url": form.attr("action")}, form.serialize(), function(r){
                            if( r == "expired" || r == "" ){
                                $(document.body).html("<div class=\'alert alert-warning text-center\' style=\'margin: 0\'><strong><span class=\'glyphicon glyphicon-warning-sign\'></span> Поиск туров временно недоступен</strong></div>");
                                return false;
                            }

                            if( r == "bad_params" ){
                                $(document.body).html("<div class=\'alert alert-warning text-center\' style=\'margin: 0\'><strong><span class=\'glyphicon glyphicon-warning-sign\'></span> Информация о данном туре временно недоступна.</strong></div>");
                                return false;
                            }

                            r = JSON.parse(r);
                            $f.hide();
                            $("#modalTourRequest [type=\'submit\']").hide();
                            $("#modalTourRequest #requestMessageContainer").html(r.message).show().find(".alert").css({"margin": 0});
                        }, "HTML");
                    }
                }'
            ]);?>

            <? $all_operators = \TSearch\TOperator::operatorsInfo(empty($this->settings->operators) ? null : $this->settings->operators); ?>
            <? Yii::app()->clientScript->registerScript(uniqid(), 'window.HSearcher("t-hotel-searcher", ' . $this->user_id . ', ' . CJSON::encode(array_values($all_operators)) . ', ' . CJSON::encode($this->operators_by_dep_cities) . ');', CClientScript::POS_READY);
        }

    }

}
