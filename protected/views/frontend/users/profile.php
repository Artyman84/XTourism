<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 21.05.2015
 * Time: 19:41
 *
 * @var ArUsers $model
 * @var CActiveForm $form
 */

$breadcrumbs = array(
    '<span class="glyphicon glyphicon-user"></span> Профайл'
);

$this->breadcrumbs = $breadcrumbs;?>

<?php echo $this->showFlashMessage('agent_profile'); ?>

    <fieldset>
        <p class="lead text-info">Мой Профайл</p>

        <? $form = $this->beginWidget('CActiveForm', array(
            'id' => 'user-form',
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
            <div class="col-sm-2 text-left" >
                <?php echo $form->labelEx($model, 'name'); ?>
            </div>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'name', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 text-left" >
                <?php echo $form->labelEx($model, 'lastname'); ?>
            </div>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'lastname', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'lastname', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 text-left" >
                <?php echo $form->labelEx($model, 'email'); ?>
            </div>
            <div class="col-sm-4">
                <? if( $model->getIsNewRecord() ) {?>
                    <?php echo $form->emailField($model, 'email', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'email', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                <?} else {?>
                    <? echo $model->email; ?>
                <? } ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 text-left">
                <?php echo $form->labelEx($model, 'password'); ?>
            </div>

            <div class="col-sm-4">
                <div class="input-group t-userPassword">
                   <span class="input-group-addon">
                        <div class="xtourism-checkbox">
                            <span class="glyphicon glyphicon-unchecked"></span>
                        </div>
                   </span>
                    <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'disabled' => 'disabled', 'value' => '????????????????????')); ?>
                </div>
                <?php echo $form->error($model, 'password', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>

            <div class="col-sm-3 t-notifyByEmail" style="padding-top: 6px; display: none;">
                <div class="xtourism-checkbox">
                    <input name="notifyByEmail" id="notifyByEmail" value="1" type="checkbox" disabled="disabled">
                    <span class="glyphicon glyphicon-unchecked"></span> <strong class="text-info" style="cursor: pointer;" onclick="$(this).prev().trigger('click');">Уведомить по емейлу</strong>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 text-left" >
                <?php echo $form->labelEx($model, 'phone'); ?>
            </div>
            <div class="col-sm-4">
                <?php echo $form->telField($model, 'phone', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'phone', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 text-left" >
                <?php echo $form->labelEx($model, 'city_id'); ?>
            </div>
            <div class="col-sm-4">
                <?php echo $form->dropDownList($model, 'city_id', CHtml::listData(\TSearch\tbl\Directory::loadData('dep_cities', ['disabled' => 0], false), 'id', 'name') , array('class' => 'form-control', 'empty' => '--Выберите свой город--')); ?>
                <?php echo $form->error($model, 'city_id', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 text-left" >
                <?php echo $form->labelEx($model, 'company'); ?>
            </div>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'company', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'company', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <div class="form-actions">
                    <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
                </div>
            </div>
        </div>


        <?php $this->endWidget();?>
    </fieldset>

<?php
if( !$model->getIsNewRecord() ) {
    Yii::app()->clientScript->registerScript(
        "edit_user",
        ';(function($, undefined){
            $(function(){

                $("body").on("click", "div.t-userPassword span.glyphicon-unchecked", function(){
                    $(this).closest("div.input-group").find("input").attr({"disabled": false, "placeholder": "Введите новый пароль", "value": ""});
                    $(this).closest("div.input-group").find("input").val("");
                    $(".t-notifyByEmail").show();
                    $("#notifyByEmail").attr("disabled", false);
                });

                $("body").on("click", "div.t-userPassword span.glyphicon-check", function(){
                    var $container = $(this).closest("div.input-group");
                    $container.removeClass("has-error");
                    $container.next().removeClass("text-dander").html("").hide();
                    $container.find("input").attr({"disabled": true, "placeholder": ""});
                    $container.find("input").val("????????????????????");
                    $(".t-notifyByEmail").hide();
                    $("#notifyByEmail").attr("disabled", true);
                });


            });
        })(jQuery);',

        CClientScript::POS_READY
    );
}