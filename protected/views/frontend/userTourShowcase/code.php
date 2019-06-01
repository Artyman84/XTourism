<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 10.03.2016
 * Time: 14:03
 * @var \TSearch\Searcher $searcher
 * @var SearcherStandardSettings $settings
 */

$package = Yii::app()->user->package; ?>

<div class="panel panel-default" style="padding: 35px;" id="searcher-default-values-settings">

    <? if( $package->hasProduct(ArShopProductsTypes::PDT_TOUR_SHOWCASE) ) {?>
        <div class="row">
            <div class="col-md-12 text-center">
                <p class="help-block">Скопируйте и вставьте код на Ваш сайт там, где должна располагаться витрина туров.</p>
            </div>

            <div class="col-md-12">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="control-label" style="cursor: pointer;" for="showcase_all_tours">Витрина туров</label>
                    <input type="text" style="cursor: pointer; font-weight: bold; font-size: 12px;" readonly class="form-control t-showcase-select-code" id="showcase_all_tours" value="<?=htmlspecialchars('<script src=\'' . Yii::app()->request->hostInfo . Yii::app()->baseUrl . '/js/front_product/_.js?p=' . TUtil::base64url_encode(TUtil::encrypt(CJSON::encode(['uid' => Yii::app()->user->id, 'p' => 'tourShowcase']))) . '\'></script>')?>">
                </div>
            </div>
        </div>
    <? } ?>
</div>

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        $("body").on("click", ".t-showcase-select-code", function(){
            $(this).select();
        });
    });
    /*]]>*/
</script>