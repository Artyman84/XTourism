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
    '<span class="flaticon-call-center-worker-with-headset"></span> Операторы туров' => Yii::app()->createUrl('SearchTourOperators/index'),
    $model->isNewRecord ? '<span class="fa fa-file-o"></span> Создание оператора' : ' <span class="fa fa-edit"></span>Редактирование оператора'
];?>

<fieldset>
    <legend class="text-info">Оператор туров</legend>
    <? $form = $this->beginWidget('CActiveForm', array(
        'id' => 'operator-form',
        'htmlOptions' => array(
            'role' => 'form',
            'method' => 'post',
            'class' => 'form-horizontal'
        ),
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'afterValidate' => 'js:function(form, data, hasError){ if( !hasError ){ $.showFade(); return true } else {return false;} }',
            'errorCssClass' => 'has-error',
            'successCssClass' => 'has-success'
        ),
    ));?>

    <div class="form-group">
        <div class="col-sm-3 text-left">
            <span class="glyphicon glyphicon-tag"></span>
            <?php echo $form->labelEx($model, 'name'); ?>
        </div>
        <div class="col-sm-8">
            <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'name', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-3 text-left">
            <span class="glyphicon glyphicon-copyright-mark"></span>
            <?php echo $form->labelEx($model, 'class'); ?>
        </div>
        <div class="col-sm-8">
            <?php echo $form->textField($model, 'class', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'class', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-3 text-left">
            <span class="glyphicon glyphicon-link"></span>
            <?php echo $form->labelEx($model, 'url'); ?>
        </div>
        <div class="col-sm-8">
            <?php echo $form->textField($model, 'url', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'url', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-3 text-left">
            <span class="glyphicon glyphicon-ban-circle"></span>
            <?php echo $form->labelEx($model, 'blocked'); ?>
        </div>
        <div class="col-sm-8">
            <div class="xtourism-checkbox">
                <?php echo $form->checkBox($model, 'blocked', array('class' => 'form-control')); ?>
                <span class="glyphicon glyphicon-<?=$model->blocked ? 'check text-info' : 'unchecked'?>"></span>
            </div>
        </div>
    </div>


    <hr/>

    <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
    <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?php echo Yii::app()->createUrl('SearchTourOperators/index');?>'; return false;">Отмена</button>

    <?php $this->endWidget();?>
</fieldset>