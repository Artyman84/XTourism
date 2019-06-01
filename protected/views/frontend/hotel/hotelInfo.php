<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 10.03.15
 * Time: 12:05
 * To change this template use File | Settings | File Templates.
 *
 * @var ArDirHotels $hotel
 * @var CActiveForm $form
 */

$this->pageTitle = CHtml::encode(Yii::app()->name . ' - ' . $hotel->name);
$images = $hotel->images();
?>

    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container tour-info">

            <div class="nav-justified">
                <h2 class="tour-info-header">
                    <span><?=htmlspecialchars($hotel->name)?></span>
                    <?=TSearch\TourHelper::hCategoryInStars($hotel->category->name)?>
                </h2>
            </div>

        </div>
    </nav>


    <div class="panel panel-default margin-bottom45" style="margin-top: 120px;">
        <div class="panel-heading">
            <h3 class="panel-title"><?=$hotel->locationPath()?></h3>
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
                        <? $this->widget('HPhotos', ['hotel' => $hotel]); ?>
                    </div>
                <? } ?>

                <div class="<?=(!empty($images) ? 'col-md-6 col-sm-6 col-xs-6' : 'col-md-12 col-sm-12 col-xs-12')?>">
                    <p class="text-justify" style="margin-left: 10px;"><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;' . nl2br($hotel->description);?></p>
                </div>
            </div>

        </div>
    </div>

    <? if( !empty($uid) ) {
        Yii::import('tsearch.hotel.HSearcher.*');
        $this->widget('HSearcher', ['hotel_id' => $hotel->id, 'user_id' => $uid]);
    }?>


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

    <? $this->widget('HComplaint', ['hotel_hash_id' => Yii::app()->request->getParam('hId')]); ?>

<?php Yii::app()->clientScript->registerScript(
    "hotel",
    '$(function(){            
            $("body").tooltip({selector: "[data-toggle=tooltip]"});
    });',
    CClientScript::POS_READY
);