<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 10.03.2016
 * Time: 14:03
 * @var \TSearch\Searcher $searcher
 * @var SearcherStandardSettings $settings
 */

$settings = $model->searcherSettings();
$searcher = new \TSearch\Searcher($settings);

$wPath = 'tsearch.searchers.widgets';
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tsearch/form.css" />

<div class="panel panel-default" style="padding: 35px 0 35px 0;" id="searcher-default-values-settings">

    <?php $param = isset($user_id) ? ['user_id' => $user_id] : []; ?>
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => $this->createUrl('UserSearcher/saveDefaultValues', $param),
        'htmlOptions' => array(
            'role' => 'form',
            'method' => 'post',
        )
    ));?>

    <div class="row"><div class="col-12-md text-center" style="padding: 0 59px 0 59px; margin-bottom: 20px;"><p class="help-block">Эти значения будут настроены в поисковике сразу после его загрузки</p><hr></div></div>

    <div id="xtourism" class="xtourism xtourism-rounded-3" style="padding: 0 0 0 45px;">

        <div class="row">

            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="xtourism-subsection t-modDepCities" style="padding: 0 45px 0 0;">
                    <? $dep_cities = $searcher->depCities(true); ?>
                    <? $this->widget($wPath . '.TSearcherDepCities.TSearcherDepCities', ['data' => $dep_cities, 'selectedId' => $settings->depCity]); ?>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="xtourism-subsection t-modCountries" style="padding: 0 45px 0 0;">
                    <? $this->widget($wPath . '.TSearcherCountries.TSearcherCountries', ['data' => $searcher->countries($settings->depCity ? $settings->depCity : key($dep_cities), true), 'selectedId' => $settings->country]); ?>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="xtourism-section xtourism-section-category t-modHotelCategories" style="padding: 0 45px 0 0;">
                    <? $this->widget($wPath . '.TSearcherHotelCategories.TSearcherHotelCategories', ['data' => $searcher->hotelCategories(), 'selectedId' => $settings->hotelCategory, 'more' => $settings->hotelCategoryMore]); ?>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="xtourism-subsection xtourism-section-nights t-modDurations xtourism-section-dates" style="padding: 0 45px 0 0;">
                    <? $this->widget($wPath . '.TSearcherDurations.TSearcherDurations', ['selectedNightFrom' => $settings->nightFrom, 'selectedNightTo' => $settings->nightTo]); ?>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="xtourism-section xtourism-section-price t-modPrice t-modExpand" style="padding: 0 45px 0 0;">
                    <? $this->widget($wPath . '.TSearcherPrice.TSearcherPrice', ['minPrice' => $settings->minPrice, 'maxPrice' => $settings->maxPrice]); ?>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="xtourism-section xtourism-section-currency t-modCurrency t-modExpand" style="padding: 0 45px 0 0;">
                    <? $this->widget($wPath . '.TSearcherCurrency.TSearcherCurrency', ['isCU' => $settings->currency]); ?>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="xtourism-section xtourism-section-people t-modPeople" style="padding: 0 45px 0 0;">
                    <? $this->widget($wPath . '.TSearcherPeople.TSearcherPeople', ['selAdults' => $settings->adults, 'selChildren' => $settings->children, 'selChild1' => $settings->child1, 'selChild2' => $settings->child2, 'selChild3' => $settings->child3]); ?>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="xtourism-section xtourism-section-food t-modMeals" style="padding: 0 45px 0 0;">
                    <? $this->widget($wPath . '.TSearcherMeals.TSearcherMeals', ['data' => $searcher->meals(), 'selectedId' => $settings->meals, 'more' => $settings->mealsMore]); ?>
                </div>
            </div>

        </div>

    </div>


    <div class="row">
        <div class="col-md-12" style="padding: 0 59px 0 59px;">
            <hr>
            <button class="btn btn-primary btn-sm" type="submit" onclick="$.showFade();"><span class="glyphicon glyphicon-save"></span> Сохранить изменения</button>
        </div>
    </div>

    <?php $this->endWidget(); ?>

    <script type="text/javascript">
        /*<![CDATA[*/
        (function($){
            var cdc_relations = <?=$searcher->cdcRelations(true)?>;

            $(function(){
                $("#depCity").change(function(){
                    var options = [];
                    var dep_city = "," + $(this).val() + ",";
                    var country = $("#country").val();
                    var j = 0;

                    for(var i= 0,l=cdc_relations.length; i<l; ++i){
                        if( cdc_relations[i].cities.indexOf(dep_city) != -1 ) {
                            var selected = country == cdc_relations[i].id ? 'selected="selected"' : '';
                            options[j++] = '<option value="' + cdc_relations[i].id + '" ' + selected + '>' + $.escapeHtml(cdc_relations[i].name) + '</option>';
                        }
                    }

                    $("#country").get(0).innerHTML = options.join("");
                })
            })
        })(jQuery);
        /*]]>*/
    </script>

</div>