<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 09.05.2015
 * Time: 10:15
 */

?>
<style>
    .i-packages:before, .i-packages:after {
        font-size: 48px;
    }
</style>

<div class="row">

    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
        <a href="<?=Yii::app()->createUrl('Users/index')?>" class="thumbnail" style="text-align: center; text-decoration: none;">
            <i class="fa fa-users" style="width: 100%; height: 80px; font-size: 42px; margin-top: 20px;"></i>
            <p class="lead">Персонал</p>
        </a>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
        <a href="<?=Yii::app()->createUrl('shopProducts/index')?>" class="thumbnail" style="text-align: center; text-decoration: none; padding-top: 14px;">
            <i class="flaticon-delivery-package i-packages" style="font-size: 63px; vertical-align: top;"></i>
            <p class="lead">Продукты и пакеты</p>
        </a>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
        <a href="<?=Yii::app()->createUrl('UserTourShowcase/index')?>" class="thumbnail" style="text-align: center; text-decoration: none; padding-top: 14px;">
            <i class="flaticon-package-cube-box-for-delivery i-packages" style="font-size: 63px; vertical-align: top;"></i>
            <p class="lead">Продукты турагентов</p>
        </a>
    </div>

    <? if( Yii::app()->user->role == ArUsers::ROLE_SUPERADMIN ) {?>
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
            <a href="<?=Yii::app()->createUrl('Migration/index')?>" class="thumbnail" style="text-align: center; text-decoration: none;">
                <span class="fa fa-gears" style="width: 100%; height: 80px; font-size: 42px; margin-top: 20px;"></span>
                <p class="lead">Настройки...</p>
            </a>
        </div>
    <? } ?>

    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
    </div>
</div>