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
            <?php echo $form->label($model, 'name', array('class' => 'text-muted')); ?>
            <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'period', ['class' => 'text-muted']); ?>
            <?php echo $form->dropDownList($model, 'period', [1 => '1 Месяц', 3 => '3 Месяца', 6 => '6 Месяцев', 12 => '12 Месяцев'], ['class' => 'form-control', 'empty' => '']); ?>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'price_uah', array('class' => 'text-muted')); ?>
            <?php echo $form->textField($model, 'price_uah', array('class' => 'form-control')); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'price_rub', array('class' => 'text-muted')); ?>
            <?php echo $form->textField($model, 'price_rub', array('class' => 'form-control')); ?>
        </div>
    </div>

</div>

<hr/>
<button typeof="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Найти</button>

<?php $this->endWidget(); ?>

<!-- search-form -->