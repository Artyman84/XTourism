<?php
/* @var $this SiteController */
/* @var $model RegistrationForm */
/* @var $form CActiveForm */


$this->breadcrumbs=array(
    '<span class="fa fa-pencil-square-o"></span> Регистрация'
);?>

<h3>Регистрация турагента</h3>
<br/>
<?php if(Yii::app()->user->hasFlash('registration')) {
    echo Yii::app()->user->getFlash('registration');
} else {

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'registration-form',
        'htmlOptions' => array('class' => 'form-horizontal', 'role' => 'form'),
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            //'validateOnSubmit' => true,
            'errorCssClass' => 'has-error',
            'successCssClass' => 'has-success'
        ),
    ));?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'name', array('class' => 'col-sm-2 text-left')); ?>
        <div class="col-sm-5">
            <?php echo $form->textField($model, 'name', array('class' => 'form-control', 'placeholder' => 'Ваше имя')); ?>
            <?php echo $form->error($model, 'name', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'lastname', array('class' => 'col-sm-2 text-left')); ?>
        <div class="col-sm-5">
            <?php echo $form->textField($model, 'lastname', array('class' => 'form-control', 'placeholder' => 'Ваша фамилия')); ?>
            <?php echo $form->error($model, 'lastname', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'email', array('class' => 'col-sm-2 text-left')); ?>
        <div class="col-sm-5">
            <?php echo $form->textField($model,'email', array('class' => 'form-control', 'placeholder' => 'Ваш Email')); ?>
            <?php echo $form->error($model,'email', array('class' => 'text-danger')); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'phone', array('class' => 'col-sm-2 text-left')); ?>
        <div class="col-sm-5">
            <?php echo $form->textField($model,'phone', array('class' => 'form-control', 'placeholder' => 'Ваш Телефон')); ?>
            <?php echo $form->error($model,'phone', array('class' => 'text-danger')); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'password', array('class' => 'col-sm-2 text-left')); ?>
        <div class="col-sm-5">
            <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => 'Введите свой пароль')); ?>
            <?php echo $form->error($model,'password', array('class' => 'text-danger')); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'password2', array('class' => 'col-sm-2 text-left')); ?>
        <div class="col-sm-5">
            <?php echo $form->passwordField($model, 'password2', array('class' => 'form-control', 'placeholder' => 'Повторите пароль')); ?>
            <?php echo $form->error($model,'password2', array('class' => 'text-danger')); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'city_id', array('class' => 'col-sm-2 text-left')); ?>
        <div class="col-sm-5">
            <?=$form->dropDownList($model, 'city_id', CHtml::listData(\TSearch\tbl\Directory::loadData('dep_cities', ['disabled' => 0], false), 'id', 'name'), array('class' => 'form-control', 'empty' => '--Ваш город--'));?>
            <?php echo $form->error($model, 'city_id', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'company', array('class' => 'col-sm-2 text-left')); ?>
        <div class="col-sm-5">
            <?php echo $form->textField($model,'company', array('class' => 'form-control', 'placeholder' => 'Название Вашей компании')); ?>
            <?php echo $form->error($model,'company', array('class' => 'text-danger')); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'verifyCode', array('class' => 'col-sm-2 text-left')); ?>

        <div class="col-sm-10">
            <?php $this->widget('CCaptcha'); ?>
        </div>

        <div class="col-sm-3 col-sm-offset-2">
            <?php echo $form->textField($model,'verifyCode', array('class' => 'form-control', 'placeholder' => 'Код с картинки')); ?>
            <?php echo $form->error($model,'verifyCode', array('class' => 'text-danger')); ?>
            <div class="hint">
                <small>Пожалуйста, введите буквы, указанные выше.</small>
            </div>
        </div>

    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?php echo CHtml::submitButton('Отправить', array('class' => 'btn btn-primary')); ?>
        </div>
    </div>

    <?php $this->endWidget();?>

<?php }
