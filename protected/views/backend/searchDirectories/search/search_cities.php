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
            <?php echo $form->label($model, 'id', array('class' => 'small')); ?>
            <?php echo $form->textField($model, 'id', array('class' => 'form-control')); ?>
        </div>
    </div>


    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'name', array('class' => 'small')); ?>
            <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'dir_region_id', array('class' => 'small')); ?>
            <?php echo $form->dropDownList($model, 'dir_region_id', $regions, array('class' => 'form-control', 'empty' => '')); ?>
        </div>
    </div>

</div>

<hr/>
<button typeof="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Найти</button>

<?php $this->endWidget(); ?>

<!-- search-form -->