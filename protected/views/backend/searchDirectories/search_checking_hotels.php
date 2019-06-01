<?php
/* @var $this UsersController */
/* @var $model ArUsers */
/* @var $form CActiveForm */

?>

<hr/>

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'htmlOptions' => array(
        'role' => 'form',
        'method' => 'post',
    ),
    'method'=>'get',
)); ?>

<div class="row">

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'dir_hotel_id', array('class' => 'text-muted')); ?>
            <?php echo $form->textField($model, 'dir_hotel_id', array('class' => 'form-control')); ?>
        </div>
    </div>


    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'hotel_name', array('class' => 'text-muted')); ?>
            <?php echo $form->textField($model, 'hotel_name', array('class' => 'form-control')); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'category_id', array('class' => 'text-muted')); ?>
            <?php echo $form->dropDownList($model, 'category_id', $categories, ['class' => 'form-control', 'empty' => '']); ?>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'checked', array('class' => 'text-muted')); ?>
            <?php echo $form->dropDownList($model, 'checked', ['0' => 'Не проверен', '1' => 'Не проверен'], ['class' => 'form-control', 'empty' => '']); ?>
        </div>
    </div>

</div>

<hr/>
<button typeof="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Найти</button>

<?php $this->endWidget(); ?>

<!-- search-form -->