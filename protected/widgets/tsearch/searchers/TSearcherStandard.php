<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 21.06.14
 * Time: 14:52
 * To change this template use File | Settings | File Templates.
 */

class TSearcherStandard extends TSearcher{

    /**
     * @var string
     */
    public $bg_color_class;

    /**
     * @var integer
     */
    public $rounding;

    /**
     * @var integer
     */
    public $spinner;

    /**
     * @var string
     */
    public $iframe_id;

    /**
     * @var integer
     */
    public $user_id;


    /**
     * Init
     */
    public function init(){
        if( !$this->bg_color_class ) {
            $this->bg_color_class = 'orange';
        }

        if( !isset($this->spinner) ) {
            $this->spinner = 0;
        }

        if( !isset($this->rounding) ) {
            $this->rounding = 0;
        }

        parent::init();
    }

    /**
     * Run
     */
    public function run() {

        $xColorClass = 'xtourism-' . $this->bg_color_class;
        $xColorResultClass = 'xtourism-results-' . $this->bg_color_class;
        $xRounding = $this->rounding ? 'xtourism-rounded-' . $this->rounding : '';
        $widgetId = 'TClassicID_' . $this->getId();

        ?><div style="min-height:244px; width: 100%;" id="<?php echo $widgetId;?>" class="main-search-form div-as-container" design-color="<?=$this->bg_color_class?>" spinner="<?=$this->spinner?>" rounding="<?=$this->rounding?>">
        <div id="xtourism" class="xtourism <?=$xColorClass . ' ' . $xRounding?> xtourism-medium xtourism-expanded" style="padding: 0!important;"><!--

             --><div class="xtourism-form adaptive-search"><!--

                    /***** Departure cities and countries *****/

                    //-->
                <div class="xtourism-section xtourism-section-direction">
                    <div class="xtourism-subsection t-modDepCities">
                        <?php $this->loadWidget('depCities'); ?>
                    </div>

                    <div class="xtourism-subsection t-modCountries">
                        <?php $this->loadWidget( 'countries'); ?>
                    </div>
                </div><!--
                                /***** Available dates *****/

                 --><div class="xtourism-section xtourism-section-dates">
                    <div class="xtourism-subsection t-modAvailableDates">
                        <?php $this->loadWidget('availableDates'); ?>
                    </div>
                    <div class="xtourism-subsection xtourism-section-nights t-modDurations">
                        <?php $this->loadWidget('durations'); ?>
                    </div>
                </div><!--

                                /***** People *****/

                 --><div class="xtourism-section xtourism-section-people t-modPeople">
                    <?php $this->loadWidget('people'); ?>
                </div><!--

                                /***** Hotel categories *****/
                    //-->
                <div class="xtourism-section xtourism-section-category t-modHotelCategories">
                    <?php $this->loadWidget('hotelCategories');?>
                </div><!--

                                /***** Meals *****/

                 --><div class="xtourism-section xtourism-section-food t-modMeals">
                    <?php $this->loadWidget('meals'); ?>
                </div><!--

                                /***** Price *****/
                    //-->
                <div class="xtourism-section xtourism-section-price t-modPrice t-modExpand">
                    <?php $this->loadWidget('price'); ?>
                </div><!--

                                /***** Currency *****/

                 --><div class="xtourism-section xtourism-section-currency t-modCurrency t-modExpand">
                    <?php $this->loadWidget('currency'); ?>
                </div><!--

                                /***** Resorts *****/
                    //-->
                <div id="xtourism-section-resorts" class="xtourism-section xtourism-section-resorts t-modResorts t-modExpand">
                    <?php $this->loadWidget('resorts'); ?>
                </div><!--

                                /***** Hotels *****/

                 --><div id="xtourism-section-hotels" class="xtourism-section xtourism-section-hotels t-modHotels t-modExpand">
                    <?php $this->loadWidget('hotels'); ?>
                </div><!--

                                /***** Operators *****/

                 --><div id="xtourism-section-operators" class="xtourism-section xtourism-section-operators t-modOperators t-modExpand">
                    <?php $this->loadWidget('operators'); ?>
                </div>


                <div id="xtourism-expand" class="xtourism-expand"><a href="#"></a></div>

                <div class="clearfix">
                    <button type="button" class="xtourism-button" id="buttonSearch">Начать поиск</button>
                </div>

            </div>
        </div>


        <!--------------------------------------------- RESULT --------------------------------------------->
        <div id="xtourism-results" class="xtourism-results <?=$xColorClass . ' ' . $xColorResultClass . ' ' . $xRounding?> xtourism-results-1 xtourism-results-narrow" style="display: none; margin-top: 20px;">
            <div>
                <!-------------------------------   HEAD   ------------------------------->
                <div class="xtourism-results-head">
                    <div class="xtourism-results-progress t-resultProgress">
                        <div style="width: 0%;">0%</div>
                    </div>

                    <div class="xtourism-results-pager pager-block t-toursPager"></div>

                    <div class="xtourism-results-amount t-resultAmount">
                        Найдено: <span class="t-showResult">0</span>
                        <a href="#" class="t-refreshResult" style="display: none;"><i class="fa fa-arrow-down"></i> Вывести все туры</a>
                    </div>
                </div>


                <!-------------------------------   ASIDE   ------------------------------->
                <div class="xtourism-results-aside">

                    <div id="xtourism-section-viewtype">
                        <label class="xtourism-label"><i class="fa fa-bars"></i>Вывод результатов</label>

                        <div class="xtourism-input xtourism-input-switcher xtourism-input-switcher-2 t-viewMode">
                            <input type="radio" name="viewtype" value="0" id="viewtype0"><!--
                             --><label for="viewtype0">По отелям</label><!--
                             --><input type="radio" name="viewtype" value="1" id="viewtype1"><!--
                             --><label for="viewtype1">Списком</label>
                        </div>
                    </div>

                </div>


                <!-------------------------------   MAIN   ------------------------------->
                <div class="xtourism-results-main t-resultMain">
                    <table class="xtourism-results-table"><tbody></tbody></table>
                </div>


                <!-------------------------------   FOOTER   ------------------------------->
                <div class="xtourism-results-foot">
                    <div class="xtourism-results-pager xtourism-results-pager-alt t-toursResultPager">
                        <a href="#" ps="200">200</a>
                        <a href="#" ps="100">100</a>
                        <a href="#" ps="50">50</a>
                        <a href="#" ps="20">20</a>
                        <a href="#" ps="10" class="active">10</a>
                        <span>Выводить по</span>
                    </div>
                    <div class="xtourism-results-pager pager-block t-toursPager"></div>
                </div>

            </div>
        </div>
        </div><?

        Yii::app()->clientScript->registerScript( uniqid(), '

            window.TSearcher("' . $widgetId . '", "' . $this->iframe_id . '", "' . $this->user_id . '");

            ', CClientScript::POS_READY
        );
    }

}