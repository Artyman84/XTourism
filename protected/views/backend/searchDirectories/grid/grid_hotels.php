
<?php $this->breadcrumbs=array(
    '<span class="fa fa-book"></span> ' . ArDirectorySearch::getTableName($view)
);?>

<?php Yii::app()->clientScript->registerScript('search', "
$('body').on('click', '.search-button', function(){

	if( $('.search-form').is(':hidden') ){
	    $(this).find('span:first').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
	} else {
	    $(this).find('span:first').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-right');
	}

	$('.search-form').toggle();
	return false;
});

$('body').on('change', '.t-main-search-form select', function(){
    // Если меняем страну - сбрасываем курорты, что бы не остались старые отели.
    if( $(this).attr('id') == 'country_id' ){
        $('.t-main-search-form select:last').val('');
    }

    $(this).closest('form').submit();
});

$('body').on('submit', '.search-form form, .t-main-search-form', function(){

	$('#directory_grid_view').yiiGridView('update', {
		data: $('.t-main-search-form, .search-form form').serialize(),
		complete: function(jqXHR, status) {
            if (status=='success'){
                var data = $('<div>' + jqXHR.responseText + '</div>');

                if( $('.search-form').is(':hidden') ){
                    $('.search-form', data).hide();
                } else {
                    $('.search-form', data).show();
                }

                $('.search-form').replaceWith($('.search-form', data));
                $('.t-resortsCombo').replaceWith($('.t-resortsCombo', data));
            }
        }
	});
	return false;
});

");?>

<div class="row">
    <div class="col-md-12">
        <a href="#" class="search-button"><span class="glyphicon glyphicon-triangle-right"></span> Расширенный поиск <span class="glyphicon glyphicon-filter"></span></a>
    </div>

    <div class="search-form col-md-12" style="display:none">
        <?php $this->renderPartial('search/search_hotels', ['model' => $model, 'categories' => $categories, 'cities' => $cities]); ?>
    </div>
</div>
<br/>
<br/>

<div class="panel panel-default" id="elementsPanelID">

    <div class="panel-heading">
        <div class="col-sm-2 text-left" style="margin-top: 4px;"><strong>Список элементов</strong></div>

        <?php $form=$this->beginWidget('CActiveForm', [
            'action'=>Yii::app()->createUrl($this->route),
            'htmlOptions' => [ 'class' => 't-main-search-form' ],
            'method'=>'get',
        ]); ?>

            <div class="col-sm-1 input-group-sm text-right" style="margin-top: 4px;">
                <?php echo CHtml::label('Страны', 'country_id', ['class' => 'small']) ?>
            </div>

            <div class="col-sm-3 input-group-sm">
                <?php echo CHtml::dropDownList('country_id', $country_id, $countries, ['class' => 'form-control']); ?>
            </div>

            <div class="col-sm-1 input-group-sm text-right" style="margin-top: 4px;">
                <?php echo CHtml::label('Курорты', CHtml::activeId($model, 'dir_resort_id'), ['class' => 'small']) ?>
            </div>

            <div class="col-sm-3">

                <div class="input-group-sm t-resortsCombo">
                    <?php echo CHtml::dropDownList(CHtml::activeName($model, 'dir_resort_id'), $resort_id, $resorts, ['class' => 'form-control', 'empty' => '']); ?>
                </div>

            </div>

        <?php $this->endWidget(); ?>

        <div class="col-sm-2 text-right" style="margin-top: 4px;">
            <div class="btn-group t-mainActions">
                <a href="#" class="dropdown-toggle text-nowrap" data-toggle="dropdown"><strong>Действие</strong>&nbsp;<span class="caret"></span></a>
                <ul class="dropdown-menu backend-dropdown-menu" role="menu">
                    <li><a href="javascript://" class="t-createElement"><span class="glyphicon glyphicon-plus text-success"></span>&nbsp;Создать</a></li>
                    <li><a href="javascript://" class="t-enableElements"><span class="glyphicon glyphicon-ok-circle text-success"></span>&nbsp;<span >Активировать</span></a></li>
                    <li><a href="javascript://" class="t-disableElements"><span class="glyphicon glyphicon-ban-circle text-danger"></span>&nbsp;<span >Заблокировать</span></a></li>
                    <li><a href="javascript://" class="t-deleteElements"><span class="glyphicon glyphicon-remove text-danger"></span>&nbsp;<span >Удалить</span></a></li>
                </ul>
            </div>
        </div>

        <div class="clearfix"></div>
    </div><?php

    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
        'htmlOptions' => array('class' => 'panel-grid-view grid-view'),
        'id' => 'directory_grid_view',
        'summaryText' => 'Элементы {start}&mdash;{end} из {count}',
        'summaryCssClass' => 'summary panel-summary',
        'itemsCssClass' => 'table table-unbordered table-hovered panel-table table-striped',
        'pagerCssClass' => 'panel-default-pager',
        'rowHtmlOptionsExpression' => 'array("id" => "element_id_" . $data->id)',
        'columns' => array(
            array(
                'header' => '<div class="xtourism-checkbox">
                               <input type="checkbox" value="1" class="ch-parent">
                               <span class="glyphicon glyphicon-unchecked"></span>
                           </div>',

                'type' => 'raw',
                'value' => function($data){
                    return '<div class="xtourism-checkbox">
                                <input type="checkbox" value="' . $data->id . '" class="ch-child">
                                <span class="glyphicon glyphicon-unchecked"></span>
                            </div>';
                },
                'headerHtmlOptions' => array('style' => 'width: 2%;'),
            ),

            array(
                'header' => '#',
                'headerHtmlOptions' => array('style' => 'width: 2%;'),
                'htmlOptions' => array('class' => 't-countElement'),
                'value' => '$row + 1'
            ),

            array(
                'name' => 'id',
                'headerHtmlOptions' => array('style' => 'width: 5%;'),
            ),

            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'CHtml::link($data->name, Yii::app()->createUrl("SearchDirectories/edit", ["view" => "' . $view . '", "id"=> $data->id, "country_id" => ' . $country_id . '])) . "&nbsp;&nbsp;&nbsp;" . CHtml::link("<span class=\'fa fa-external-link\'></span>", $data->url, ["target" => "_blank"])',
                //'headerHtmlOptions' => array('style' => 'width: 30%;'),
            ),

            array(
                'name' => 'resort_name',
                'type' => 'html',
                'value' => 'isset($data->resort) ? $data->resort->name : ""',
            ),

            array(
                'name' => 'city_name',
                'type' => 'html',
                'value' => 'isset($data->city) ? $data->city->name : ""',
            ),

            array(
                'name' => 'category_name',
                'type' => 'html',
                'value' => 'isset($data->category) ? $data->category->name : ""',
            ),

            array(
                'name' => 'position',
            ),

            array(
                'name' => 'rating',
            ),

            array(
                'name' => 'disabled',
                'header' => 'Статус',
                'type' => 'html',
                'htmlOptions' => array('class' => 'text-center t-elementStatus'),
                'headerHtmlOptions' => array('style' => 'width: 3%;', 'class' => 'text-center'),
                'value' => function($data){
                    if($data->disabled) {
                        return "<span class=\"text-danger glyphicon glyphicon-ban-circle\" title=\"Заблокирован\"></span>";
                    } else {
                        return "<span class=\"text-success glyphicon glyphicon-ok-circle\" title=\"Активен\"></span>";
                    }
                },

            ),

            array(
                'htmlOptions' => array('class' => 'text-right'),
                'type' => 'raw',
                'value' => function($data){

                    $ret = '<div class="btn-group t-ownActions" id="element_act_' . $data->id . '">
                            <a href="#" class="dropdown-toggle text-nowrap" data-toggle="dropdown">Действие&nbsp;<span class="caret"></span></a>
                            <ul class="dropdown-menu backend-dropdown-menu" role="menu">
                                <li><a href="javascript://" class="t-editElement"><span class="glyphicon glyphicon-edit text-warning"></span>&nbsp;Редактировать</a></li>
                                <li>
                                    <a href="javascript://" class="' . ($data->disabled ? 't-enableElement' : 't-disableElement') . '">
                                        <span class="glyphicon glyphicon-' . ($data->disabled ? 'ok-circle text-success' : 'ban-circle text-danger') . '"></span>
                                        <span>' . ($data->disabled ? 'Активировать' : 'Заблокировать') . '</span>
                                    </a>
                                </li>
                                <li><a href="javascript://" class="t-deleteElement"><span class="glyphicon glyphicon-remove text-danger"></span>&nbsp;<span >Удалить</span></a></li>
                            </ul>
                        </div>';

                    return $ret;
                }
            )
        )
    ));?>

