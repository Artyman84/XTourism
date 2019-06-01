<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl;?>/images/favicon/favicon.ico" type="image/x-icon" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/flaticons/logistics-delivery/flaticon.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap3/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/bootstrap3/css/bootstrap-theme.css" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <?php Yii::app()->clientScript->registerPackage('common'); ?>

    </head>

<body style="overflow-y: scroll;">

    <?php $this->widget('TFrontendMenu', ['controller_name' => $this->getId(), 'action_name' => $this->getAction()->getId(), 'userId' => Yii::app()->request->getParam('id')]); ?>

    <br/><br/><br/>

    <div class="container container-frontend">

        <!-- breadcrumbs -->
        <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('zii.widgets.CBreadcrumbs', [
                'links' => $this->breadcrumbs,
                'homeLink' => CHtml::link('<span class="fa fa-home"></span> Главная',  Yii::app()->createUrl('Welcome')),
        ]);?><!-- breadcrumbs -->

        <?php endif?>

        <div class="row content-row">
            <div class="col-md-12">

                <? if(YII_DEBUG) {?>
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
        <div class="container container-frontend text-center">
            <p class="text-muted"><span class="glyphicon glyphicon-copyright-mark"></span> Разработано <strong>DiAr System</strong>&nbsp;<?=date('Y')?>. Все права защищены.</p>
        </div>
    </div>


</body>
    <!-- Need for resolving conflict with jquery ui -->
    <script src="<?php echo Yii::app()->request->baseUrl;?>/js/bootstrap3/bootstrap.js"></script>
</html>
