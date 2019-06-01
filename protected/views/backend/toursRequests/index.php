<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 30.08.2016
 * Time: 12:40
 */

$this->addCssFile('print', 'webroot.css', 'print');

$this->breadcrumbs=array(
    '<span class="flaticon-phone-auricular-and-a-clock" style="position: relative; top: 1px;"></span> Заявки на туры'
);?>


    <div class="panel panel-default" id="toursRequests_accepted" style="margin-top: 20px;">

        <div class="panel-heading">
            <div class="col-sm-8 text-left"><strong>Список заявок турагентов</strong></div>
            <div class="col-sm-2 col-sm-offset-2 text-right"></div>
            <div class="clearfix"></div>
        </div><?php

        $_agents = ArUsers::model()->findAll('role = "' . ArUsers::ROLE_AGENT . '"');
        $agents = [];
        foreach( $_agents as $agent ){
            $agents[$agent->id] = $agent->name . ' ' . $agent->lastname;
        }

        $this->widget('zii.widgets.grid.CGridView', [
            'dataProvider' => $model->search(),
            'filter' => $model,
            'htmlOptions' => array('class' => 'panel-grid-view grid-view'),
            'id' => 'tours_all_grid_view',
            'summaryText' => 'Заявки {start}&mdash;{end} из {count}',
            'afterAjaxUpdate' => 'reinstallDatePicker',
            'summaryCssClass' => 'summary panel-summary',
            'itemsCssClass' => 'table table-unbordered table-hovered panel-table table-striped table-filter',
            'pagerCssClass' => 'panel-default-pager',
            'rowHtmlOptionsExpression' => '["id" => "element_id_" . $data->id]',
            'columns' => array(

                [
                    'header' => '#',
                    'headerHtmlOptions' => array('style' => 'width: 2%;'),
                    'htmlOptions' => array('class' => 't-countElement'),
                    'value' => '$row + 1'
                ],

                [
                    'name' => 'id',
                    'headerHtmlOptions' => array('style' => 'width: 10%;'),
                    'type' => 'html',
                    'value' => 'CHtml::link($data->id, "#", ["class" => "t-view-tour-request"])'
                ],

                [
                    'name' => 'agent_id',
                    'type' => 'html',
                    'value' => '$data->agent ? CHtml::encode($data->agent->userName()) : "<i>Агент удален</i>"',
                    'filter' => $agents
                ],

                [
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
                ],

                [
                    'htmlOptions' => array('class' => 'text-right'),
                    'type' => 'html',
                    'value' => 'CHtml::link("<span class=\'glyphicon glyphicon-list-alt text-info\'></span>", "#", ["class" => "t-view-tour-request"])'
                ]
            )
        ]);?>

    </div>


<div class="modal fade" id="tourRequestInfo" info="" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>

<?  Yii::app()->clientScript->registerScript(
    "checkboxGroup",
    'function reinstallDatePicker(id, data) {
        $.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );
        $(".t-filter-datepicker").datepicker();
        $.hideFade();
        $.bindDatepickerEraser();
    }

    reinstallDatePicker();
    $("body").tooltip({selector: "[data-toggle=tooltip]"});


    $("body").on("click", ".t-view-tour-request", function() {

        var id = $.toInt( $(this).closest("tr").attr("id").replace("element_id_", "") );

        $.sendRequest("ToursRequests/tourInfo", {"id": id}, function(view){
            $("#tourRequestInfo .modal-content").get(0).innerHTML = view;
            $("#tourRequestInfo").attr("info", id);
            $("#tourRequestInfo").modal();
        }, "html");

    });
    ',
    CClientScript::POS_READY
);