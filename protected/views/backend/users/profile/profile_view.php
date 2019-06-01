<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 21.05.2015
 * Time: 19:41
 *
 * @var ArUsers $model
 */

$this->breadcrumbs=array(
    '<span class="fa fa-users"></span> Персонал' => Yii::app()->createUrl('Users/index'),
    '<span class="fa fa-user"></span> Профайл'
);?>


<fieldset>
    <legend class="text-info"><?php echo ArUsers::roleName($model->role, true)?></legend>

    <form class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2 text-left text-muted" for="ArUsers_name">
                <?php echo $model->getAttributeLabel('id'); ?>
            </label>
            <div class="col-sm-5 font-bold">
                <?php echo $model->getAttribute('id')?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 text-left text-muted" for="ArUsers_name">
                <?php echo $model->getAttributeLabel('name'); ?>
            </label>
            <div class="col-sm-5 font-bold">
                <?php echo $model->getAttribute('name')?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 text-left text-muted" for="ArUsers_name">
                <?php echo $model->getAttributeLabel('lastname'); ?>
            </label>
            <div class="col-sm-5 font-bold">
                <?php echo $model->getAttribute('lastname')?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 text-left text-muted" for="ArUsers_name">
                <?php echo $model->getAttributeLabel('email'); ?>
            </label>
            <div class="col-sm-5 font-bold">
                <?php echo $model->getAttribute('email')?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 text-left text-muted" for="ArUsers_name">
                <?php echo $model->getAttributeLabel('phone'); ?>
            </label>
            <div class="col-sm-5 font-bold">
                <?php echo $model->getAttribute('phone')?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 text-left text-muted" for="ArUsers_name">
                <?php echo $model->getAttributeLabel('city'); ?>
            </label>
            <div class="col-sm-5 font-bold">
                <?php echo isset($model->city) ? $model->city->name : '';?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 text-left text-muted" for="ArUsers_name">
                <?php echo $model->getAttributeLabel('company'); ?>
            </label>
            <div class="col-sm-5 font-bold">
                <?php echo $model->getAttribute('company')?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 text-left text-muted" for="ArUsers_name">
                <?php echo $model->getAttributeLabel('state'); ?>
            </label>
            <div class="col-sm-5 font-bold">
                <?php if( $model->getAttribute('state') ) {
                    echo '<span class="glyphicon glyphicon-ban-circle text-danger" title="Заблокирован"></span>';
                } else {
                    echo '<span class="glyphicon glyphicon-ok-circle text-success" title="Активен"></span>';
                }?>
            </div>
        </div>


        <hr/>

        <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?php echo Yii::app()->createUrl('Users/index');?>'; return false;"><span class="glyphicon glyphicon-arrow-left"></span> Назад</button>
    </form>
</fieldset>