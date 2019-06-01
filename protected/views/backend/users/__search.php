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
        //  'class' => 'form-horizontal'
    ),
    'method'=>'get',
)); ?>

<div class="row">

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'id', array('class' => 'text-muted')); ?>
            <?php echo $form->textField($model, 'id', array('class' => 'form-control')); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'role', array('class' => 'text-muted')); ?>
            <?php echo $form->dropDownList($model, 'role', $this->availableRolesList(true, true), array('class' => 'form-control', 'empty' => '')); ?>
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
            <?php echo $form->label($model, 'lastname', array('class' => 'text-muted')); ?>
            <?php echo $form->textField($model, 'lastname', array('class' => 'form-control')); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'email', array('class' => 'text-muted')); ?>
            <?php echo $form->textField($model, 'email', array('class' => 'form-control')); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'state', array('class' => 'text-muted')); ?>
            <?php echo $form->dropDownList($model, 'state', array(0 => 'Активен', 1 => 'Заблокирован', 2 => 'Ожидает подтверждения'), array('class' => 'form-control', 'empty' => '')); ?>
        </div>
    </div>
</div>



<hr/>
<button typeof="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Найти</button>

<?php $this->endWidget(); ?>

<!-- search-form -->