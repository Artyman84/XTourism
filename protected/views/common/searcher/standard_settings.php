<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 15.06.2015
 * Time: 13:40
 *
 * @var $model ArUserSearcher
 * @var $form CActiveForm
 * @var $settings SearcherStandardSettings
 */

$settings = $model->searcherSettings();

?>

<div class="panel panel-default" style="padding: 15px;" id="searcher-design-settings">

    <div class="row">

        <div class="col-md-9 col-sm-9 col-xs-9">
            <script src="<?=Yii::app()->request->hostInfo . Yii::app()->baseUrl?>/js/front_product/_.js?p=<?=TUtil::base64url_encode(TUtil::encrypt(CJSON::encode(['uid' => $model->user->id, 'p' => 'searcher'])))?>"></script>
        </div>

        <div class="col-md-3 col-sm-3 col-xs-3">
            <? $param = isset($user_id) ? ['user_id' => $user_id] : []; ?>
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'searcher-settings-form',
                'action' => $this->createUrl('UserSearcher/saveDesign', $param),
                'htmlOptions' => array(
                    'role' => 'form',
                    'method' => 'post',
                )
            ));?>

            <p class="text-muted font-bold product-setting-name"><?=$settings->getAttributeLabel('bg_color_class')?></p>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group input-group-sm">
                        <div data-toggle="buttons" class="searcher-set-bg-color-class">
                            <? foreach( $settings->bgColorClassesList() as $i => $bg_class ) {?>
                                <? $checked = $settings->bg_color_class == $bg_class; ?>
                                <label class="btn <?=$bg_class?> <?=($checked ? 'active' : '')?>">
                                    <input type="radio" name="<?=CHtml::activeName($settings, 'bg_color_class')?>" value="<?=$bg_class?>" <?=$checked ? 'checked' : ''?>>
                                </label>
                            <? } ?>
                        </div>
                    </div>
                </div>
            </div>


            <p class="text-muted font-bold product-setting-name"><?=$settings->getAttributeLabel('rounding')?></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group input-group-sm">
                        <div data-toggle="buttons"  class="searcher-set-rounding">
                            <? foreach($settings->roundingList() as $round) {?>
                                <? $checked = $settings->rounding == $round; ?>
                                <label class="btn <?=($checked ? 'active' : '')?>">
                                    <span style="border-radius: 0 <?=$round?>px 0 0;"></span>
                                    <input type="radio" name="<?=CHtml::activeName($settings, 'rounding')?>" value="<?=$round?>" <?=$checked ? 'checked' : ''?>>
                                </label>
                            <? } ?>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-muted font-bold product-setting-name"><?=$settings->getAttributeLabel('spinner')?></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group input-group-sm searcher-set-spinners text-nowrap">
                        <?php echo CHtml::activeDropDownList($settings, 'spinner', $settings->spinnersList(), ['class' => 'form-control small', 'style' => 'width: 87%; display: inline-block;']) ?>
                        <button type="button" class="btn btn-default btn-sm" title="показать"><span class="glyphicon glyphicon-play"></span></button>
                    </div>
                </div>
            </div>

            <p></p>

            <div class="row">
                <div class="col-md-12">

                    <button class="btn btn-primary btn-sm" type="submit" onclick="$.showFade();"><span class="glyphicon glyphicon-save"></span> Сохранить изменения</button>
                </div>
            </div>

            <?php $this->endWidget(); ?>

        </div>

    </div>

    <script type="text/javascript">
        /*<![CDATA[*/
        ;(function($, undefined){

            var searcherFrameDoc = function(){
                return $.getFrameDocument("xtrsmproduct");
            }

            var changeBgColorClass = function(c){
                var old_c = $("#xtourism", searcherFrameDoc()).parent().attr("design-color");
                var old_cc = "xtourism-" + old_c;
                var new_cc = "xtourism-" + c;

                $("body", searcherFrameDoc()).removeClass(old_cc).addClass(new_cc);
                $("#xtourism", searcherFrameDoc()).parent().attr("design-color", c);
                $("#xtourism", searcherFrameDoc()).removeClass(old_cc).addClass(new_cc);
                $("#xtourism-results", searcherFrameDoc()).removeClass(old_cc).addClass(new_cc);
                $("#xtourism-results", searcherFrameDoc()).removeClass("xtourism-results-" + old_c).addClass("xtourism-results-" + c);
            }

            var changeRounding = function(r){
                var old_r = 'xtourism-rounded-' + $("#xtourism", searcherFrameDoc()).parent().attr("rounding");
                $("#xtourism, #xtourism-results", searcherFrameDoc()).removeClass(old_r);

                if( $.toInt(r) ){
                    $("#xtourism, #xtourism-results", searcherFrameDoc()).addClass("xtourism-rounded-" + r);
                }

                $("#xtourism", searcherFrameDoc()).parent().attr("rounding", r);
            }

            var changeSpinner = function(s){
                var iid = $("iframe[name='xtrsmproduct']").attr("id");
                $("#xtourism", searcherFrameDoc()).parent().attr("spinner", s);

                parent.postMessage( JSON.stringify({
                    iid: iid,
                    action: 'showFade',
                    cc: "xtourism-" + $("#xtourism", searcherFrameDoc()).parent().attr("design-color") + " xtourism-spinner-load" + s
                }), "*");

                setTimeout(function(){
                    parent.postMessage( JSON.stringify({iid: iid, action: 'hideFade' }), "*");
                }, 2000);
            }

            $(function(){

                /***********************************************  SETTINGS  ***********************************************/

                $("body").on("change", "#searcher-design-settings .searcher-set-bg-color-class input:radio", function(){
                    changeBgColorClass($(this).val());
                });

                $("body").on("change", "#searcher-design-settings .searcher-set-rounding input:radio", function(){
                    changeRounding($(this).val());
                });

                $("body").on("change", "#searcher-design-settings .searcher-set-spinners select", function(){
                    changeSpinner( $(this).val() );
                });

                $("body").on("click", "#searcher-design-settings .searcher-set-spinners button", function(){
                    $(this).parent().find("select").trigger("change");
                });

            });

        })(jQuery);

        /*]]>*/
    </script>

</div>