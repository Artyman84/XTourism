<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />

    <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl;?>/images/favicon/favicon.ico" type="image/x-icon" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/flaticons/logistics-delivery/flaticon.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/bootstrap3/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/backend/layouts/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/bootstrap3/css/bootstrap-theme.css" />

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php Yii::app()->clientScript->registerPackage('common');?>
    <?php Yii::app()->clientScript->registerPackage('bootstrap3');?>
</head>

<body style="overflow-y: scroll;">

<?php $this->widget('TPersonalMenu', ['controller_name' => $this->getId(), 'action_name' => $this->getAction()->getId(), 'userId' => Yii::app()->user->id]); ?>

<br/><br/><br/>


<div class="container-fluid">
    <div class="row">

        <?php if( !Yii::app()->user->isGuest ){?>
            <div class="col-sm-3 col-md-2 sidebar">
                <?php $this->widget('TPersonalSidebar', ['controller_name' => $this->getId(), 'action_name' => $this->getAction()->getId(), 'userId' => Yii::app()->user->id]); ?>
            </div>
        <?php } ?>

        <!-- breadcrumbs -->
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="padding-top: 12px; padding-bottom: 0;">
            <?php if(isset($this->breadcrumbs)):?>
                <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                    'homeLink' => CHtml::link('<span class="fa fa-home"></span> Главная',  Yii::app()->createUrl('Welcome')),
                ));?>
            <?php endif?>
        </div>
        <!-- breadcrumbs -->

        <div class="<?php echo (Yii::app()->user->isGuest ? 'col-md-12' : 'col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2');?>">

            <? if(YII_DEBUG && 0) {?>
                <div class="alert alert-info" style="font-weight: bold;">
                    <? list($queryCount, $queryTime) = Yii::app()->db->getStats();?>
                    <? echo "Query count: $queryCount, Total query time: ".sprintf('%0.5f',$queryTime)."s"; ?>
                    <br/>
                </div>
            <? } ?>

            <?php echo $content; ?>
        </div>

    </div>
</div>

<div id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-md-offset-2 col-sm-offset-3 text-center">
                <p class="text-muted"><span class="glyphicon glyphicon-copyright-mark"></span> Разработано <strong>DiAr System</strong>&nbsp;<?=date('Y')?>. Все права защищены.</p>
            </div>
        </div>
    </div>

</div>

<div class="ajax-loader" style="display: none;"></div>
</body>
</html>
