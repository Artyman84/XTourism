<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html" xml:lang="en" lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="language" content="en">
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl;?>/images/favicon/favicon.ico" type="image/x-icon" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/flaticons/logistics-delivery/flaticon.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/main.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/frontend/layouts/showcase.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/bootstrap3/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/bootstrap3/css/bootstrap-theme.css">

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <?php Yii::app()->clientScript->registerPackage('bootstrap3');?>
        <?php Yii::app()->clientScript->registerPackage('common');?>
    </head>

    <body style="margin: 0 !important;">

        <div class="container-fluid" style="padding: 0;">

            <? if(YII_DEBUG && 0) {?>
                <div class="alert alert-info" style="font-weight: bold;">
                    <? list($queryCount, $queryTime) = Yii::app()->db->getStats();?>
                    <? echo "Query count: $queryCount, Total query time: ".sprintf('%0.5f',$queryTime)."s"; ?>
                    <br/>
                </div>
            <? } ?>

            <?php echo $content;?>
        </div>

    </body>

</html>
