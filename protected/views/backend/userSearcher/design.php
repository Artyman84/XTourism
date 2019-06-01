<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 01.10.2017
 * Time: 8:48
 */

$this->breadcrumbs = [
    '<span class="glyphicon glyphicon-search"></span> Поисковики турагентов' => Yii::app()->createUrl('UserSearcher/index'),
    '<span class="fa fa-user"></span> ' . ArUsers::model()->findByPk($model->user_id)->userName() . ' - Настройка поисковика'
];

$this->showFlashMessage('searcher_design');

$this->renderPartial('common_views.searcher.' . $model->type . '_settings', ['model' => $model, 'user_id' => $user_id, ]);?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        <div class="form-actions">
            <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?=Yii::app()->createUrl('UserSearcher/Index')?>'; return false;">Отмена</button>
        </div>
    </div>
</div>

