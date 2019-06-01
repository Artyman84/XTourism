<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */


?>

<form class="navbar-form navbar-right" role="form" action="<?php echo Yii::app()->createUrl('Welcome')?>" method="post">
    <div class="form-group">
        <input type="text" name="LoginForm[email]" placeholder="Введите email" class="form-control">
    </div>
    <div class="form-group">
        <input type="password" name="LoginForm[password]" placeholder="Введите пароль" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Войти</button>
</form><?php

