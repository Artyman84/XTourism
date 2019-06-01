

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableClientValidation'=>true,
    'htmlOptions' => array(
        'class' => 'form-signin',
        'role' => 'form',
        'method' => 'post'
    ),
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success'
    ),
));?>
    <div class="row">

        <div class="col-lg-12">

            <h4><strong><?=Yii::app()->name?>:&nbsp;вход для администратора</strong></h4>

            <div class="form-group">
                <p>
                    <?php echo $form->textField($model, 'email', array('placeholder' => 'Емейл', 'class' => 'form-control'))?>
                    <?php echo $form->error($model, 'email', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                </p>
            </div>

            <div class="form-group">
                <p>
                    <?php echo $form->passwordField($model, 'password', array('placeholder' => 'Пароль', 'class' => 'form-control'))?>
                    <?php echo $form->error($model, 'password', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                </p>
            </div>

            <button class="btn btn-primary" type="submit">Войти</button>

        </div>
    </div>
<? $this->endWidget(); ?>

