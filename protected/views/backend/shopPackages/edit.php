<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 21.05.2015
 * Time: 19:41
 *
 * @var CActiveForm $form
 * @var ArShopPackages $model
 */


$this->breadcrumbs=[
    '<span class="flaticon-delivery-package-opened"></span> Пакеты' => Yii::app()->createUrl('ShopPackages/index'),
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
                    var isValidMSelect = window.ms_tables.validateMsTables();

                    if( !hasError && isValidMSelect ){
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
                <span class="glyphicon glyphicon-tag i-margin"></span>
                <?php echo $form->labelEx($model, 'name'); ?>
            </div>
            <div class="col-sm-8">
                <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'name', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <i class="flaticon-package-cube-box-for-delivery"></i>
                <?php echo CHtml::label('Продукты', ''); ?>
            </div>
            <div class="col-sm-8">
                <? $this->widget('widgets.ms_tables.MultiSelectTables', [
                    'selectedElements' => CHtml::listData($model->products, 'id', 'name'),
                    'allElements' => CHtml::listData(ArShopProducts::model()->findAll('published = 1'), 'id', 'name'),
                    'hidden_name' => 'ArShopPackages[products][]',
                    'enable_validation' => true,
                    'error_message' => 'Необходимо выбрать продукты для пакета.'
                ]);?>
            </div>
        </div>


        <div class="form-group">
            <? $periods = ArShopPackages::periods(); ?>
            <div class="col-sm-3 text-left">
                <span class="glyphicon glyphicon-time i-margin"></span>
                <?php echo $form->labelEx($model, 'period'); ?>
            </div>
            <div class="col-sm-8 btn-group" data-toggle="buttons">
                <label class="btn btn-xs btn-primary <?=($model->period == ArShopPackages::PERIOD_1_MONTH ? 'active"' : '')?>">
                    <input type="radio" name="<?=CHtml::activeName($model, 'period')?>" id="period1" value="<?=ArShopPackages::PERIOD_1_MONTH?>" <?=($model->period == ArShopPackages::PERIOD_1_MONTH ? 'checked="checked"' : '')?>>
                    <?=$periods[ArShopPackages::PERIOD_1_MONTH]?>
                </label>
                <label class="btn btn-xs btn-warning <?=($model->period == ArShopPackages::PERIOD_3_MONTH || $model->getIsNewRecord() ? 'active"' : '')?>">
                    <input type="radio" name="<?=CHtml::activeName($model, 'period')?>" id="period3" value="<?=ArShopPackages::PERIOD_3_MONTH?>" <?=($model->period == ArShopPackages::PERIOD_3_MONTH || $model->getIsNewRecord() ? 'checked="checked"' : '')?>>
                    <?=$periods[ArShopPackages::PERIOD_3_MONTH]?>
                </label>
                <label class="btn btn-xs btn-success <?=($model->period == ArShopPackages::PERIOD_6_MONTH ? 'active"' : '')?>">
                    <input type="radio" name="<?=CHtml::activeName($model, 'period')?>" id="period6" value="<?=ArShopPackages::PERIOD_6_MONTH?>" <?=($model->period == ArShopPackages::PERIOD_6_MONTH ? 'checked="checked"' : '')?>>
                    <?=$periods[ArShopPackages::PERIOD_6_MONTH]?>
                </label>
                <label class="btn btn-xs btn-info <?=($model->period == ArShopPackages::PERIOD_12_MONTH ? 'active"' : '')?>">
                    <input type="radio" name="<?=CHtml::activeName($model, 'period')?>" id="period12" value="<?=ArShopPackages::PERIOD_12_MONTH?>"  <?=($model->period == ArShopPackages::PERIOD_12_MONTH ? 'checked="checked"' : '')?>>
                    <?=$periods[ArShopPackages::PERIOD_12_MONTH]?>
                </label>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="glyphicon glyphicon-list-alt i-margin"></span>
                <?php echo $form->labelEx($model, 'description'); ?>
            </div>
            <div class="col-sm-8">
                <?php echo $form->textArea($model, 'description', array('class' => 'form-control', 'rows' => 5)); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="i-margin"><strong style="font-size: 16px;">&#8372;</strong></span>
                <?php echo $form->labelEx($model, 'price_uah'); ?>
            </div>
            <div class="col-sm-8">
                <?php echo $form->textField($model, 'price_uah', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'price_uah', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="i-margin"><strong style="font-size: 16px;">₽</strong></span>
                <?php echo $form->labelEx($model, 'price_rub'); ?>
            </div>
            <div class="col-sm-8">
                <?php echo $form->textField($model, 'price_rub', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'price_rub', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <div class="form-actions">
                    <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
                    <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?php echo Yii::app()->createUrl('ShopPackages/index');?>'; return false;">Отмена</button>
                </div>
            </div>
        </div>

        <?php $this->endWidget();?>
    </fieldset>