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
    '<span class="fa fa-suitcase i-margin"></span> Неактивные пакеты турагентов' => Yii::app()->createUrl('UsersDraftPackages/index'),
    $model->isNewRecord ? '<span class="fa fa-file-o"></span> Создание' : '<span class="fa fa-edit"></span> Редактирование'
];?>

    <fieldset>
        <legend class="text-info">Выбор пакета</legend>
        <? $form = $this->beginWidget('CActiveForm', array (
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
                    var isValidDates = validateDates();

                    if( !hasError && isValidMSelect && isValidDates ){
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
                <i class="flaticon-delivery-package-opened"></i>
                <?php echo CHtml::label('Пакеты', 'packages'); ?>
            </div>
            <div class="col-sm-8">
                <?php echo CHtml::dropDownList('packages', null, CHtml::listData(ArShopPackages::model()->findAll(), 'id', 'name'), ['class' => 'form-control', 'empty' => '']); ?>
            </div>
        </div>

        <br>
        <br>
        <legend class="text-info">Редактирование пакета турагента</legend>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="glyphicon glyphicon-user i-margin"></span>
                <?php echo $form->labelEx($model, 'user_id'); ?>
            </div>
            <div class="col-sm-8">
                <? if( $model->getIsNewRecord()) {?>
                    <?php echo CHtml::activeDropDownList($model, 'user_id', ArUsers::simpleUsersList(ArUsers::model()->active()->withoutPackages()->findAll()), ['class' => 'form-control', 'empty' => '']); ?>
                    <?php echo $form->error($model, 'user_id', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                <?} else {
                    echo ArUsers::model()->findByPk($model->user_id)->userName();?>
                <?} ?>
            </div>
        </div>

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
                <?php echo CHtml::label('Продукты <span class="required">*</span>', ''); ?>
            </div>
            <div class="col-sm-8">
                <? $this->widget('widgets.ms_tables.MultiSelectTables', [
                    'selectedElements' => CHtml::listData($model->products, 'id', 'name'),
                    'allElements' => CHtml::listData(ArShopProducts::model()->findAll('published = 1'), 'id', 'name'),
                    'hidden_name' => get_class($model) . '[products][]',
                    'enable_validation' => true,
                    'error_message' => 'Необходимо выбрать продукты для пакета.'
                ]);?>
            </div>
        </div>

        <div class="form-group">
            <? $periods = ArShopPackages::periods(); ?>
            <div class="col-sm-3 text-left">
                <span class="glyphicon glyphicon-time i-margin"></span>
                <?php echo CHtml::label('Период', 'period'); ?>
            </div>
            <div class="col-sm-8 btn-group t-Period" data-toggle="buttons">
                <label class="btn btn-xs btn-primary">
                    <input type="radio" name="period" id="period1" value="<?=ArShopPackages::PERIOD_1_MONTH?>">
                    <?=$periods[ArShopPackages::PERIOD_1_MONTH]?>
                </label>
                <label class="btn btn-xs btn-warning">
                    <input type="radio" name="period" id="period3" value="<?=ArShopPackages::PERIOD_3_MONTH?>">
                    <?=$periods[ArShopPackages::PERIOD_3_MONTH]?>
                </label>
                <label class="btn btn-xs btn-success">
                    <input type="radio" name="period" id="period6" value="<?=ArShopPackages::PERIOD_6_MONTH?>">
                    <?=$periods[ArShopPackages::PERIOD_6_MONTH]?>
                </label>
                <label class="btn btn-xs btn-info">
                    <input type="radio" name="period" id="period12" value="<?=ArShopPackages::PERIOD_12_MONTH?>">
                    <?=$periods[ArShopPackages::PERIOD_12_MONTH]?>
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left" >
                <span class="glyphicon glyphicon-calendar i-margin"></span>
                <?php echo $form->labelEx($model, 'start'); ?>
            </div>
            <div class="col-sm-8 text-nowrap">
                <a href="#" title="Очистить" onclick="$('#<?=CHtml::activeId($model, 'start')?>').val(''); $('#<?=CHtml::activeId($model, 'expired')?>').val(''); return false;"><span class="glyphicon glyphicon-erase text-danger"></span></a>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
                    'name' => CHtml::activeName($model, 'start'),
                    'value' => Yii::app()->dateFormatter->format('dd.MM.yyyy', $model->start),
                    'model' => $model,
                ]);?>

                <?php echo $form->error($model, 'start', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left" >
                <span class="glyphicon glyphicon-alert i-margin"></span>
                <?php echo $form->labelEx($model, 'expired'); ?>
            </div>
            <div class="col-sm-8">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
                    'name' => CHtml::activeName($model, 'expired'),
                    'value' => Yii::app()->dateFormatter->format('dd.MM.yyyy', $model->expired),
                    'model' => $model,
                ]);?>
                <?php echo $form->error($model, 'expired', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                <div class="text-danger" id="expired_error" style="display: none;">Если выбрана «Дата начала», должна быть выбрана и дата истечения пакета.</div>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="glyphicon glyphicon-list-alt i-margin"></span>
                <?php echo $form->labelEx($model, 'comment'); ?>
            </div>
            <div class="col-sm-8">
                <?php echo $form->textArea($model, 'comment', array('class' => 'form-control', 'rows' => 5)); ?>
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
                    <?php echo CHtml::button('Активировать', ['class' => 'btn btn-success t-activate-package']); ?>
                    <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?php echo Yii::app()->createUrl('UsersDraftPackages/index');?>'; return false;">Отмена</button>
                </div>
            </div>
        </div>

        <?php $this->endWidget();?>
    </fieldset>

<?php
Yii::app()->clientScript->registerScript(
    "edit_package",
    '$(function() {

        $("body").on("click", ".t-activate-package", function(){

            if( !$("#' . CHtml::activeId($model, 'start') . '").val() || !$("#' . CHtml::activeId($model, 'expired') . '").val() ) {
                alert("При активации пакета должна быть указана «Дата начала» и «Действителен до»");
                return false;
            }

            $("form#product-form").attr("action", $("form#product-form").attr("action") + "?activate=1").submit();
        });

        $("body").on("change", "#packages", function() {

            $.sendRequest("UsersDraftPackages/packageData", {package: $(this).val()}, function(data) {
                if( data ) {
                    $("#' . CHtml::activeId($model, 'name') . '").val( $.escapeHtml(data.name) ).trigger("blur");
                    $("#' . CHtml::activeId($model, 'price_uah') . '").val( data.price_uah ).trigger("blur");
                    $("#' . CHtml::activeId($model, 'price_rub') . '").val( data.price_rub ).trigger("blur");

                    $("#period" + data.period).parent().trigger("click");

                    $(".t-delete-all").trigger("click");
                    for(var i=0, l=data.products.length; i<l; ++i) {
                        $(".t-all-elements tr[id=\'" + data.products[i] + "\']").trigger("dblclick");
                    };
    }

            });
        });

        $("#' . CHtml::activeId($model, 'start') . '").datepicker("option", {
            "onSelect": function(date) {
                $("#' . CHtml::activeId($model, 'expired') . '").datepicker("option", {"minDate": date});
                calculateExpired();
        }});

        $(".t-Period input").change(function(){
            calculateExpired();
        });

    });

    function validateDates() {
        var start = $("#' . CHtml::activeId($model, 'start') . '");
        var expired = $("#' . CHtml::activeId($model, 'expired') . '");

        if( start.val() && !expired.val() ){
            expired.parent().addClass("has-error");
            $("#expired_error").show();
            return false;
        } else {
            expired.parent().removeClass("has-error");
            $("#expired_error").hide();
            return true;
        }
    }

    var getPeriod = function() {
        return parseInt($(".t-Period input:checked").val());
    }

    var getStart = function() {
        return $("#' . CHtml::activeId($model, 'start') . '").datepicker("getDate");
    }

    var calculateExpired = function(){
        var period = getPeriod();
        var start = getStart();

        if( period && start ) {
            start.setMonth(start.getMonth() + period);
            $("#' . CHtml::activeId($model, 'expired') . '").datepicker("setDate", start);
        }
    }

    ',
    CClientScript::POS_READY
); ?>