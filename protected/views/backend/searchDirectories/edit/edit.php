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
    '<span class="fa fa-book"></span> ' . ArDirectorySearch::getTableName($view) => Yii::app()->createUrl('SearchDirectories/index', ['view' => $view]),
    $model->isNewRecord ? '<span class="fa fa-file-o"></span> Создание' : '<span class="fa fa-edit"></span> Редактирование'
];?>

<fieldset>
    <legend class="text-info">Форма редактирования</legend>
    <? $form = $this->beginWidget('CActiveForm', array(
        'id' => 'showcase-form',
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
        <div class="col-sm-2 text-left">
            <span class="glyphicon glyphicon-tag text-muted"></span>
            <?php echo $form->labelEx($model, 'name', array('class' => 'text-muted')); ?>
        </div>
        <div class="col-sm-5">
            <? if( $view == 'countries' ) {?>
                <?=CHtml::encode($model->name)?>
            <?} else {?>
                <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'name', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            <? } ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 text-left">
            <span class="glyphicon glyphicon-list-alt text-muted"></span>
            <?php echo $form->labelEx($model, 'description', array('class' => 'text-muted')); ?>
        </div>
        <div class="col-sm-5">
            <?php echo $form->textArea($model, 'description', array('class' => 'form-control', 'rows' => 5)); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 text-left">
            <span class="glyphicon glyphicon-screenshot text-muted"></span>
            <?php echo $form->labelEx($model, 'position', array('class' => 'text-muted')); ?>
        </div>
        <div class="col-sm-5">
            <?php echo $form->textField($model, 'position', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'position', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 text-left">
            <span class="glyphicon glyphicon-thumbs-up text-muted"></span>
            <?php echo $form->labelEx($model, 'rating', array('class' => 'text-muted')); ?>
        </div>
        <div class="col-sm-5">
            <?php echo $form->textField($model, 'rating', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'rating', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 text-left">
            <span class="glyphicon glyphicon-ban-circle text-muted"></span>
            <span class="text-muted"><strong><?=$model->getAttributeLabel('disabled')?></strong></span>
        </div>
        <div class="col-sm-5">
            <div class="xtourism-checkbox">
                <?php echo $form->checkBox($model, 'disabled', array('class' => 'form-control')); ?>
                <span class="glyphicon glyphicon-<?=$model->disabled ? 'check text-info' : 'unchecked'?>"></span>
            </div>
        </div>
    </div>

    <hr/>

    <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
    <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?php echo Yii::app()->createUrl('SearchDirectories/index', ['view' => $view]);?>'; return false;">Отмена</button>

    <?php $this->endWidget();?>
</fieldset>