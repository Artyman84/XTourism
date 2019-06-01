<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 10.03.2016
 * Time: 14:03
 * @var \TSearch\Searcher $searcher
 * @var SearcherStandardSettings $settings
 */
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tsearch/form.css" />

<div class="panel panel-default" style="padding: 35px 35px 35px 35px;" id="searcher-default-values-settings">

    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12 text-center">
            <p class="help-block">Скопируйте и вставьте код на Ваш сайт там, где должен располагаться поисковик туров.</p>
        </div>

        <div class="col-md-12">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="control-label" style="cursor: pointer;" for="searcher_js_code">Поисковик туров</label>
                <input type="text" style="cursor: pointer; font-weight: bold; font-size: 12px;" readonly class="form-control t-searcher-select-code" id="searcher_js_code" value="<?=htmlspecialchars('<script src=\'' . Yii::app()->request->hostInfo . Yii::app()->baseUrl . '/js/front_product/_.js?p=' . TUtil::base64url_encode(TUtil::encrypt(CJSON::encode(['uid' => Yii::app()->user->id, 'p' => 'searcher']))) .'\'></script>')?>">
            </div>
        </div>
    </div>

    <script type="text/javascript">
        /*<![CDATA[*/
        jQuery(function($) {
            $("body").on("click", ".t-searcher-select-code", function(){
                $(this).select();
            });
        });
        /*]]>*/
    </script>
</div>