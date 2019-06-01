<?php

/**
 * @var CActiveDataProvider $search
 */

$this->breadcrumbs=array(
'<span class="flaticon-call-center-worker-with-headset"></span> Операторы туров'
);?>

<?php Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){

	if( $('.search-form').is(':hidden') ){
	    $(this).find('span:first').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
	} else {
	    $(this).find('span:first').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-right');
	}

	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#tour_operators_grid_view').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");?>

<div class="row">
    <div class="col-md-12">
        <a href="#" class="search-button"><span class="glyphicon glyphicon-triangle-right"></span> Расширенный поиск <span class="glyphicon glyphicon-filter"></span></a>
    </div>

    <div class="search-form col-md-12" style="display:none">
        <?php $this->renderPartial('_search',array(
            'model'=>$model,
        )); ?>
    </div><!-- search-form -->
</div>
<br/>
<br/>


<div class="panel panel-default" id="elementsPanelID">

    <div class="panel-heading">
        <div class="col-sm-8 text-left"><strong>Список операторов</strong></div>
        <div class="col-sm-2 col-sm-offset-2 text-right">
            <div class="btn-group t-mainActions">
                <a href="#" class="dropdown-toggle text-nowrap" data-toggle="dropdown"><strong>Действие</strong>&nbsp;<span class="caret"></span></a>
                <ul class="dropdown-menu backend-dropdown-menu" role="menu">
                    <li><a href="javascript://" class="t-createElement"><span class="glyphicon glyphicon-plus text-success"></span>&nbsp;Новый оператор</a></li>
                    <li><a href="javascript://" class="t-enableElements"><span class="glyphicon glyphicon-ok-circle text-success"></span>&nbsp;<span >Активировать</span></a></li>
                    <li><a href="javascript://" class="t-disableElements"><span class="glyphicon glyphicon-ban-circle text-danger"></span>&nbsp;<span >Заблокировать</span></a></li>
                    <li><a href="javascript://" class="t-deleteElements"><span class="glyphicon glyphicon-remove text-danger"></span>&nbsp;<span >Удалить</span></a></li>
                </ul>
            </div>
        </div>
        <div class="clearfix"></div>
    </div><?php

    $operators = [];
    foreach ($search->getData() as $o){
        $operators[] = $o->id;
    }

    $db = Yii::app()->db;
    $condition = ['AND', 'unread=1', ['IN', 'operator_id', $operators]];
    $new_countries = TUtil::listKey(
        $db->createCommand()->select('operator_id, COUNT(id) AS count')->from('{{operator_countries}}')->where($condition)->group('operator_id')->setFetchMode(PDO::FETCH_OBJ)->queryAll(),
        'operator_id'
    );

    $new_resorts = TUtil::listKey(
        $db->createCommand()->select('operator_id, COUNT(id) AS count')->from('{{operator_resorts}}')->where($condition)->group('operator_id')->setFetchMode(PDO::FETCH_OBJ)->queryAll(),
        'operator_id'
    );

    $new_hotels = TUtil::listKey(
        $db->createCommand()->select('h.operator_id, COUNT(h.id) AS count')->from('{{operator_hotels}} AS h')->join('{{operator_resorts}} AS r', 'r.element_id = h.resort AND r.operator_id = h.operator_id')->where(['AND', 'h.unread=1', ['IN', 'h.operator_id', $operators]])->group('h.operator_id')->setFetchMode(PDO::FETCH_OBJ)->queryAll(),
        'operator_id'
    );

    $new_meals = TUtil::listKey(
        $db->createCommand()->select('operator_id, COUNT(id) AS count')->from('{{operator_meals}}')->where($condition)->group('operator_id')->setFetchMode(PDO::FETCH_OBJ)->queryAll(),
        'operator_id'
    );

    $new_dep_cities = TUtil::listKey(
        $db->createCommand()->select('operator_id, COUNT(id) AS count')->from('{{operator_dep_cities}}')->where($condition)->group('operator_id')->setFetchMode(PDO::FETCH_OBJ)->queryAll(),
        'operator_id'
    );

    $new_hotel_categories = TUtil::listKey(
        $db->createCommand()->select('operator_id, COUNT(id) AS count')->from('{{operator_hotel_categories}}')->where($condition)->group('operator_id')->setFetchMode(PDO::FETCH_OBJ)->queryAll(),
        'operator_id'
    );

    $new_hotel_statuses = TUtil::listKey(
        $db->createCommand()->select('operator_id, COUNT(id) AS count')->from('{{operator_hotel_statuses}}')->where($condition)->group('operator_id')->setFetchMode(PDO::FETCH_OBJ)->queryAll(),
        'operator_id'
    );

    $new_ticket_statuses = TUtil::listKey(
        $db->createCommand()->select('operator_id, COUNT(id) AS count')->from('{{operator_ticket_statuses}}')->where($condition)->group('operator_id')->setFetchMode(PDO::FETCH_OBJ)->queryAll(),
        'operator_id'
    );


    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $search,
        'htmlOptions' => array('class' => 'panel-grid-view grid-view'),
        'id' => 'tour_operators_grid_view',
        'summaryText' => 'Операторы {start}&mdash;{end} из {count}',
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
                'type' => 'html',
                'value' => 'CHtml::link($data->name, Yii::app()->createUrl("SearchTourOperators/editOperator", array("id"=> $data->id)))',
            ),

            array(
                'header' => 'C+',
                'type' => 'html',
                'value' => function($data) use($new_countries){

                    if( !empty($new_countries[$data->id]) ) {
                        return CHtml::link('<span class="badge">' . $new_countries[$data->id]->count . '</span>', Yii::app()->createUrl('Migration/index', ['oid' => $data->id, 'tab' => 0]));
                    }
                },
            ),

            array(
                'header' => 'R+',
                'type' => 'html',
                'value' => function($data) use($new_resorts){

                    if( !empty($new_resorts[$data->id]) ) {
                        return CHtml::link('<span class="badge">' . $new_resorts[$data->id]->count . '</span>', Yii::app()->createUrl('Migration/index', ['oid' => $data->id, 'tab' => 1]));
                    }
                },
            ),

            array(
                'header' => 'H+',
                'type' => 'html',
                'value' => function($data) use($new_hotels){

                    if( !empty($new_hotels[$data->id]) ) {
                        return CHtml::link('<span class="badge">' . $new_hotels[$data->id]->count . '</span>', Yii::app()->createUrl('Migration/index', ['oid' => $data->id, 'tab' => 2]));
                    }
                },
            ),

            array(
                'header' => 'DC+',
                'type' => 'html',
                'value' => function($data) use($new_dep_cities){

                    if( !empty($new_dep_cities[$data->id]) ) {
                        return CHtml::link('<span class="badge">' . $new_dep_cities[$data->id]->count . '</span>', Yii::app()->createUrl('Migration/index', ['oid' => $data->id, 'tab' => 3]));
                    }
                },
            ),

            array(
                'header' => 'HC+',
                'type' => 'html',
                'value' => function($data) use($new_hotel_categories){

                    if( !empty($new_hotel_categories[$data->id]) ) {
                        return CHtml::link('<span class="badge">' . $new_hotel_categories[$data->id]->count . '</span>', Yii::app()->createUrl('Migration/index', ['oid' => $data->id, 'tab' => 4]));
                    }
                },
            ),

            array(
                'header' => 'M+',
                'type' => 'html',
                'value' => function($data) use($new_meals){

                    if( !empty($new_meals[$data->id]) ) {
                        return CHtml::link('<span class="badge">' . $new_meals[$data->id]->count . '</span>', Yii::app()->createUrl('Migration/index', ['oid' => $data->id, 'tab' => 5]));
                    }
                },
            ),

            array(
                'header' => 'HS+',
                'type' => 'html',
                'value' => function($data) use($new_hotel_statuses){

                    if( !empty($new_hotel_statuses[$data->id]) ) {
                        return CHtml::link('<span class="badge">' . $new_hotel_statuses[$data->id]->count . '</span>', Yii::app()->createUrl('Migration/index', ['oid' => $data->id, 'tab' => 6]));
                    }
                },
            ),

            array(
                'header' => 'TS+',
                'type' => 'html',
                'value' => function($data) use($new_ticket_statuses){

                    if( !empty($new_ticket_statuses[$data->id]) ) {
                        return CHtml::link('<span class="badge">' . $new_ticket_statuses[$data->id]->count . '</span>', Yii::app()->createUrl('Migration/index', ['oid' => $data->id, 'tab' => 7]));
                    }
                },
            ),

            array(
                'header' => 'Статус',
                'name' => 'blocked',
                'type' => 'html',
                'htmlOptions' => array('class' => 'text-center t-elementStatus'),
                'headerHtmlOptions' => array('style' => 'width: 3%;', 'class' => 'text-center'),
                'value' => function($data){
                    if($data->blocked) {
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
                                    <a href="javascript://" class="' . ($data->blocked ? 't-enableElement' : 't-disableElement') . '">
                                        <span class="glyphicon glyphicon-' . ($data->blocked ? 'ok-circle text-success' : 'ban-circle text-danger') . '"></span>
                                        <span>' . ($data->blocked ? 'Активировать' : 'Заблокировать') . '</span>
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

            $.sendRequest("SearchTourOperators/enableOperators", {ids: ids, enable: enable}, function(){

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
            $.sendRequest("SearchTourOperators/deleteOperators", {ids: ids}, function(){
                for(var i=0, l=ids.length; i<l; ++i){
                    $("#element_act_" + ids[i]).closest("tr").remove();
                }

                $.reCountElements( $("#tour_operators_grid_view tbody") );
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
                    alert("Необходимо для начала выбрать туроператоры");
                } else {
                    enableElements(ids, 1);
                }
            });

            $("body").on("click", "div.t-mainActions li a.t-disableElements", function(){
                var ids = getElements();
                if(!ids.length){
                    alert("Необходимо для начала выбрать туроператоры");
                } else {
                    enableElements(ids, 0);
                }

            });

            $("body").on("click", "div.t-mainActions li a.t-createElement", function(){
                $.showFade();
                window.location.href = "' . Yii::app()->createUrl('SearchTourOperators/editOperator') . '";
            });

            $("body").on("click", "div.t-ownActions li a.t-editElement", function(){
                $.showFade();
                var id = $(this).closest("div.t-ownActions").attr("id").replace("element_act_", "");
                window.location.href = "' . Yii::app()->createUrl('SearchTourOperators/editOperator') . '/" + id;
            });

            $("body").on("click", "div.t-ownActions li a.t-deleteElement", function(){
                if(confirm("Внимание!!! \n Удаление туроператора повлечет за собой безвозвратное удаление всех справочников данного туроператора, привязанных к поисковой системе туров! Выдействительно хотите продолжить удалени???")){
                    var id = getElement(this);
                    deleteElements([id]);
                }
            });

            $("body").on("click", "div.t-mainActions li a.t-deleteElements", function(){
                var ids = getElements();
                if(!ids.length){
                    alert("Необходимо для начала выбрать операторы");
                } else {
                    if(confirm("Внимание!!! \n Удаление туроператоров повлечет за собой безвозвратное удаление всех справочников удаляемых туроператора, привязанных к поисковой системе туров! Выдействительно хотите продолжить удалени???")){
                        deleteElements(ids);
                    }
                }
            });


        })

        $.blinkHash("element_id_");

    })(jQuery);',
    CClientScript::POS_READY
);
