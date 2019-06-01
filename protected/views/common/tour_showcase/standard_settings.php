<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 15.06.2015
 * Time: 13:40
 *
 * @var $model ArUserTourShowcase
 * @var $form CActiveForm
 * @var $settings TourShowcaseStandardSettings
 */

$this->addJsFile('settings', 'webroot.js.tour_showcase');

Yii::app()->clientScript->registerScript(
    "showcase_settings",
    ';(function($, undefined){
        $(function(){
            window.setDefaultSettings(' . CJavaScript::encode($defaultSettings) . ');
            window.setLastSettings(' . CJavaScript::encode($settings) . ');

            $("body").tooltip({selector: "[data-toggle=tooltip]"});
        });
    })(jQuery);',

    CClientScript::POS_READY
);?>


<div class="panel panel-default" style="padding: 15px;">

    <div class="row">

        <div class="col-md-10 t-iframeBlock">
            <script src="<?=Yii::app()->request->hostInfo . Yii::app()->baseUrl?>/js/front_product/_.js?p=<?=TUtil::base64url_encode(TUtil::encrypt(CJSON::encode(['uid' => $model->user_id, 'p' => 'tourShowcase'])))?>"></script>
        </div>

        <div class="col-md-2">
            <? $param = isset($user_id) ? ['user_id' => $user_id] : []; ?>
            <?php $form = $this->beginWidget('CActiveForm', [
                'id' => 'showcase-settings-form',
                'action' => $this->createUrl('UserTourShowcase/saveSettings', $param),
                'htmlOptions' => [
                    'role' => 'form',
                    'method' => 'post',
                ]
            ]);?>

            <p class="text-muted font-bold product-setting-name" >Параметры</p>

