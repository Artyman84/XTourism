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
            <?php echo $form->label($model, 'dir_category_id', array('class' => 'small')); ?>
            <?php echo $form->dropDownList($model, 'dir_category_id', $categories , ['class' => 'form-control', 'empty' => '']); ?>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'rating', array('class' => 'small')); ?>
            <?php echo $form->textField($model, 'rating', array('class' => 'form-control')); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'position', array('class' => 'small')); ?>
            <?php echo $form->textField($model, 'position', array('class' => 'form-control')); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo CHtml::label('Статус', CHtml::activeId($model, 'disabled'), ['class' => 'small']) ; ?>
            <?php echo $form->dropDownList($model, 'disabled', array(0 => 'Активный', 1 => 'Неактивен'), array('class' => 'form-control', 'empty' => '')); ?>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'dir_city_id', array('class' => 'small')); ?>
            <?php echo $form->dropDownList($model, 'dir_city_id', $cities, array('class' => 'form-control', 'empty' => '')); ?>
        </div>
    </div>

</div>

<hr/>
<button typeof="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Найти</button>

<?php $this->endWidget(); ?>

<!-- search-form -->