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
            <?php echo $form->label($model, 'category_id', array('class' => 'text-muted')); ?>
            <?php echo $form->dropDownList($model, 'category_id', CHtml::listData(ArShopCategories::model()->findAll(), 'id', 'name'), array('class' => 'form-control', 'empty' => '')); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'type_id', array('class' => 'text-muted')); ?>
            <?php echo $form->dropDownList($model, 'type_id', CHtml::listData(ArShopProductsTypes::model()->findAll(), 'id', 'name'), array('class' => 'form-control', 'empty' => '')); ?>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'name', array('class' => 'text-muted')); ?>
            <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
        </div>
    </div>

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

<div class="row">
    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'published', ['class' => 'text-muted']); ?>
            <?php echo $form->dropDownList($model, 'published', [1 => 'Опубликован', 0 => 'Неопубликован'], ['class' => 'form-control', 'empty' => '']); ?>
        </div>
    </div>
</div>

<hr/>
<button typeof="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Найти</button>

<?php $this->endWidget(); ?>

<!-- search-form -->