<!--            <div class="row">-->
<!--                <div class="col-md-12 form-group input-group-sm">-->
<!--                    <span class="glyphicon glyphicon-info-sign text-info" data-toggle="tooltip" data-placement="top" title="По возможности оставляйте автоматическую настройку, особенно для сайтов с резиновой версткой."></span>&nbsp;--><?php //echo $form->labelEx($settings, 'per_row', array( 'class' => 'small'))?>
<!--                    --><?php //echo $form->dropDownList($settings, 'per_row', $settings->rowsList(), array('class' => 'form-control')); ?>
<!--                </div>-->
<!--            </div>-->

            <div class="row">
                <div class="col-md-12 form-group input-group-sm">
                    <?php echo $form->labelEx($settings, 'rows', array( 'class' => 'small'))?>
                    <?php echo $form->dropDownList($settings, 'rows', $settings->rowsList(), array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group input-group-sm">
                    <?php echo $form->labelEx($settings, 'open_tour_target', array( 'class' => 'small'))?>
                    <?php echo $form->dropDownList($settings, 'open_tour_target', $settings->tourTargetsList(), array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group input-group-sm">
                    <span class="glyphicon glyphicon-info-sign text-info" data-toggle="tooltip" data-placement="top" title="Закругления имеет смысл использовать тогда, когда фон витрины отличается от фона сайта."></span>&nbsp;<?php echo $form->labelEx($settings, 'rounding', array( 'class' => 'small'))?>
                    <?php echo $form->dropDownList($settings, 'rounding', $settings->roundingList(), array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group input-group-sm">
                    <?php echo $form->labelEx($settings, 'currency', array( 'class' => 'small'))?>
                    <?php echo $form->dropDownList($settings, 'currency', $settings->currenciesList(), array('class' => 'form-control')); ?>
                </div>
            </div>


            <br/>
            <p class="text-muted font-bold product-setting-name">Оформление</p>

            <div class="row">
                <div class="col-md-12 form-group input-group-sm">
                    <?php echo $form->labelEx($settings, 'bg_color', array( 'class' => 'small'))?>

                    <div class="text-nowrap">
                        <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
                            'model' => $settings,
                            'attribute' => 'bg_color',
                            'hidden' => false, // defaults to false - can be set to hide the textarea with the hex
                            'options' => array( 'change' => new CJavaScriptExpression('function(hex, opacity){ $("#TourShowcaseStandardSettings_bg_color").trigger("change"); return true;}') ), // jQuery plugin options
                            'htmlOptions' => array('class' => 'form-control', 'style' => 'display: inline-block; height: 30px;'), // jQuery plugin options
                        )); ?>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12 form-group input-group-sm">
                    <?php echo $form->labelEx($settings, 'bg_block_color', array( 'class' => 'small'))?>

                    <div class="text-nowrap">
                        <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
                            'model' => $settings,
                            'attribute' => 'bg_block_color',
                            'hidden' => false, // defaults to false - can be set to hide the textarea with the hex
                            'options' => array( 'change' => new CJavaScriptExpression('function(hex, opacity){ $("#TourShowcaseStandardSettings_bg_block_color").trigger("change"); return true;}') ), // jQuery plugin options
                            'htmlOptions' => array('class' => 'form-control', 'style' => 'display: inline-block; height: 30px;'), // jQuery plugin options
                        )); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group input-group-sm">
                    <?php echo $form->labelEx($settings, 'tour_link_color', array( 'class' => 'small'))?>

                    <div class="text-nowrap">
                        <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
                            'model' => $settings,
                            'attribute' => 'tour_link_color',
                            'hidden' => false, // defaults to false - can be set to hide the textarea with the hex
                            'options' => array( 'change' => new CJavaScriptExpression('function(hex, opacity){ $("#TourShowcaseStandardSettings_tour_link_color").trigger("change"); return true;}') ), // jQuery plugin options
                            'htmlOptions' => array('class' => 'form-control', 'style' => 'display: inline-block; height: 30px;'), // jQuery plugin options
                        )); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group input-group-sm">
                    <?php echo $form->labelEx($settings, 'icon_color', array( 'class' => 'small'))?>

                    <div class="text-nowrap">
                        <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
                            'model' => $settings,
                            'attribute' => 'icon_color',
                            'hidden' => false, // defaults to false - can be set to hide the textarea with the hex
                            'options' => array( 'change' => new CJavaScriptExpression('function(hex, opacity){ $("#TourShowcaseStandardSettings_icon_color").trigger("change"); return true;}') ), // jQuery plugin options
                            'htmlOptions' => array('class' => 'form-control', 'style' => 'display: inline-block; height: 30px;'), // jQuery plugin options
                        )); ?>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12 form-group input-group-sm">
                    <?php echo $form->labelEx($settings, 'price_label_color', array( 'class' => 'small'))?>

                    <div class="text-nowrap">
                        <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
                            'model' => $settings,
                            'attribute' => 'price_label_color',
                            'hidden' => false, // defaults to false - can be set to hide the textarea with the hex
                            'options' => array( 'change' => new CJavaScriptExpression('function(hex, opacity){ $("#TourShowcaseStandardSettings_price_label_color").trigger("change"); return true;}') ), // jQuery plugin options
                            'htmlOptions' => array('class' => 'form-control', 'style' => 'display: inline-block; height: 30px;'), // jQuery plugin options
                        )); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group input-group-sm">
                    <?php echo $form->labelEx($settings, 'price_color', array( 'class' => 'small'))?>

                    <div class="text-nowrap">
                        <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
                            'model' => $settings,
                            'attribute' => 'price_color',
                            'hidden' => false, // defaults to false - can be set to hide the textarea with the hex
                            'options' => array( 'change' => new CJavaScriptExpression('function(hex, opacity){ $("#TourShowcaseStandardSettings_price_color").trigger("change"); return true;}') ), // jQuery plugin options
                            'htmlOptions' => array('class' => 'form-control', 'style' => 'display: inline-block; height: 30px;'), // jQuery plugin options
                        )); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group input-group-sm">
                    <?php echo $form->labelEx($settings, 'pagination_color', array( 'class' => 'small'))?>

                    <div class="text-nowrap">
                        <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
                            'model' => $settings,
                            'attribute' => 'pagination_color',
                            'hidden' => false, // defaults to false - can be set to hide the textarea with the hex
                            'options' => array( 'change' => new CJavaScriptExpression('function(hex, opacity){ $("#TourShowcaseStandardSettings_pagination_color").trigger("change"); return true;}') ), // jQuery plugin options
                            'htmlOptions' => array('class' => 'form-control', 'style' => 'display: inline-block; height: 30px;'), // jQuery plugin options
                        )); ?>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-default btn-sm" type="submit" onclick="$.showFade();" style="width: 100%;"><span class="glyphicon glyphicon-save"></span> Сохранить изменения</button>
                </div>
            </div>

            <br/>
            <div class="row" style="margin-bottom: 5px;">
                <div class="col-md-12">
                    <a href="javascript://" id="lastShowcaseSettings" style="border-bottom: 1px dashed; text-decoration: none;"><small><span class="glyphicon glyphicon-refresh"></span> Вернуть последние настройки</small></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="javascript://" id="defaultShowcaseSettings" style="border-bottom: 1px dashed; text-decoration: none;"><small><span class="glyphicon glyphicon-repeat"></span> Вернуть настройки по умолчанию</small></a>
                </div>
            </div>

            <?php $this->endWidget(); ?>

        </div>

    </div>

</div>