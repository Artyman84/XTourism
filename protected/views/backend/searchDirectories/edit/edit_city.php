<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 21.05.2015
 * Time: 19:41
 *
 * @var CActiveForm $form
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

                if( !hasError ){
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
            <div class="col-sm-2 text-left">
                <span class="glyphicon glyphicon-globe text-muted"></span>
                <?php echo $form->labelEx($model, 'dir_country_id', array('class' => 'text-muted')); ?>
            </div>
            <div class="col-sm-5">
                <? if( $model->isNewRecord ) {
                    echo $form->dropDownList($model, 'dir_country_id', $countries, array('class' => 'form-control'));
                    echo $form->error($model, 'dir_country_id', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));
                } else {
                    echo CHtml::encode($countries[$model->dir_country_id]);
                }?>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-2 text-left">
                <span class="glyphicon glyphicon-tag text-muted"></span>
                <?php echo $form->labelEx($model, 'name', array('class' => 'text-muted')); ?>
            </div>
            <div class="col-sm-5">
                <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'name', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
            </div>
        </div>

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
            <div class="col-sm-2 text-left">
                <span class="glyphicon glyphicon-globe text-muted"></span>
                <?php echo $form->labelEx($model, ArDirRegions::REG_TYPE_DISTRICT, array('class' => 'text-muted')); ?>
            </div>
            <div class="col-sm-5">
                <? $district = $model->region(['condition' => 'type="' . ArDirRegions::REG_TYPE_DISTRICT . '"']);?>
                <?php echo CHtml::dropDownList(CHtml::activeName($model, ArDirRegions::REG_TYPE_DISTRICT), !empty($district) ? $district[0]->id : '', $districts , array('class' => 'form-control', 'type' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_DISTRICT), 'empty' => ''));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 text-left">
                <span class="glyphicon glyphicon-globe text-muted"></span>
                <?php echo $form->labelEx($model, ArDirRegions::REG_TYPE_REGION, array('class' => 'text-muted')); ?>
            </div>
            <div class="col-sm-5">
                <? $free_region = $model->region(['condition' => 'type="' . ArDirRegions::REG_TYPE_REGION . '"']);?>
                <?php echo CHtml::dropDownList(CHtml::activeName($model, ArDirRegions::REG_TYPE_REGION), !empty($free_region) ? $free_region[0]->id : '', $free_regions , array('class' => 'form-control', 'type' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_REGION), 'empty' => ''));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 text-left">
                <span class="glyphicon glyphicon-globe text-muted"></span>
                <?php echo $form->labelEx($model, ArDirRegions::REG_TYPE_PROVINCE, array('class' => 'text-muted')); ?>
            </div>
            <div class="col-sm-5">
                <? $province = $model->region(['condition' => 'type="' . ArDirRegions::REG_TYPE_PROVINCE . '"']);?>
                <?php echo CHtml::dropDownList(CHtml::activeName($model, ArDirRegions::REG_TYPE_PROVINCE), !empty($province) ? $province[0]->id : '', $provinces , array('class' => 'form-control', 'type' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_PROVINCE), 'empty' => ''));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 text-left">
                <span class="glyphicon glyphicon-globe text-muted"></span>
                <?php echo $form->labelEx($model, ArDirRegions::REG_TYPE_ISLAND, array('class' => 'text-muted')); ?>
            </div>
            <div class="col-sm-5">
                <? $island = $model->region(['condition' => 'type="' . ArDirRegions::REG_TYPE_ISLAND . '"']);?>
                <?php echo CHtml::dropDownList(CHtml::activeName($model, ArDirRegions::REG_TYPE_ISLAND), !empty($island) ? $island[0]->id : '', $islands , array('class' => 'form-control', 'type' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_ISLAND), 'empty' => ''));?>
            </div>
        </div>

        <br/><br/>
        <div class="form-group">
            <div class="col-sm-12">
                <legend class="text-info">Дополнительно</legend>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-2 text-left">
                <span class="glyphicon glyphicon-list-alt text-muted"></span>
                <?php echo $form->labelEx($model, 'description', array('class' => 'text-muted')); ?>
            </div>
            <div class="col-sm-5">
                <?php echo $form->textArea($model, 'description', array('class' => 'form-control', 'rows' => 5)); ?>
            </div>
        </div>


        <hr/>

        <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
        <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?php echo Yii::app()->createUrl('SearchDirectories/index', ['view' => $view, get_class($model) . '[dir_country_id]' => $model->dir_country_id]);?>'; return false;">Отмена</button>

        <?php $this->endWidget();?>
    </fieldset>

<? Yii::app()->clientScript->registerScript(
    'edit_resort',
    ';
    (function($, undefined){

        $(function(){

            $("#' . CHtml::activeId($model, 'dir_country_id') . '").change(function(){
                $.sendRequest("SearchDirectories/regionsByCountry/" + $(this).val(), {resort: ' . (int)$model->id . '}, function(data) {

                    $("#' . CHtml::activeId($model, 'free_region') . ', #' . CHtml::activeId($model, 'district') . ', #' . CHtml::activeId($model, 'province') . ', #' . CHtml::activeId($model, 'island') . '").html("<option value></option>");

                    var regions = data;
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

                    $("#' . CHtml::activeId($model, 'dir_region_id') . '").html(combinedOptions.join("")).trigger("change");

                });
            });

        })

    })(jQuery);',
    CClientScript::POS_READY
);