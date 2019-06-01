<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 21.05.2015
 * Time: 19:41
 *
 * @var CActiveForm $form
 * @var ArDirResorts $model
 */

$this->breadcrumbs=[
    '<span class="fa fa-book"></span> ' . ArDirectorySearch::getTableName($view) => Yii::app()->createUrl('SearchDirectories/index', ['view' => $view]),
    $model->isNewRecord ? '<span class="fa fa-file-o"></span> Создание' : '<span class="fa fa-edit"></span> Редактирование'
];?>

<fieldset>
    <legend class="text-info">Основное</legend>
    <? $form = $this->beginWidget('CActiveForm', array(
        'id' => 'showcase-form',
        'htmlOptions' => array(
            'role' => 'form',
            'method' => 'post',
            'class' => 'form-horizontal'
        ),
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'afterValidate' => 'js:function(form, data, hasError){

                var is_combined = $("#' . CHtml::activeId($model, 'is_combined') . '").is(":checked") || ' . $model->is_combined . ';

                if( !hasError && ( !is_combined || (window.ms_tables.validateMsTables() && is_combined) )  ){
                    $.showFade(); return true;
                } else {
                    return false;
                }
            }',
            'errorCssClass' => 'has-error',
            'successCssClass' => 'has-success'
        ),
    ));?>

    <div class="form-group">
        <div class="col-sm-3 text-left">
            <span class="glyphicon glyphicon-globe"></span>
            <?php echo $form->labelEx($model, 'dir_country_id'); ?>
        </div>
        <div class="col-sm-8">
            <? if( $model->isNewRecord ) {
                echo $form->dropDownList($model, 'dir_country_id', $countries, array('class' => 'form-control'));
                echo $form->error($model, 'dir_country_id', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));
            } else {
                echo CHtml::encode($countries[$model->dir_country_id]);
            }?>
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-3 text-left">
            <span class="glyphicon glyphicon-tag"></span>
            <?php echo $form->labelEx($model, 'name'); ?>
        </div>
        <div class="col-sm-8">
            <? // Убираем возможность редактировать название некомбинированного курорта ?>
            <? if( $model->isNewRecord || $model->is_combined) {?>
                <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'name', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            <? } else { ?>
                <? echo CHtml::encode($model->name); ?>
            <? } ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-3 text-left">
            <i class="fa fa-object-group"></i>
            <strong><?=$model->getAttributeLabel('is_combined')?></strong>
        </div>
        <div class="col-sm-8">
            <? // Разрешено создание обычного и комбинированного курорта ?>
            <? if( $model->isNewRecord ) {?>
                    <div class="xtourism-checkbox">
                        <?php echo $form->checkBox($model, 'is_combined', array('class' => 'form-control')); ?>
                        <span class="glyphicon glyphicon-<?=$model->is_combined ? 'check text-info' : 'unchecked'?>"></span>
                    </div>
            <? } else {?>
                    <span class="text-info"><strong><? echo $model->is_combined ? 'Да' : 'Нет';?></strong></span>
            <? } ?>
        </div>
    </div>

    <? if( $model->isNewRecord || $model->is_combined ) {?>

        <div class="form-group t-combineResorts" style="<?=$model->is_combined ? '' : 'display:none;'?>">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-sm-3 text-left">
                        <span class="glyphicon glyphicon-globe"></span>
                        <?php echo $form->label($model, 'dir_region_id'); ?>
                    </div>
                    <div class="col-sm-8">
                        <?php echo $form->dropDownList($model, 'dir_region_id', $regions, array('class' => 'form-control', 'empty' => '')); ?>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-3 text-left">
                        <i class="fa fa-sun-o"></i>
                        <?php echo CHtml::label('Дочерние курорты', ''); ?>
                        <p class="text-warning" style="margin-top: 15px;"><strong>Важно:</strong> при удалении дочерних курортов необходимо вручную разъединить отели этих курортов с отелями туроператров!</p>
                    </div>
                    <div class="col-sm-8">
                        <? $this->widget('widgets.ms_tables.MultiSelectTables', [
                                'selectedElements' => $model->childrenResortsList(),
                                'allElements' => CHtml::listData($uncombinedResorts, 'id', 'name'),
                                'hidden_name' => 'uncombined_resorts[]',
                                'enable_validation' => true,
                                'error_message' => 'Для комбинированного курорта, необходимо выбрать хотя бы один курорт.'
                        ]);?>
                    </div>
                </div>

            </div>
        </div>

    <? } ?>

    <? // Запрещаем для создаваемых курортов добавлять районы/регионы/провинции/острова  ?>

    <? if( !$model->isNewRecord && !$model->is_combined) {?>
        <br/><br/>
        <div class="form-group">
            <div class="col-sm-12">
                <legend class="text-info">Регионы</legend>
            </div>
        </div>

        <?

        $free_regions = isset( $regions[ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_REGION)] ) ? $regions[ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_REGION)] : [];
        $districts = isset( $regions[ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_DISTRICT)] ) ? $regions[ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_DISTRICT)] : [];
        $islands = isset( $regions[ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_ISLAND)] ) ? $regions[ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_ISLAND)] : [];
        $provinces = isset( $regions[ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_PROVINCE)] ) ? $regions[ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_PROVINCE)] : [];

        ?>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="glyphicon glyphicon-globe"></span>
                <?php echo $form->labelEx($model, ArDirRegions::REG_TYPE_DISTRICT); ?>
            </div>
            <div class="col-sm-8">
                <? $district = $model->region(['condition' => 'type="' . ArDirRegions::REG_TYPE_DISTRICT . '"']);?>
                <? if( 0 /* запрещаем создавать/менять район(ы) */ ) {?>
                    <?php echo CHtml::dropDownList(CHtml::activeName($model, ArDirRegions::REG_TYPE_DISTRICT), !empty($district) ? $district[0]->id : '', $districts , array('class' => 'form-control', 'type' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_DISTRICT), 'empty' => ''));?>
                <? } else { ?>

                    <? // Районов в одном курорте может быть больше одного, поэтому выводим их списком ?>

                    <? if( !empty($district) ) {?>
                        <? $arrDistricts = []; ?>
                        <? foreach ($district as $_district) {?>
                            <? if( isset($districts[$_district->id]) ) $arrDistricts[] = $districts[$_district->id];?>
                        <? } ?>
                        <? echo CHtml::encode(implode(', ', $arrDistricts)); ?>
                    <? } else {?>
                        <? echo '&mdash;'; ?>
                    <? } ?>
                <? } ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="glyphicon glyphicon-globe"></span>
                <?php echo $form->labelEx($model, ArDirRegions::REG_TYPE_REGION); ?>
            </div>
            <div class="col-sm-8">
                <? $free_region = $model->region(['condition' => 'type="' . ArDirRegions::REG_TYPE_REGION . '"']);?>
                <? if( 0 /* запрещаем создавать/менять регион */ ) {?>
                    <?php echo CHtml::dropDownList(CHtml::activeName($model, ArDirRegions::REG_TYPE_REGION), !empty($free_region) ? $free_region[0]->id : '', $free_regions , array('class' => 'form-control', 'type' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_REGION), 'empty' => ''));?>
                <? } else { ?>
                    <? echo !empty($free_region) && isset($free_regions[$free_region[0]->id]) ? CHtml::encode($free_regions[$free_region[0]->id]) : '&mdash;'; ?>
                <? } ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="glyphicon glyphicon-globe"></span>
                <?php echo $form->labelEx($model, ArDirRegions::REG_TYPE_PROVINCE); ?>
            </div>
            <div class="col-sm-8">
                <? $province = $model->region(['condition' => 'type="' . ArDirRegions::REG_TYPE_PROVINCE . '"']);?>
                <? if( 0 /* запрещаем создавать/менять провинцию */ ) {?>
                    <?php echo CHtml::dropDownList(CHtml::activeName($model, ArDirRegions::REG_TYPE_PROVINCE), !empty($province) ? $province[0]->id : '', $provinces , array('class' => 'form-control', 'type' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_PROVINCE), 'empty' => ''));?>
                <? } else {?>
                    <? echo !empty($province) && isset($provinces[$province[0]->id]) ? CHtml::encode($provinces[$province[0]->id]) : '&mdash;'; ?>
                <? } ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-left">
                <span class="glyphicon glyphicon-globe"></span>
                <?php echo $form->labelEx($model, ArDirRegions::REG_TYPE_ISLAND); ?>
            </div>
            <div class="col-sm-8">
                <? $island = $model->region(['condition' => 'type="' . ArDirRegions::REG_TYPE_ISLAND . '"']);?>
                <? if( 0 /* запрещаем создавать/менять остров */ ) {?>
                    <?php echo CHtml::dropDownList(CHtml::activeName($model, ArDirRegions::REG_TYPE_ISLAND), !empty($island) ? $island[0]->id : '', $islands , array('class' => 'form-control', 'type' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_ISLAND), 'empty' => ''));?>
                <? } else {?>
                    <? echo !empty($island) && isset($islands[$island[0]->id]) ? CHtml::encode($islands[$island[0]->id]) : '&mdash;'; ?>
                <? } ?>
            </div>
        </div>
    <? } ?>

    <br/><br/>
    <div class="form-group">
        <div class="col-sm-12">
            <legend class="text-info">Дополнительно</legend>
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-3 text-left">
            <span class="glyphicon glyphicon-list-alt"></span>
            <?php echo $form->labelEx($model, 'description'); ?>
        </div>
        <div class="col-sm-8">
            <?php echo $form->textArea($model, 'description', array('class' => 'form-control', 'rows' => 5)); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-3 text-left">
            <span class="glyphicon glyphicon-screenshot"></span>
            <?php echo $form->labelEx($model, 'position'); ?>
        </div>
        <div class="col-sm-8">
            <?php echo $form->textField($model, 'position', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'position', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-3 text-left">
            <span class="glyphicon glyphicon-thumbs-up"></span>
            <?php echo $form->labelEx($model, 'rating'); ?>
        </div>
        <div class="col-sm-8">
            <?php echo $form->textField($model, 'rating', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'rating', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-3 text-left">
            <span class="glyphicon glyphicon-ban-circle"></span>
            <strong><?=$model->getAttributeLabel('disabled')?></strong>
        </div>
        <div class="col-sm-8">
            <div class="xtourism-checkbox">
                <?php echo $form->checkBox($model, 'disabled', array('class' => 'form-control')); ?>
                <span class="glyphicon glyphicon-<?=$model->disabled ? 'check text-info' : 'unchecked'?>"></span>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <div class="form-actions">
                <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
                <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?php echo Yii::app()->createUrl('SearchDirectories/index', ['view' => $view, get_class($model) . '[dir_country_id]' => $model->dir_country_id]);?>'; return false;">Отмена</button>
            </div>
        </div>
    </div>

    <?php $this->endWidget();?>
</fieldset>

<? Yii::app()->clientScript->registerScript(
    'edit_resort',
    ';
    (function($, undefined){

        $(function(){

            var resorts_to_regions = ' . TUtil::jsonList($resorts_regions, 'dir_resort_id', 'regions_ids') . ';
            var resorts = ' . TUtil::jsonList($uncombinedResorts, 'id', 'name') . ';
                                    
            $(".t-select-all").addClass("disabled");

            var toggleSelectAll = function(){
                if( $("#' . CHtml::activeId($model, 'dir_region_id') . '").val() ) {
                    $(".t-select-all").removeClass("disabled");
                } else {
                    $(".t-select-all").addClass("disabled");
                }
            }          

            $("#' . CHtml::activeId($model, 'dir_country_id') . '").change(function(){
            
                $.sendRequest("SearchDirectories/regionsAndCitiesByCountry/" + $(this).val(), {resort: ' . (int)$model->id . '}, function(data){

                    $("#' . CHtml::activeId($model, 'free_region') . ', #' . CHtml::activeId($model, 'district') . ', #' . CHtml::activeId($model, 'province') . ', #' . CHtml::activeId($model, 'island') . '").html("<option value></option>");

                    resorts = data["uncombinedResorts"];
                    resorts_to_regions = data["resorts_regions"];

                    var regions = data["regions"];
                    var combinedOptions = ["<option value></option>"];
                    var j = 1;
                    for( var regionType in regions ){

                        combinedOptions[j++] = "<optgroup label=\'" + regionType + "\'>";
                        var options = ["<option value></option>"];
                        var i = 1;

                        for(var regionId in regions[regionType]){
                            combinedOptions[j++] = options[i++] = "<option value=\'" + regionId.replace("_", "") + "\'>" + regions[regionType][regionId] + "</option>";
                        }

                        combinedOptions[j++] = "</optgroup>";
                        $("select[type=\'" + regionType + "\']").html(options.join(""));
                    }

                    $(".head-selected-elements").next().get(0).innerHTML = "";
                    $("#' . CHtml::activeId($model, 'dir_region_id') . '").html(combinedOptions.join("")).trigger("change");

                });
            });

            $("#' . CHtml::activeId($model, 'is_combined') . '").change(function(){
                if( $(this).is(":checked") ){
                    $(".t-combineResorts").show();
                } else {
                    $(".t-combineResorts").hide();
                }
            });

            $("#' . CHtml::activeId($model, 'dir_region_id') . '").change(function(){
                var trs = [];
                var regionId = $(this).val();
                var i = 1;
                var selectedChildren = window.ms_tables.getChildrenResorts();
                
                for( var _resort_id in resorts ) {
                    if( regionId == "" || (typeof resorts_to_regions[_resort_id] !== "undefined" && resorts_to_regions[_resort_id].indexOf("," + regionId + ",") !== -1) ) {
                    
                        var resort_id = _resort_id.replace("_", "");
                        var is_checked = $.inArray(resort_id, selectedChildren) != -1;
                        
                        trs[trs.length] = "<tr id=\'" + resort_id + "\' " + (is_checked ? "class=\'checked-element\'" : "") + "><td>" + (i++) + "</td><td>" + $.escapeHtml(resorts[_resort_id]) + "</td><td><a href=\'#\' class=\'text-success t-select-one\'><span class=\'glyphicon glyphicon-" + (is_checked ? "ok" : "plus-sign") + "\'></span></a></td></tr>";
                    }
                }
                
                $(".t-all-elements").get(0).innerHTML = trs.join("");
                $(".t-element-filter").val("");
                toggleSelectAll();
            });

        })

    })(jQuery);',
    CClientScript::POS_READY
);