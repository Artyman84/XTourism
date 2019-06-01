<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 26.04.2015
 * Time: 9:15
 *
 * @var CActiveForm $form
 * @var array $hotel
 * @var array $images
 */

$hotel = ArDirHotels::model()->with(['services', 'residence', 'photos', 'cards', 'ratings', 'category', 'resort' => ['with' => 'country']])->findByPk($data['hid']);
$city = ArDirDepCities::model()->findByPk($data['cid']);
$meal = ArDirMeals::model()->findByPk($data['mid']);
$operator = ArOperators::model()->findByPk($data['oid']);

if( !$hotel || !$city || !$meal || !$operator ){
    $this->badTourInfo();
}

$images = $hotel->images();
$this->pageTitle = CHtml::encode(Yii::app()->name . ' - ' . CHtml::encode($hotel->name)); ?>


    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container tour-info">
            <div class="nav-justified">
                <h2 class="tour-info-header">
                    <span><?=CHtml::encode($hotel->name)?></span>
                    <?=TSearch\TourHelper::hCategoryInStars($hotel->category->name)?>
                </h2>
            </div>

        </div><!-- /.container-fluid -->
    </nav>

    <div class="panel panel-default margin-bottom45" style="margin-top: 120px;">

        <div class="panel-heading">
            <h3 class="panel-title">
                <?=$hotel->locationPath()?>
            </h3>
        </div>

        <div class="panel-body">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>
                        <addres>
                            <?=CHtml::encode($hotel->address)?>
                            <? $googleUrl = $hotel->coords2googleUrl();
                            if($googleUrl) { ?>
                                <? $this->decorator('google_map_modal', $googleUrl, ['address' => CHtml::encode($hotel->address)]); ?>
                                <a href="javascript://" data-toggle="tooltip" data-placement="top" title="Месторасположение на карте" target="_blank"><span data-toggle="modal" data-target="#modalGoogleMap" class="glyphicon glyphicon-map-marker"></span></a><?php
                            }?>
                        </addres>
                    </h4>
                </div>
            </div>

            <div class="row">

                <? if( !empty($images) ) {?>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <? $this->widget('HPhotos', ['hotel' => $hotel, 'price' => $data['prc'], 'currency' => $data['cur']]); ?>
                    </div>
                <? } ?>

                <div class="<?=(!empty($images) ? 'col-md-6 col-sm-6 col-xs-6' : 'col-md-12 col-sm-12 col-xs-12')?>" id="tour-info">

                    <table class="table table-striped">
                        <tbody id="main-tour-info">
                        <tr id="tour-operator">
                            <td class="text-info text-nowrap" ><i class="flaticon-call-center-worker-with-headset"></i> Туроператор</td>
                            <td><?=CHtml::image(ArOperators::imgPath($operator->class), $operator->name, ['height' => 25, 'width' => 30, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => $operator->name])?></td>
                        </tr>
                        <tr id="tour-dep-city">
                            <td class="text-info text-nowrap" ><i class="fa fa-plane" style="font-size: 18px; margin-right: 3px;"></i> Город вылета</td>
                            <td><?=CHtml::encode($city->name)?></td>
                        </tr>
                        <tr id="tour-start-date">
                            <td class="text-info text-nowrap" ><span class="glyphicon glyphicon-calendar i-margin"></span> Дата начала</td>
                            <td><span class="text-success" style="font-weight: bold;"><?=\TSearch\TourHelper::formatDate2($data['date'])?></span></td>
                        </tr>
                        <tr id="tour-nights">
                            <td class="text-info text-nowrap" ><span class="glyphicon glyphicon-time i-margin"></span> Ночей</td>
                            <td><span class="text-success" style="font-weight: bold;"><?=$data['ngt']?></span></td>
                        </tr>
                        <tr id="tour-meal">
                            <td class="text-info text-nowrap" ><span class="glyphicon glyphicon-cutlery i-margin"></span> Питание</td>
                            <td><strong><?=CHtml::encode($meal->name)?></strong>&nbsp;&nbsp;<?=CHtml::encode($meal->description)?></td>
                        </tr>
                        <tr id="tour-residence">
                            <td class="text-info text-nowrap" ><i class="glyphicon glyphicon-bed i-margin"></i> Размещение</td>
                            <td><?echo TSearch\TourHelper::getResidence($data['ad'], $data['ch'], true) . ' ' . $data['rm'];?></td>
                        </tr>
                        <tr id="tour-price">
                            <td colspan="2"><span class="lead"><?=TSearch\TourHelper::normalizePrice($data['prc']) . ' ' . TSearch\TourHelper::htmlCurrency($data['cur'])?></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <br>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <p class="text-justify hotel-description"><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $hotel->description;?></p>
                </div>
            </div>

        </div>
    </div>

    <div class="panel panel-primary margin-bottom45">
        <div class="panel-heading">
            <h3 class="panel-title">
                Отправить заявку
            </h3>
        </div>

        <div class="panel-body">

            <? $this->renderPartial('common_views.tour_request.request_form', [
                'request_func' => 'js:function(form, data, hasError){
                    if( !hasError ){
                        var $f = form;
                        $.sendRequest( {"url": form.attr("action")}, form.serialize(), function(r){
                            if( r == "expired" || r == "" ){
                                $(document.body).html("<div class=\'alert alert-warning text-center\'><strong><span class=\'glyphicon glyphicon-warning-sign\'></span> Поиск туров временно недоступен</strong></div>")
                                return false;
                            }

                            if( r == "bad_params" ){
                                $(document.body).html("<div class=\'alert alert-warning text-center\'><strong><span class=\'glyphicon glyphicon-warning-sign\'></span> Информация о данном туре временно недоступна.</strong></div>")
                                return false;
                            }

                            r = JSON.parse(r);
                            $f.remove();
                            $("#simpleTourRequest #requestMessageContainer").html(r.message).show().find(".alert").css({"margin": 0});
                        }, "HTML");
                    }
                }'
            ]); ?>
        </div>
    </div>
    <? Yii::import('tsearch.hotel.HSearcher.*'); ?>
    <? $this->widget('HSearcher', ['hotel_id' => $hotel->id, 'user_id' => $uid]); ?>

