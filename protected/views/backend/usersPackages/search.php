<?php
/* @var $this UsersController */
/* @var $model ArUsers */
/* @var $form CActiveForm */

?>

<hr/>

<?php $form=$this->beginWidget('CActiveForm', [
    'action'=>Yii::app()->createUrl($this->route),
    'htmlOptions' => [
        'role' => 'form',
    ],
    'method'=>'get',
]); ?>

<div class="row">

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'id', array('class' => 'text-muted')); ?>
            <?php echo $form->textField($model, 'id', array('class' => 'form-control')); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'user_id', array('class' => 'text-muted')); ?>
            <?php echo CHtml::activeDropDownList($model, 'user_id', ArUsers::simpleUsersList(ArUsers::model()->active()->agent()->findAll()), ['class' => 'form-control', 'empty' => '']); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'name', array('class' => 'text-muted')); ?>
            <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'start', array('class' => 'text-muted')); ?>
            <a href="#" title="Очистить" onclick="$(this).next().val(''); return false;"><span class="glyphicon glyphicon-erase text-danger"></span></a>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
                'name' => CHtml::activeName($model, 'start'),
                'value' => '',
                'model' => $model,
            ]);?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'expired', array('class' => 'text-muted')); ?>
            <a href="#" title="Очистить" onclick="$(this).next().val(''); return false;"><span class="glyphicon glyphicon-erase text-danger"></span></a>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
                'name' => CHtml::activeName($model, 'expired'),
                'value' => '',
                'model' => $model,
            ]);?>

        </div>
    </div>

</div>

<hr/>
<button typeof="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Найти</button>

<?php $this->endWidget(); ?>

<!-- search-form -->