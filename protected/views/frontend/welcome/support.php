<?php
/* @var $this SiteController */
/* @var $model SupportForm */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
    '<span class="fa fa-support"></span> Техподдержка'
);?>

<p class="lead text-info">Техподдержка</p>

<?php if(Yii::app()->user->hasFlash('contact')) {
    echo Yii::app()->user->getFlash('contact');
} else { ?>

    <p class="help-block">Здесь Вы сможете сообщить нам о возникших проблемах или вопросах по работе сайта. Мы незамедлительно отреагируем на ваше сообщение!</p>
    <p class="help-block">Также будем рады Вашим предложениям и советам по улучшению работы сайта.</p>
    <br/><br/>


    <?php

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'contact-form',
        'htmlOptions' => array('class' => 'form-horizontal', 'role' => 'form'),
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'afterValidate' => 'js:function(form, data, hasError){ if( !hasError ){ $.showFade(); return true } else {return false;} }',
            'errorCssClass' => 'has-error',
            'successCssClass' => 'has-success'
        ),
    ));

    if (Yii::app()->user->isGuest) {?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'name', array('class' => 'col-sm-2 text-left')); ?>
            <div class="col-sm-7">
                    <?php echo $form->textField($model, 'name', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('name'))); ?>
                    <?php echo $form->error($model, 'name', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model,'email', array('class' => 'col-sm-2 text-left')); ?>
            <div class="col-sm-7">
                <?php echo $form->textField($model,'email', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('email'))); ?>
                <?php echo $form->error($model,'email', array('class' => 'text-danger')); ?>
            </div>
        </div><?php
    }?>

        <div class="form-group">
            <?php echo $form->labelEx($model,'subject', array('class' => 'col-sm-2 text-left')); ?>
            <div class="col-sm-7">
                <?php echo $form->textField($model, 'subject', array('size'=>60,'maxlength'=>128, 'class' => 'form-control', 'placeholder' => $model->getAttributeLabel('subject'))); ?>
                <?php echo $form->error($model,'subject', array('class' => 'text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model,'body', array('class' => 'col-sm-2 text-left')); ?>
            <div class="col-sm-7">
                <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'class' => 'form-control', 'placeholder' => $model->getAttributeLabel('body'))); ?>
                <?php echo $form->error($model,'body', array('class' => 'text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <?php echo CHtml::submitButton('Отправить', array('class' => 'btn btn-primary')); ?>
            </div>
        </div>

    <?php $this->endWidget();?>

<?php }; ?>