<? if( !empty($hotel->services) ) {?>
    <div class="panel panel-default margin-bottom45">
        <div class="panel-heading">
            <h3 class="panel-title">
                Услуги/Ориентиры
            </h3>
        </div>

        <div class="panel-body">
            <? $this->widget('HServicesList', ['services' => $hotel->services]); ?>
        </div>
    </div>
<? } ?>


<? if( !empty($hotel->residence) ) {?>
    <div class="panel panel-default margin-bottom45">
        <div class="panel-heading">
            <h3 class="panel-title">
                Дополнительно
            </h3>
        </div>

        <div class="panel-body">
            <? $this->widget('HResidenceList', ['residence' => $hotel->residence, 'cards' => $hotel->cards]); ?>
        </div>
    </div>

<? } ?>

    <? if( !empty($hotel->ratings) ) {?>
        <div class="panel panel-default margin-bottom45">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Оценки
                </h3>
            </div>

            <div class="panel-body">
                <? $this->widget('HRatingsList', ['ratings' => $hotel->ratings]); ?>
            </div>
        </div>
    <? } ?>

    <? if( empty($hotel->services) && empty($hotel->residence) && empty($hotel->ratings) ) {?>
        <div class="alert alert-warning text-center" style="font-weight: bold;">
            Информация об отеле обновляется.
        </div>
    <? } ?>


    <? $this->widget('HComplaint', ['hotel_hash_id' => TUtil::encode_hotel_id($hotel->id)]); ?>

<?php

// uncomment for get more info about tour(ex.: - airport schedule, etc.)

//$this->addJsFile('tourInfo');
Yii::app()->clientScript->registerScript(
    "trequest",
    '$("body").tooltip({selector: "[data-toggle=tooltip]"});',

//    $(function(){window.tour_request(' . CJavaScript::jsonEncode([
//        'bg_color_class' => $settings->bg_color_class,
//        'spinner' => $settings->spinner,
//        'uid' => $uid,
//        'request_params' => $request_params
//    ]) . ');})',
    CClientScript::POS_READY
);