</div>

<? Yii::app()->clientScript->registerScript(
    'checkboxGroup',
    ';
    $.initCheckboxGroup(
        "elementsPanelID",
        function(input){
            input.closest("tr").addClass("info");
        },
        function(input){
            input.closest("tr").removeClass("info");
        }
    );


    (function($, undefined){

        var getCountry = function(){
            return $.toInt($("#country_id").val());
        }

        var getResort = function(){
            return $.toInt($("#' . CHtml::activeId($model, 'dir_resort_id') . '").val());
        }

        var getCity = function(){
            var resortId = getResort();
            var resortName = $("#' . CHtml::activeId($model, 'dir_resort_id') . ' option[value=\'" + resortId + "\']").text();
            var cityId = 0;

            $("#' . CHtml::activeId($model, 'dir_city_id') . ' option").each(function(i){
                if( $(this).text() == resortName && $(this).text() ){
                    cityId = $(this).val();
                }
            });

            return cityId;
        }

        var getElements = function(){
            var ids = [];
            $("div#elementsPanelID input.ch-child:checked").each(function(){
                ids[ids.length] = $.toInt($(this).val());
            });

            return ids;
        };

        var getElement = function(sel){
            return $.toInt($(sel).closest("tr").find("td:first input:checkbox").val());
        };

        var enableElements = function(ids, enable){
            var data = {
                0: {
                    text: "Активировать",
                    title: "Заблокирован",
                    rClasses: "glyphicon-ban-circle text-danger",
                    aClasses: "glyphicon-ok-circle text-success",
                    sel: "t-disableElement",
                    newSel: "t-enableElement",
                },
                1: {
                    "text": "Заблокировать",
                    title: "Активен",
                    "rClasses": "glyphicon-ok-circle text-success",
                    "aClasses": "glyphicon-ban-circle text-danger",
                    "sel": "t-enableElement",
                    "newSel": "t-disableElement",
                }
            };

            $.sendRequest("SearchDirectories/enable", {view: "' . $view . '", ids: ids, enable: enable}, function(){

                for(var i=0, l=ids.length; i<l; ++i) {

                    var ta = $("div#element_act_" + ids[i] + " ." + data[enable].sel);
                    ta.removeClass(data[enable].sel).addClass(data[enable].newSel);
                    ta.find("span:first").removeClass(data[enable].rClasses).addClass(data[enable].aClasses);
                    ta.find("span:last").text(data[enable].text);
                    ta.closest("tr").find("td.t-elementStatus span").removeClass(data[enable].aClasses).addClass(data[enable].rClasses).attr("title", data[enable].title);
                }
            }, "html");
        }

        var deleteElements = function(ids){
            $.sendRequest("SearchDirectories/delete", {view: "' . $view . '", ids: ids}, function(){
                for(var i=0, l=ids.length; i<l; ++i){
                    $("#element_act_" + ids[i]).closest("tr").remove();
                }

                $.reCountElements( $("#directory_grid_view tbody") );
            }, "html");
        }


        $(function(){

            $("body").on("click", "div.t-ownActions li a.t-enableElement", function(){
                var id = getElement(this);
                enableElements([id], 1);
            });

            $("body").on("click", "div.t-ownActions li a.t-disableElement", function(){
                var id = getElement(this);
                enableElements([id], 0);
            });

            $("body").on("click", "div.t-mainActions li a.t-enableElements", function(){
                var ids = getElements();
                if(!ids.length){
                    alert("Необходимо для начала выбрать элементы справочника");
                } else {
                    enableElements(ids, 1);
                }
            });

            $("body").on("click", "div.t-mainActions li a.t-disableElements", function(){
                var ids = getElements();
                if(!ids.length){
                    alert("Необходимо для начала выбрать элементы справочника");
                } else {
                    enableElements(ids, 0);
                }

            });

            $("body").on("click", "div.t-mainActions li a.t-createElement", function(){
                $.showFade();
                window.location.href = "' . Yii::app()->createUrl('SearchDirectories/edit', ['view' => $view]) . '&country_id=" + getCountry() + "&dir_resort_id=" + getResort() + "&dir_city_id=" + getCity();
            });

            $("body").on("click", "div.t-ownActions li a.t-editElement", function(){
                $.showFade();
                var id = $(this).closest("div.t-ownActions").attr("id").replace("element_act_", "");
                window.location.href = "' . Yii::app()->createUrl('SearchDirectories/edit', ['view' => $view]) . '&id=" + id + "&country_id=" + getCountry();
            });

            $("body").on("click", "div.t-ownActions li a.t-deleteElement", function(){
                if(confirm("Внимание!!! \n Вы действительно хотите удалить этот элемент справочника ?")){
                    var id = getElement(this);
                    deleteElements([id]);
                }
            });

            $("body").on("click", "div.t-mainActions li a.t-deleteElements", function(){
                var ids = getElements();
                if(!ids.length){
                    alert("Необходимо для начала выбрать элементы справочника");
                } else {
                    if(confirm("Внимание!!! \n Вы действительно хотите удалить эти элементы справочника")){
                        deleteElements(ids);
                    }
                }
            });


        })

        $.blinkHash("element_id_");

    })(jQuery);',
    CClientScript::POS_READY
);
