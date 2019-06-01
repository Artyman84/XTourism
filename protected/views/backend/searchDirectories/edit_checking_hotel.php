<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 21.05.2015
 * Time: 19:41
 *
 * @var CActiveForm $form
 */

$this->breadcrumbs=array(
    '<span class="fa fa-star"></span> Проверка звезд отелей' => Yii::app()->createUrl('SearchDirectories/checkingCategories'),
    '<span class="fa fa-star-o"></span> Редактирование категории отеля'
);?>

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
            <i class="fa fa-building text-muted"></i>
            <?php echo $form->labelEx($model, 'hotel_name', array('class' => 'text-muted')); ?>
        </div>
        <div class="col-sm-5">
            <?php echo CHtml::link(CHtml::encode($model->hotel->name), Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/index.php/Hotel/hotelInfo/?hId=' . TUtil::encode_hotel_id($model->hotel->id), ['target' => '_blank']); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 text-left">
            <span class="glyphicon glyphicon-star text-muted"></span>
            <?php echo $form->labelEx($model, 'category_id', array('class' => 'text-muted')); ?>
        </div>
        <div class="col-sm-5">
            <?php echo $form->dropDownList($model, 'category_id', $categories, array('class' => 'form-control', 'value' => $model->hotel->dir_category_id)); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 text-left">
            <span class="glyphicon glyphicon-ban-circle text-muted"></span>
            <span class="text-muted"><strong><?=$model->getAttributeLabel('checked')?></strong></span>
        </div>
        <div class="col-sm-5 btn-group" data-toggle="buttons">

            <label class="btn btn-xs btn-success <?=($model->checked ? 'active"' : '')?>">
                <input type="radio" name="<?=CHtml::activeName($model, 'checked')?>" value="1" <?=($model->checked ? 'checked="checked"' : '')?>>
                <span class="glyphicon glyphicon-ok-sign"></span> Проверен
            </label>
            <label class="btn btn-xs btn-danger <?=(!$model->checked ? 'active"' : '')?>">
                <input type="radio" name="<?=CHtml::activeName($model, 'checked')?>" value="0" <?=(!$model->checked ? 'checked="checked"' : '')?>>
                <span class="glyphicon glyphicon-minus-sign"></span> Не проверен
            </label>

        </div>
    </div>

    <hr/>

    <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
    <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?php echo Yii::app()->createUrl('SearchDirectories/checkingCategories');?>'; return false;">Отмена</button>

    <?php $this->endWidget();?>
</fieldset>