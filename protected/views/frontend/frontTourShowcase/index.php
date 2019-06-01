<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 22.04.2015
 * Time: 21:34
 */

?>

<div style="background-color: <?=($settings['bg_color'])?>; border-radius: <?=$settings['rounding'] ?>px;" id="TourShowcaseStandardSettings_bg_color" class="showcase-main">

    <div class="row t-tours-filter" style="padding: 20px 20px 0 20px;">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group-sm">
            <label for="shw-country" class="small">Страна</label>
            <select class="form-control t-shw-country" id="shw-country">
                <option value="0"></option>
                <? foreach ($countries as $country) {?>
                    <option <?=$settings['country'] == $country->id ? 'selected="selected"' : ''?> value="<?=$country->id?>"><?=CHtml::encode($country->name)?></option>
                <? } ?>
            </select>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group-sm">
            <label for="shw-resort" class="small">Курорт</label>
            <select class="form-control t-shw-resort" id="shw-resort">
                <option value="0"></option>
                <? foreach ($resorts as $resort) {?>
                       <option <?=$settings['resort'] == $resort->id ? 'selected="selected"' : ''?> value="<?=$resort->id?>"><?=CHtml::encode($resort->name)?></option>
                <? } ?>
            </select>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group-sm">
            <label for="shw-category" class="small">Категория отеля</label>
            <select class="form-control t-shw-category" id="shw-category">
                <option value="0"></option>
                <? foreach ($categories as $category) {?>
                    <option <?=$settings['category'] == $category->id ? 'selected="selected"' : ''?> value="<?=$category->id?>"><?=TSearch\TourHelper::normalizeHotelCategory($category->name)?></option>
                <? } ?>
            </select>
        </div>
    </div>

    <div class="row" style="padding: 20px 20px 0 20px;">
        <div class="t-tours-content">
            <?php if( empty($tours) ) {
                ?><div class="col-xs-12"><p class="text-center lead" style="opacity: 0.6;">Не найдено туров с данными параметрами.</p><hr></div><?
            } else {
                $this->renderTours($tours, $uid, $settings);
            } ?>
        </div>

        <?php if($totalCount > $count && $count) {?>
            <div class="clearfix"></div>
            <div class="col-md-12 text-center">
                <div class="load-showcase-tours">
                    <button type="button" id="t_loading_tours" data-loading-text="Загружаем туры..." class="btn btn-default t-pagination-button" page="2" style="background-color: <?=$settings['pagination_color']?>;">Загрузить больше туров</button>
                </div>

            </div>
        <? } ?>
        <input type="hidden" id="showcase-params" value="">
    </div>

</div>

<?php Yii::app()->clientScript->registerScript("showcase_settings",
    'window.TSHWCS(' . (int)$uid . ', "' . $iframe_id . '", ' . $totalCount . ')',
    CClientScript::POS_READY
);
