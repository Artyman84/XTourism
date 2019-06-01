<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 19.08.2016
 * Time: 14:21
 */

if( !empty($modal) ){?>
    <div class="modal fade" id="modalTourRequest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Отправить заявку</h4>
                </div>

                <div class="modal-body"><?
} else {
    ?><div id="simpleTourRequest"><?
}?>


    <div id="requestMessageContainer" style="display: none;"></div>

    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => isset($action) ? $action : Yii::app()->request->url,
        'htmlOptions' => array('role' => 'form'),
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'afterValidate' => $request_func,
            'errorCssClass' => 'has-error',
            'successCssClass' => 'has-success'
        ),
    ));?>

    <? $request = new RequestForm; ?>

    <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo $form->labelEx($request, 'name'); ?>
                        <?php echo $form->textField($request, 'name', array('class' => 'form-control', 'placeholder' => 'Введите свое имя')); ?>
                        <?php echo $form->error($request, 'name', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo $form->labelEx($request, 'phone'); ?>
                        <?php echo $form->telField($request, 'phone', array('class' => 'form-control', 'placeholder' => 'Введите свой телефон')); ?>
                        <?php echo $form->error($request, 'phone', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                    </div>
                </div>

                <div class="col-md-12">

                    <div class="form-group">
                        <?php echo $form->labelEx($request, 'email'); ?>
                        <?php echo $form->emailField($request, 'email', array('class' => 'form-control', 'placeholder' => 'Введите свой email')); ?>
                        <?php echo $form->error($request, 'email', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-8 col-sm-8 col-xs-8">
            <div class="form-group">
                <?php echo $form->labelEx($request, 'comment'); ?>
                <?php echo $form->textArea($request, 'comment', array('class' => 'form-control', 'style' => 'height:139px;', 'placeholder' => 'Оставьте комментарий к заявке..')); ?>
                <?php echo $form->error($request, 'comment', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                <p class="help-block">Отправка заявки ни к чему не обязывает и не является бронированием. Получив заявку, менеджер туристической компании уточнит наличие тура и свяжется с Вами.</p>
            </div>
        </div>
    </div>

    <? if( !empty($modal) ) {?>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        <? echo CHtml::submitButton('Отправить', array( 'class' => 'btn btn-success' ));?>
                    </div>

                </div>
            </div>
        </div>

    <? } else { ?>

            <br>
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group text-right">
                        <? echo CHtml::submitButton('Отправить', ['class' => 'btn btn-success']);?>
                    </div>
                </div>

            </div>
        </div>
    <? } ?>
<?php $this->endWidget();?>