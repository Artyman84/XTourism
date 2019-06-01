<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap3/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/bootstrap3/css/bootstrap-theme.css" />
</head>
<body>
<div class="alert alert-<?=$class?> text-center"><?=$content?></div>
<script type="text/javascript">/*<![CDATA[*/
    window.onload = function(){
        parent.postMessage( JSON.stringify({
            iid: "<?=$iframeId?>",
            h: document.body.offsetHeight + "px"
        }), "*");
    };
/*]]>*/</script>
</body>
</html>


