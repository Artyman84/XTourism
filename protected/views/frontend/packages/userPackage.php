<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 24.04.2016
 * Time: 9:45
 *
 * @var $userPackage ArShopUsersPackages
 */

$this->breadcrumbs=array(
    '<span class="flaticon-delivery-package-opened"></span> Мой пакет'
);

if( !$userPackage ) {

    ?><div class="alert alert-warning text-center" style="font-weight: bold;">
        У Вас нет купленного пакета.
    </div><?

} else {

    $invoice = CJSON::decode($userInvoice->invoice);
    $agent = ArUsers::model()->findByPk($userInvoice->user_id);?>

    <? if( !$userPackage->isValid() ) {?>

        <div class="alert alert-danger text-center"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Внимание!</strong> Срок пользования вашего пакета истек. Для активации пакета необходимо внести оплатиту, либо выбрать другой пакет.
        </div>

    <? } elseif( $userPackage->expired <= strtotime('+ 3 Days') ) {?>

        <div class="alert alert-warning text-center"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Внимание!</strong> Истекает срок пользования вашего пакета. Для активации пакета необходимо внести оплатиту, либо выбрать другой пакет.
        </div>

    <? } ?>

    <? $this->addCssFile('print', 'webroot.css', 'print'); ?>
    <div class="t-print-area">

        <p class="lead text-info">Информация о пакете</p>
        <div class="form-group row">
            <div class="col-sm-3 text-left" >
                <strong>Название пакета:</strong>
            </div>
            <div class="col-sm-4"><?=CHtml::encode($userPackage->name)?></div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 text-left" >
                <strong>Дата начала:</strong>
            </div>
            <div class="col-sm-4"><?=$userPackage->start != '' ? Yii::app()->dateFormatter->format('dd.MM.yyyy', $userPackage->start) : ''?></div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 text-left" >
                <strong>Действителен до:</strong>
            </div>
            <div class="col-sm-4"><?=$userPackage->expired != '' ? Yii::app()->dateFormatter->format('dd.MM.yyyy', $userPackage->expired) : ''?></div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 text-left" >
                <strong>Цена в гривнах:</strong>
            </div>
            <div class="col-sm-4"><?=$invoice['price_uah']?>&nbsp;&#8372;</div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 text-left" >
                <strong>Цена в рублях:</strong>
            </div>
            <div class="col-sm-4"><?=$invoice['price_rub']?>&nbsp;₽</div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 text-left" >
                <strong>Продукты:</strong>
            </div>
            <div class="col-sm-4"><?
                $_products = $invoice['products'];
                $products = '<ul style="padding-left: 15px;">';
                foreach( $_products as $product ) {
                    $products .= '<li>' . CHtml::encode($product) . '</li>';
                }
                $products .= '</ul>';
                echo $products;?>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 text-left" >
                <strong>Время оплаты:</strong>
            </div>
            <div class="col-sm-4"><?=Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm:ss', $userInvoice->created_at)?></div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <div class="form-actions">
                <button type="button" class="btn btn-primary" onclick="window.print();"><span class="glyphicon glyphicon-print"></span> Печать</button>
            </div>
        </div>
    </div>

<? } ?>
