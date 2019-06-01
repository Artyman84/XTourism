<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 10.09.2016
 * Time: 9:56
 */

?>

    <div class="panel panel-default" id="toursRequests_accepted" style="margin-top: 20px;">

        <div class="panel-heading">
            <div class="col-sm-8 text-left"><strong>Список заявок</strong></div>
            <div class="col-sm-2 col-sm-offset-2 text-right">
                <div class="btn-group t-mainActions">
                    <a href="#" class="dropdown-toggle text-nowrap" data-toggle="dropdown"><strong>Действие</strong>&nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu gridview-dropdown-menu" role="menu">
                        <li><a href="javascript://" class="t-delete-tours"><span class="glyphicon glyphicon-remove text-danger"></span>&nbsp;<span >Удалить</span></a></li>
                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
        </div><?php

        $model->state = 1;
        $this->widget('zii.widgets.grid.CGridView', [
            'dataProvider' => $model->search(),
            'filter' => $model,
            'htmlOptions' => array('class' => 'panel-grid-view grid-view'),
            'id' => 'tours_accepted_grid_view',
            'summaryText' => 'Заявки {start}&mdash;{end} из {count}',
            'afterAjaxUpdate' => 'reinstallDatePicker',
            'summaryCssClass' => 'summary panel-summary',
            'itemsCssClass' => 'table table-unbordered table-hovered panel-table table-striped table-filter',
            'pagerCssClass' => 'panel-default-pager',
            'rowCssClassExpression' => '$data->state == 2 ? "warning" : "";',
            'rowHtmlOptionsExpression' => '["id" => "element_id_" . $data->id]',
            'columns' => array(
                array(
                    'header' => '<div class="xtourism-checkbox">
                               <input type="checkbox" value="1" class="ch-parent">
                               <span class="glyphicon glyphicon-unchecked"></span>
                           </div>',

                    'type' => 'raw',
                    'headerHtmlOptions' => array('style' => 'width: 2%;'),
                    'value' => function($data){
                        return '<div class="xtourism-checkbox">
                                    <input type="checkbox" value="' . $data->id . '" class="ch-child">
                                    <span class="glyphicon glyphicon-unchecked"></span>
                                </div>';
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
                    'headerHtmlOptions' => array('style' => 'width: 10%;'),
                    'type' => 'html',
                    'value' => 'CHtml::link($data->id, "#", ["class" => "t-view-tour-request"])'
                ),

                array(
                    'name' => 'client_name',
                ),

                array(
                    'name' => 'client_phone',
                ),

                array(
                    'name' => 'client_email',
                ),

                array(
                    'name' => 'created_at',
                    'value' => 'Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm", $data->created_at)',
                    'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', [
                        'model' => $model,
                        'attribute' => 'created_at',
                        'language' => 'ru',
                        'i18nScriptFile' => 'jquery-ui-i18n.min.js',
                        'htmlOptions' => [
                            'id' => 'datepicker_created_at_accepted',
                            'class' => 't-filter-datepicker'
                        ],
                        'defaultOptions' => [
                            'showOn' => 'focus',
                        ]
                    ],
                        true)
                ),

                array(
                    'htmlOptions' => array('class' => 'text-right'),
                    'type' => 'raw',
                    'value' => function($data){

                        return '
                            <div class="btn-group t-ownActions" id="tour_act_' . $data->id . '">
                                <a href="#" class="dropdown-toggle text-nowrap" data-toggle="dropdown">Действие&nbsp;<span class="caret"></span></a>
                                <ul class="dropdown-menu gridview-dropdown-menu" role="menu">
                                    <li><a href="javascript://" class="t-view-tour-request"><span class="glyphicon glyphicon-list-alt text-info"></span>&nbsp;Посмотреть заявку</a></li>

                                    <li class="divider"></li>

                                    <li><a href="javascript://" class="t-delete-tour"><span class="glyphicon glyphicon-remove text-danger"></span>&nbsp;<span >Удалить</span></a></li>
                                </ul>
                            </div>';
                    }
                )
            )
        ]);?>

    </div>

<?  Yii::app()->clientScript->registerScript(
    "tours_accepted",
    '$.initCheckboxGroup(
        "toursRequests_accepted",
        function(input){
            input.closest("tr").addClass("info");
        },
        function(input){
            input.closest("tr").removeClass("info");
        }
    )

    ;(function($, undefined){

        var wrapId = "#toursRequests_accepted";

        var getElements = function() {
            var ids = [];
            $("div#toursRequests_accepted input.ch-child:checked").each(function(){
                ids[ids.length] = $.toInt($(this).val());
            });

            return ids;
        };

        var getElement = function(sel){
            return $.toInt($(sel).closest("tr").find("td:first input:checkbox").val());
        };

        var deleteElements = function(ids){
            $.sendRequest("ToursRequests/delete", {ids: ids}, function(rows){

                for(var i=0, l=ids.length; i<l; ++i){
                    $("#tour_act_" + ids[i]).closest("tr").remove();
                }

                $.reCountElements( $("#toursRequests_accepted tbody") );
                updateToursRequestsCount(rows);

            }, "html");
        }


        $(function(){

            $("body").on("click", wrapId + " div.t-ownActions li a.t-delete-tour", function(){
                if(confirm("Внимание!!! \n Вы действительно хотите удалить эту заявку ?")){
                    var id = getElement(this);
                    deleteElements([id]);
                }
            });

            $("body").on("click", wrapId + " div.t-mainActions li a.t-delete-tours", function(){
                var ids = getElements();
                if(!ids.length){
                    alert("Необходимо для начала выбрать заявки");
                } else {
                    if(confirm("Внимание!!! \n Вы действительно хотите удалить эти заявки")){
                        deleteElements(ids);
                    }
                }
            });
        });


    })(jQuery);

    ',
    CClientScript::POS_READY
);