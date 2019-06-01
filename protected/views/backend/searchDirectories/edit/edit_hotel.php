<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 21.05.2015
 * Time: 19:41
 *
 * @var CActiveForm $form
 * @var ArDirHotels $model
 */

$this->addJsFile('edit/edit_hotel');

$this->breadcrumbs=array(
    '<span class="fa fa-book"></span> ' . ArDirectorySearch::getTableName($view) => Yii::app()->createUrl('SearchDirectories/index', ['view' => $view]),
    $model->isNewRecord ? '<span class="fa fa-file-o"></span> Создание' : '<span class="fa fa-edit"></span> Редактирование'
);?>

    <fieldset>
        <? $form = $this->beginWidget('CActiveForm', array(
            'id' => 'showcase-form',
            'htmlOptions' => array(
                'role' => 'form',
                'method' => 'post',
            ),
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'afterValidate' => 'js:function(form, data, hasError){
                    if( !hasError ){

                        var data = new FormData();
                        var $form = form;

                        $.each($form.serializeArray(), function(k, el){
                            data.append(el.name, el.value);
                        });

                        var images = {};
                        $("span[remove-handle^=\'tourPhotos\']").each(function(k, span){
                            var rh = $(this).attr("remove-handle");

                            if( !images.hasOwnProperty(rh) ){
                                images[rh] = [];
                            }

                            images[rh][images[rh].length] = $.toInt($(this).attr("i-file"));
                        });

                        $form.find("input:file").each(function(_, fileObj){
                            var hr = fileObj.id;
                            $.each(fileObj.files, function(i, file){
                                if( $.inArray(i, images[hr]) != -1 ){
                                    data.append(fileObj.name + "[]", file);
                                }
                            });
                        });

                        $.showFade();
                        $.sendRequest( {"url": $form.attr("action")}, data, function(data){

                            if( data.hasOwnProperty("url") ){
                                window.location.href=data.url;
                            } else {
                                $.hideFade();
                                if( data.hasOwnProperty("name") ){
                                    $("#' . CHtml::activeId($model, 'name') . '").closest(".form-group").removeClass("has-success").addClass("has-error");
                                    $("#' . CHtml::activeId($model, 'name') . '").next().html(data.name.join("<br>")).show();
                                }
                            }
                        },
                        "JSON",
                        false,
                        true);

                    }

                    return false;
                }',
                'errorCssClass' => 'has-error',
                'successCssClass' => 'has-success'
            ),
        ));?>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <div class="form-actions">
                    <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
                    <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?php echo Yii::app()->createUrl('SearchDirectories/index', ['view' => $view, 'country_id' => $model->dir_country_id, get_class($model) . '[dir_resort_id]' => $model->dir_resort_id]);?>'; return false;">Отмена</button>
                </div>
            </div>
        </div>
        <br>

        <legend class="text-info">Основное</legend>
        <div class="row">

            <div class="col-sm-4 col-md-4 col-xs-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-globe text-muted"></span>
                    <?php echo $form->labelEx($model, 'dir_country_id', array('class' => 'text-muted')); ?>
                    <? if( $model->isNewRecord ) {
                        echo $form->dropDownList($model, 'dir_country_id', $countries, array('class' => 'form-control'));
                        echo $form->error($model, 'dir_country_id', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));
                    } else {
                        echo '<div class="form-control">' . CHtml::encode($model->country->name) . '</div>';
                    }?>
                </div>
            </div>

            <div class="col-sm-4 col-md-4 col-xs-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-globe text-muted"></span>
                    <?php echo $form->labelEx($model, 'dir_city_id', array('class' => 'text-muted')); ?>
                    <? if( $model->isNewRecord ) {
                        echo $form->dropDownList($model, 'dir_city_id', $cities, array('class' => 'form-control'));
                        echo $form->error($model, 'dir_city_id', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));
                    } else {
                        echo '<div class="form-control">' . CHtml::encode($model->city->name) . '</div>';
                    }?>
                </div>
            </div>

            <div class="col-sm-4 col-md-4 col-xs-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-globe text-muted"></span>
                    <?php echo $form->labelEx($model, 'dir_resort_id', array('class' => 'text-muted')); ?>
                    <?php echo $form->dropDownList($model, 'dir_resort_id', $resorts, array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'dir_resort_id', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                </div>
            </div>

        </div>


        <br/>


        <div class="row">
            <div class="col-sm-4 col-md-4 col-xs-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-tag text-muted"></span>
                    <?php echo $form->labelEx($model, 'name', array('class' => 'text-muted')); ?>
                    <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'name', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                </div>
            </div>

            <div class="col-sm-4 col-md-4 col-xs-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-star text-muted"></span>
                    <?php echo $form->labelEx($model, 'dir_category_id', array('class' => 'text-muted')); ?>
                    <?php echo $form->dropDownList($model, 'dir_category_id', $categories, array('class' => 'form-control', 'empty' => '')); ?>
                    <?php echo $form->error($model, 'dir_category_id', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                </div>
            </div>

            <div class="col-sm-4 col-md-4 col-xs-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-home text-muted"></span>
                    <?php echo $form->labelEx($model, 'address', array('class' => 'text-muted')); ?>
                    <?php echo $form->textField($model, 'address', array('class' => 'form-control')); ?>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-map-marker text-muted"></span>
                    <?php echo $form->labelEx($model, 'coords', array('class' => 'text-muted')); ?>
                    <?php echo $form->textField($model, 'coords', array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-screenshot text-muted"></span>
                    <?php echo $form->labelEx($model, 'position', array('class' => 'text-muted')); ?>
                    <?php echo $form->textField($model, 'position', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'position', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-thumbs-up text-muted"></span>
                    <?php echo $form->labelEx($model, 'rating', array('class' => 'text-muted')); ?>
                    <?php echo $form->textField($model, 'rating', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'rating', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="checkbox">
                    <div class="xtourism-checkbox form-group">
                        <?php echo $form->checkBox($model, 'disabled', array('class' => 'form-control')); ?>
                        <span class="glyphicon glyphicon-<?=$model->disabled ? 'check text-info' : 'unchecked'?>"></span>
                        <strong class="text-info" style="cursor: pointer;" onclick="$(this).prev().trigger('click');"><?=$model->getAttributeLabel('disabled')?></strong>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <span class="glyphicon glyphicon-list-alt text-muted"></span>
                    <?php echo $form->labelEx($model, 'description', ['class' => 'text-muted']); ?>
                    <?php echo $form->textArea($model, 'description', ['class' => 'form-control', 'rows' => 12]); ?>
                </div>
            </div>
        </div>

        <br>
        <br>
        <legend class="text-info">Услуги/Ориентиры</legend>
        <? $services = ArHotelServices::propertiesList($model->services);?>
        <? $i = 0;?>
        <? foreach( ArHotelServices::icons() as $property => $icon ) {?>
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <span class="fa fa-<?=$icon?> text-muted"></span>
                    <?php echo CHtml::label(TUtil::mb_ucfirst($property), 'Services_' . $i . '', ['class' => 'text-muted']); ?>
                </div>
                <div class="col-md-6 col-sm-8 col-xs-8">
                    <?php echo CHtml::textArea('Services[' . $i . ']', isset($services[$property]) ? str_replace(['<standard>', '</standard>', '<free>', '</free>'], '', $services[$property]) : '', ['class' => 'form-control', 'rows' => 4]); ?>
                </div>
            </div>
            <br>
            <? ++$i; ?>
        <? } ?>

        <br>
        <br>
        <legend class="text-info">Дополнительно</legend>
        <? $residence = ArHotelResidence::propertiesList($model->residence);?>
        <? $i = 0; ?>
        <? foreach( ArHotelResidence::icons() as $property => $icon ) {?>
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <span class="fa fa-<?=$icon?> text-muted"></span>
                    <?php echo CHtml::label(TUtil::mb_ucfirst($property), 'Residence_' . $i . '', ['class' => 'text-muted']); ?>
                </div>
                <div class="col-md-6 col-sm-8 col-xs-8">
                    <?php echo CHtml::textArea('Residence[' . $i . ']', isset($residence[$property]) ? str_replace(['<standard>', '</standard>', '<free>', '</free>'], '', $residence[$property]) : '', ['class' => 'form-control', 'rows' => 4]); ?>
                </div>
            </div>
            <br>
            <? ++$i; ?>
        <? } ?>

        <br>
        <br>
        <legend class="text-info">Кредитные карты</legend>
        <div class="row hotel-credit-cards">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <? $cards = $model->cards();?>
                <? foreach(ArCurrencyCards::cards() as $card) {
                    $active = isset($cards[$card->id]);
                    ?><div class="thumbnail <?=$active ? 'active' : ''?>">
                    <img src="<?=Yii::app()->baseUrl?>/images/hotel_cards/<?=$card->name?>.png" class="thumbnail-showcase" alt="">
                    <div class="text-center">
                        <div class="xtourism-checkbox">
                            <input type="checkbox" value="<?=$card->id?>" name="Cards[]" <?=$active ? 'checked="checked"' : ''?>>
                            <span class="glyphicon glyphicon-<?=$active ? 'check text-info' : 'unchecked'?>"></span>
                        </div>
                    </div>
                    </div><?
                }?>

            </div>
        </div>


        <br>
        <br>
        <legend class="text-info">Фотогалерея</legend>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label><span class="glyphicon glyphicon-picture i-margin text-muted"></span> Фото для тура</label><br/>
                    <p class="help-block">Размер изображения не должен превышать 3 МБ.</p>
                    <p class="help-block">Максимальное число изображений - 30 штук.</p>
                    <p class="help-block">Все изображения должны быть с расширением JPEG/JPG.</p>
                    <a href="#" id="addNewTourFiles"><span class="glyphicon glyphicon-plus"></span>&nbsp;Добавить фото</a>
                </div>
            </div>
        </div>


        <div class="row t-tourPhotoSection">

            <div class="col-md-12 t-tourFiles">
                <input type="file" id="tourPhotos1" name="HotelImages[images]" class="t-newTourFile" multiple="true" accept="image/jpeg" style="display: none;">
            </div><?php

            $images = $model->images();
            if(  !empty($images)) {
                foreach ($images as $i => $image) {
                    $id = $i + 1; ?>
                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                    <div class="thumbnail">
                            <span style="float: right;">
                                <span class="glyphicon glyphicon-remove text-danger remove-tour-icon" title="Удалить"></span>
                            </span>

                        <img src="<?=$image?>" class="thumbnail-showcase" alt="">

                        <div class="text-center text-info" style="margin-bottom: -4px;">
                            <input type="hidden" name="file_ids[]" value="<?=$id?>"/>
                            <span class="t-nr-hotel-photo"><?=$id?></span>
                        </div>
                    </div>
                    </div><?
                }
            }?>
        </div>


        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <div class="form-actions">
                    <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
                    <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?php echo Yii::app()->createUrl('SearchDirectories/index', ['view' => $view, 'country_id' => $model->dir_country_id, get_class($model) . '[dir_resort_id]' => $model->dir_resort_id]);?>'; return false;">Отмена</button>
                </div>
            </div>
        </div>

        <?php $this->endWidget();?>
    </fieldset>

<? Yii::app()->clientScript->registerScript(
    'edit_hotel',
    ';
    (function($, undefined){

        $(function(){
            $("#' . CHtml::activeId($model, 'dir_country_id') . '").change(function(){
                $.sendRequest("SearchDirectories/resortsByCountry/" + $(this).val(), {}, function(data){

                    var options = { "cities": [], "resorts": []};

                    for(var type in data){
                        var i = 1;
                        for(var resortId in data[type]){
                            options[type][i++] = "<option value=\'" + resortId.replace("_", "") + "\'>" + data[type][resortId] + "</option>";
                        }
                    }

                    $("#' . CHtml::activeId($model, 'dir_resort_id') . '").html(options["resorts"].join());
                    $("#' . CHtml::activeId($model, 'dir_city_id') . '").html(options["cities"].join());
                });

            });

            $(".hotel-credit-cards .thumbnail").click(function(e) {

                if( e.target.tagName.toUpperCase() == "IMG" || e.target.tagName.toUpperCase() == "DIV" ) {
                    $(this).find("span").trigger("click");
                }

            });

            $(document.body).on("change", ".hotel-credit-cards .thumbnail :checkbox", function() {
                if( $(this).is(":checked") ) {
                    $(this).closest(".thumbnail").addClass("active");
                } else {
                    $(this).closest(".thumbnail").removeClass("active");
                }
            });

        })

    })(jQuery);',
    CClientScript::POS_READY
);