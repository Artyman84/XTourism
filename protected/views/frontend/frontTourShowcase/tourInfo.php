<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 26.04.2015
 * Time: 9:15
 *
 * @var CActiveForm $form
 */

$hotel = $tour->hotel;
$images = $hotel->images();

$currency = $tour->currency;
$price = $tour->price;
if( $showcase_currency ){
    $currency = 'RUB';
    $price = $tour->price_rur;
}

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
                        <?=CHtml::encode($hotel->address)?>
                        <? $googleUrl = $hotel->coords2googleUrl();
                        if($googleUrl) { ?>
                            <? $this->decorator('google_map_modal', $googleUrl, ['address' => CHtml::encode($hotel->address)]); ?>
                            <a href="javascript://" data-toggle="tooltip" data-placement="top" title="Месторасположение на карте" target="_blank"><span data-toggle="modal" data-target="#modalGoogleMap" class="glyphicon glyphicon-map-marker"></span></a><?php
                        }?>
                    </h4>
                </div>
            </div>

            <div class="row">

                <? if( !empty($images) ) {?>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <? $this->widget('HPhotos', ['hotel' => $hotel, 'price' => $price, 'currency' => $currency]); ?>
                    </div>
                <? } ?>

                <div class="<?=(!empty($images) ? 'col-md-6 col-sm-6 col-xs-6' : 'col-md-12 col-sm-12 col-xs-12')?>" id="tour-info">

                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td class="text-info text-nowrap"><i class="flaticon-call-center-worker-with-headset"></i> Туроператор</td>
                                <td><?=CHtml::image(ArOperators::imgPath($tour->operator->class), $tour->operator->name, ['height' => 25, 'width' => 30, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => $tour->operator->name])?></td>
                            </tr>

                            <tr>
                                <td class="text-info text-nowrap" ><i class="fa fa-plane" style="font-size: 18px; margin-right: 3px;"></i> Город вылета</td>
                                <td><?=CHtml::encode($tour->city->name)?></td>
                            </tr>

                            <tr>
                                <td class="text-info text-nowrap"><span class="glyphicon glyphicon-calendar i-margin"></span> <?=$tour->getAttributeLabel('start_date')?></td>
                                <td><span class="text-success" style="font-weight: bold;"><?=\TSearch\TourHelper::formatDate2($tour->start_date)?></span></td>
                            </tr>

                            <tr>
                                <td class="text-info text-nowrap"><span class="glyphicon glyphicon-time i-margin"></span> <?=$tour->getAttributeLabel('nights')?></td>
                                <td><span class="text-success" style="font-weight: bold;"><?=$tour->nights?></span></td>
                            </tr>

                            <tr>
                                <td class="text-info text-nowrap"><span class="glyphicon glyphicon-cutlery i-margin"></span> <?=$tour->getAttributeLabel('m_dir_id')?></td>
                                <td><strong><?=CHtml::encode($tour->meal->name)?></strong>&nbsp;&nbsp;<?=CHtml::encode($tour->meal->description)?></td>
                            </tr>
                            <tr>
                                <td class="text-info text-nowrap"><i class="glyphicon glyphicon-bed i-margin"></i> Размещение</td>
                                <td><?echo TSearch\TourHelper::getResidence($tour->adults, $tour->kids, true) . ' ' . $tour->room;?></td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="lead"><?=TSearch\TourHelper::normalizePrice($price) . ' ' . TSearch\TourHelper::htmlCurrency($currency)?></span></td>
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

    <div class="panel panel-primary margin-bottom45" >
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

                                if( r == "expired" || r == "" ) {
                                    $(document.body).html("<div class=\'alert alert-warning text-center\'><span class=\'glyphicon glyphicon-warning-sign\'></span> Витрина туров временно недоступна</div>")
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


Yii::app()->clientScript->registerScript(
    "hotel",
    ';$(function(){
        $("body").tooltip({selector: "[data-toggle=tooltip]"});
    });',
    CClientScript::POS_READY
);