<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="language" content="ru" />
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl;?>/images/favicon/favicon.ico" type="image/x-icon" />

        <!-- Bootstrap -->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/flaticons/logistics-delivery/flaticon.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/main.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/frontend/layouts/tour_info.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tsearch/spinners.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/bootstrap3/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/bootstrap3/css/bootstrap-theme.css">

        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/js/plugins/slick/slick.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/js/plugins/slick/slick-theme.css">

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <?php Yii::app()->clientScript->registerPackage('common');?>
        <?php Yii::app()->clientScript->registerPackage('slick');?>

    </head>

    <body>
        <div class="container tour-info">
            <?php echo $content; ?>
            <? if(YII_DEBUG) {?>
                <div class="alert alert-info" style="font-weight: bold;">
                    <? list($queryCount, $queryTime) = Yii::app()->db->getStats();?>
                    <? echo "Query count: $queryCount, Total query time: ".sprintf('%0.5f',$queryTime)."s"; ?>
                    <br/>
                </div>
            <? } ?>

        </div>

        <div id="footer">
            <div class="container tour-info text-center">
                <p class="text-muted"><span class="glyphicon glyphicon-copyright-mark"></span> Разработано <strong>DiAr System</strong>&nbsp;<?=date('Y')?>. Все права защищены.</p>
            </div>
        </div>

        <? Yii::app()->clientScript->registerScript("changeModalPaddings", ';(function($, undefined){  $.changeModalPaddings(); })(jQuery);', CClientScript::POS_READY ); ?>

    </body>
    <!-- Need for resolving conflict with jquery ui -->
    <script src="<?php echo Yii::app()->request->baseUrl;?>/js/bootstrap3/bootstrap.js"></script>
</html>
