<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 09.04.2015
 * Time: 9:55
 */

$this->breadcrumbs=array(
    '<span class="fa fa-users"></span> Персонал'
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
	$('#users_grid_view').yiiGridView('update', {
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
            <?php $this->renderPartial('__search',array(
                'model'=>$model,
            )); ?>
        </div><!-- search-form -->
</div>
<br/>
<br/>

<div class="panel panel-default" id="elementsPanelID">

    <div class="panel-heading">
        <div class="col-sm-8 text-left"><strong>Список пользователей</strong></div>
        <div class="col-sm-2 col-sm-offset-2 text-right">
            <div class="btn-group t-mainActions" style="width: 110px;">
                <a href="#" class="dropdown-toggle text-nowrap" data-toggle="dropdown"><strong>Действие</strong>&nbsp;<span class="caret"></span></a>
                <ul class="dropdown-menu backend-dropdown-menu" role="menu">
                    <li><a href="javascript://" class="t-createElement"><i class="fa fa-user-plus text-success"></i>&nbsp;Новый пользователь</a></li>
                    <li><a href="javascript://" class="t-enableElements"><span class="glyphicon glyphicon-ok-circle text-success"></span>&nbsp;<span >Активировать</span></a></li>
                    <li><a href="javascript://" class="t-disableElements"><span class="glyphicon glyphicon-ban-circle text-danger"></span>&nbsp;<span >Заблокировать</span></a></li>
                    <li><a href="javascript://" class="t-deleteElements"><i class="fa fa-user-times text-danger"></i>&nbsp;<span >Удалить</span></a></li>
                </ul>
            </div>
        </div>
        <div class="clearfix"></div>
    </div><?php

    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $search,
        'htmlOptions' => array('class' => 'panel-grid-view grid-view'),
        'id' => 'users_grid_view',
        'summaryText' => 'Пользователи {start}&mdash;{end} из {count}',
        'summaryCssClass' => 'summary panel-summary',
        'itemsCssClass' => 'table table-hovered panel-table table-unbordered',
        'pagerCssClass' => 'panel-default-pager',
        'rowCssClassExpression' => '$data->role == "guest" ? "warning" : ""',
        'rowHtmlOptionsExpression' => 'array("id" => "element_id_" . $data->id, "role" => $data->role, "class" => Yii::app()->user->id == $data->id ? "success" : "")',
        'columns' => array(
            array(
                'header' => '<div class="xtourism-checkbox">
                               <input type="checkbox" value="1" class="ch-parent">
                               <span class="glyphicon glyphicon-unchecked"></span>
                           </div>',

                'type' => 'raw',
                'headerHtmlOptions' => array('style' => 'width: 2%;'),
                'value' => function($data){
                    $user = Yii::app()->user;
                    if( $data->role == ArUsers::ROLE_MODERATOR || $data->role == ArUsers::ROLE_AGENT || ($user->role == ArUsers::ROLE_SUPERADMIN && $data->role != ArUsers::ROLE_SUPERADMIN ) ){
                        return '<div class="xtourism-checkbox">
                                    <input type="checkbox" value="' . $data->id . '" class="ch-child">
                                    <span class="glyphicon glyphicon-unchecked"></span>
                                </div>';
                    }
                }
            ),

            array(
                'header' => '#',
                'headerHtmlOptions' => array('style' => 'width: 2%;'),
                'htmlOptions' => array('class' => 't-countElement'),
                'value' => '$row + 1'
            ),

            array(
                'name' => 'id',
                'headerHtmlOptions' => array('style' => 'width: 4%;'),
            ),

            array(
                'name' => 'role',
                'type' => 'raw',
                'headerHtmlOptions' => array('style' => 'width: 14%;'),
                'value' => function($data){
                    if( $data->role == 'guest' ){
                        return "<span class=\"text-danger glyphicon glyphicon-minus\" title=\"Не подтвержден\"></span>";
                    } else{
                        return '<span class="text-success font-bold">' . ArUsers::roleName($data->role, true) . '</span>';
                    }

                },
            ),

            array(
                'name' => 'name',
                'headerHtmlOptions' => array('style' => 'width: 10%;'),
            ),

            array(
                'name' => 'lastname',
                'headerHtmlOptions' => array('style' => 'width: 15%;'),
            ),

            array(
                'name' => 'email',
                'headerHtmlOptions' => array('style' => 'width: 15%;'),
            ),

            array(
                'name' => 'city_id',
                'value' => 'isset($data->city) ? $data->city->name : ""',
                'htmlOptions' => array('class' => 'text-center'),
                'headerHtmlOptions' => array('style' => 'width: 15%;', 'class' => 'text-center'),
            ),

            array(
                'name' => 'state',
                'type' => 'raw',
                'value' => function($data){
                    if( $data->role == 'guest' ){
                        return "<span class=\"text-danger glyphicon glyphicon-minus\" title=\"Не подтвержден\"></span>";
                    } elseif($data->state) {
                        return "<span class=\"text-danger glyphicon glyphicon-ban-circle\" title=\"Заблокирован\"></span>";
                    } else {
                        return "<span class=\"text-success glyphicon glyphicon-ok-circle\" title=\"Активен\"></span>";
                    }
                },
                'htmlOptions' => array('class' => 'text-center t-elementStatus'),
                'headerHtmlOptions' => array('style' => 'width: 3%;', 'class' => 'text-center'),
            ),

            array(
                'headerHtmlOptions' => array('style' => 'width: 18%;'),
                'htmlOptions' => array('class' => 'text-right'),
                'type' => 'raw',
                'value' => function($data){
                    $user = Yii::app()->user;

                    if( $data->role == 'guest' ){

                        $ret = '<div class="btn-group t-ownActions" id="element_act_' . $data->id . '">
                                    <button type="button" class="btn btn-xs btn-success t-acceptAgent"><span class="glyphicon glyphicon-thumbs-up"></span> Подтвердить</button>
                                    <button type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu backend-dropdown-menu" role="menu">
                                        <li><a href="javascript://" class="t-profile"><span class="glyphicon glyphicon-user text-warning"></span>&nbsp;Профайл</a></li>
                                        <li class="divider"></li>
                                        <li><a href="javascript://" class="t-acceptAgent"><span class="glyphicon glyphicon-thumbs-up text-success"></span>&nbsp;<span >Подтвердить</span></a></li>
                                        <li><a href="javascript://" class="t-declineAgent"><span class="glyphicon glyphicon-thumbs-down text-danger"></span>&nbsp;<span >Отклонить</span></a></li>
                                    </ul>
                                </div>';

                    } else {

                        $ret = '<div class="btn-group t-ownActions" id="element_act_' . $data->id . '">
                                    <a href="#" class="dropdown-toggle text-nowrap" data-toggle="dropdown">Действие&nbsp;<span class="caret"></span></a>
                                    <ul class="dropdown-menu backend-dropdown-menu" role="menu">
                                        <li><a href="javascript://" class="t-profile"><span class="glyphicon glyphicon-user text-warning"></span>&nbsp;Профайл</a></li>';

                        if( $data->role == ArUsers::ROLE_MODERATOR || $data->role == ArUsers::ROLE_AGENT || ($user->role == ArUsers::ROLE_SUPERADMIN && $data->role != ArUsers::ROLE_SUPERADMIN ) ){
                            $ret .= '<li>
                                        <a href="javascript://" class="' . ($data->state ? 't-enableElement' : 't-disableElement') . '">
                                            <span class="glyphicon glyphicon-' . ($data->state ? 'ok-circle text-success' : 'ban-circle text-danger') . '"></span>
                                            <span>' . ($data->state ? 'Активировать' : 'Заблокировать') . '</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript://" class="t-deleteElement"><i class="fa fa-user-times text-danger"></i>&nbsp;<span >Удалить</span></a>
                                    </li>';
                        }

                        $ret .= '</ul></div>';
                    }

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

            $.sendRequest("Users/enableUsers", {ids: ids, enable: enable}, function(){

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
            $.sendRequest("Users/deleteUsers", {ids: ids}, function(){
                for(var i=0, l=ids.length; i<l; ++i){
                    $("#element_act_" + ids[i]).closest("tr").remove();
                }

                $.reCountElements( $("#users_grid_view tbody") );
            }, "html");
        }

        var declineAgents = function(ids){
            $.sendRequest("Users/declineAgents", {ids: ids}, function(){
                for(var i=0, l=ids.length; i<l; ++i){
                    $("tr#element_id_" + ids[i]).remove();
                }

                $.reCountElements( $("#users_grid_view tbody") );
            }, "html");
        }

        var acceptAgents = function(ids) {
            $.sendRequest("Users/acceptAgents", {ids: ids}, function() {
                $.fn.yiiGridView.update(
                    "users_grid_view",
                    {
                        "complete": function(jqXHR, status){
                            if( status == "success" ){
                                for(var i=0, l=ids.length; i<l; ++i){
                                    $.blinkElement("tr#element_id_" + ids[i]);
                                }
                            };
                        }
                    }
                );

            }, "html");
        }

        var editElement = function(id){
            $.showFade();
            window.location.href = "' . Yii::app()->createUrl('Users/editUser') . '/" + (id === undefined ? "" : id);
        }

        var profile = function(id){
            $.showFade();
            window.location.href = "' . Yii::app()->createUrl('Users/profile') . '/" + id;
        }

        var createUser = function(){
            $.showFade();
            window.location.href = "' . Yii::app()->createUrl('Users/createUser') . '";
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
                    alert("Необходимо для начала выбрать пользователей");
                } else {
                    enableElements(ids, 1);
                }
            });

            $("body").on("click", "div.t-mainActions li a.t-disableElements", function(){
                var ids = getElements();
                if(!ids.length){
                    alert("Необходимо для начала выбрать пользователей");
                } else {
                    enableElements(ids, 0);
                }

            });


            $("body").on("click", "div.t-ownActions li a.t-deleteElement", function(){
                if(confirm("Вы действительно хотите удалить этого пользователя?")){
                    var id = getElement(this);
                    deleteElements([id]);
                }
            });

            $("body").on("click", "div.t-mainActions li a.t-deleteElements", function(){
                var ids = getElements();
                if(!ids.length){
                    alert("Необходимо для начала выбрать пользователей");
                } else {
                    if(confirm("Вы действительно хотите удалить этого пользователя?")){
                        deleteElements(ids);
                    }
                }
            });

            $("body").on("click", "div.t-ownActions .t-acceptAgent", function(){
                var id = $.toInt($(this).closest("tr").attr("id").replace("element_id_", ""));
                acceptAgents([id]);
            });

            $("body").on("click", "div.t-ownActions .t-declineAgent", function(){
                var id = $.toInt($(this).closest("tr").attr("id").replace("element_id_", ""));
                if(confirm("Вы действительно хотите отклонить предложение этого турагента и удалить его из базы?")){
                    declineAgents([id]);
                }

            });

            $("body").on("click", "div.t-mainActions li a.t-createElement", function(){
                createUser();
            });

            $("body").on("click", "div.t-ownActions li a.t-profile", function(){
                var id = getElement(this);
                editElement(id);
            });

            $("body").on("click", "div.t-ownActions li a.t-profile", function(){
                var id = $(this).closest(".t-ownActions").attr("id").replace("element_act_", "");
                profile(id);
            });

            $.blinkHash("element_id_");
        })

    })(jQuery);',

    CClientScript::POS_READY
);