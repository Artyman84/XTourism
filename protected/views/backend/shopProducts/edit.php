<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 21.05.2015
 * Time: 19:41
 *
 * @var CActiveForm $form
 */


$this->breadcrumbs=[
    '<span class="flaticon-package-cube-box-for-delivery"></span> Продукты' => Yii::app()->createUrl('ShopProducts/index'),
    $model->isNewRecord ? '<span class="fa fa-file-o"></span> Создание' : '<span class="fa fa-edit"></span> Редактирование'
];?>

    <fieldset>
        <legend class="text-info">Форма редактирования</legend>
        <? $form = $this->beginWidget('CActiveForm', array(
            'id' => 'product-form',
            'htmlOptions' => array(
                'role' => 'form',
                'method' => 'post',
                'class' => 'form-horizontal'
            ),
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'afterValidate' => 'js:function(form, data, hasError){

                if( !hasError ){
                    $.showFade(); return true;
                } else {
                    return false;
                }
            }',
                'errorCssClass' => 'has-error',
                'successCssClass' => 'has-success'
            ),
        ));?>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="fa fa-sitemap  i-margin"></span>
                <?php echo $form->labelEx($model, 'category_id'); ?>
            </div>
            <div class="col-sm-6">
                <? if( $model->getIsNewRecord() ) { ?>
                    <?php echo $form->dropDownList($model, 'category_id', CHtml::listData(ArShopCategories::model()->findAll(), 'id', 'name'), array('class' => 'form-control', 'empty' => '')); ?>
                    <?php echo $form->error($model, 'category_id', array('class' => 'text-danger', 'errorCssClass' => 'has-error')); ?>
                <? } else { ?>
                    <?php echo CHtml::encode($model->category->name); ?>
                <? } ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="fa fa-navicon i-margin"></span>
                <?php echo $form->labelEx($model, 'type_id'); ?>
            </div>
            <div class="col-sm-6">
                <? if( $model->getIsNewRecord() ) { ?>
                    <?php //echo $form->dropDownList($model, 'type_id', CHtml::listData(ArShopProductsTypes::model()->findAll(), 'id', 'name'), array('class' => 'form-control', 'empty' => '')); ?>
                    <?php echo $form->dropDownList($model, 'type_id', [ArShopProductsTypes::PDT_EXTERNAL => 'Внешний продукт'], ['class' => 'form-control', 'empty' => '']); ?>
                    <?php echo $form->error($model, 'type_id', array('class' => 'text-danger', 'errorCssClass' => 'has-error')); ?>
                <? } else { ?>
                    <?php echo ($model->type ? CHtml::encode($model->type->name) : '<span class="text-danger">Такого типа продукта больше не существует</span>'); ?>
                <? } ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="glyphicon glyphicon-tag i-margin"></span>
                <?php echo $form->labelEx($model, 'name'); ?>
            </div>
            <div class="col-sm-6">
                <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'name', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="glyphicon glyphicon-list-alt i-margin"></span>
                <?php echo $form->labelEx($model, 'description'); ?>
            </div>
            <div class="col-sm-6">
                <?php echo $form->textArea($model, 'description', array('class' => 'form-control', 'rows' => 5)); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class=" i-margin"><strong style="font-size: 16px;">&#8372;</strong></span>
                <?php echo $form->labelEx($model, 'price_uah'); ?>
            </div>
            <div class="col-sm-6">
                <?php echo $form->textField($model, 'price_uah', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'price_uah', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class=" i-margin"><strong style="font-size: 16px;">₽</strong></span>
                <?php echo $form->labelEx($model, 'price_rub'); ?>
            </div>
            <div class="col-sm-6">
                <?php echo $form->textField($model, 'price_rub', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'price_rub', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="fa fa-check i-margin"></span>
                <span class=""><strong><?=$model->getAttributeLabel('published')?></strong></span>
            </div>
            <div class="col-sm-6">
                <div class="xtourism-checkbox">
                    <?php echo $form->checkBox($model, 'published', array('class' => 'form-control')); ?>
                    <span class="glyphicon glyphicon-<?=$model->published ? 'check text-info' : 'unchecked'?>"></span>
                </div>
            </div>
        </div>


        <hr/>


        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <div class="form-actions">
                    <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
                    <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?php echo Yii::app()->createUrl('ShopProducts/index');?>'; return false;">Отмена</button>
                </div>
            </div>
        </div>

        <?php $this->endWidget();?>
    </fieldset>