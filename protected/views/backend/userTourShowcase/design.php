<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 01.10.2017
 * Time: 8:48
 */

$this->breadcrumbs = [
    '<span class="glyphicon glyphicon-th"></span> Витрины туров' => Yii::app()->createUrl('UserTourShowcase/index'),
    '<span class="fa fa-user"></span> ' . ArUsers::model()->findByPk($model->user_id)->userName() . ' - Внешний вид витрины'
];

$this->showFlashMessage('showcase_settings');

$this->renderPartial('common_views.tour_showcase.' . $model->type . '_settings', [
    'model' => $model,
    'message' => Yii::app()->user->getFlash('showcase_settings'),
    'settings' => $model->showcaseSettings(),
    'defaultSettings' => ArUserTourShowcase::defaultSettings($model->type),
    'user_id' => $model->user_id
]);?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        <div class="form-actions">
            <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?=Yii::app()->createUrl('UserTourShowcase/Index')?>'; return false;">Отмена</button>
        </div>
    </div>
</div>

