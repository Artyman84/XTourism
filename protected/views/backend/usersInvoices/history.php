
<?php $user = ArUsers::model()->findByPk($model->user_id);?>
<?php $this->breadcrumbs=array(
    '<span class="flaticon-verification-of-delivery-list-clipboard-symbol"></span> Счета турагентов' => Yii::app()->createUrl('UsersInvoices/index'),
    '<span class="flaticon-verification-of-delivery-list-clipboard-symbol"></span> История счетов - ' . ($user ? $user->userName() : '(Агент удален)')
);?>

<br>
<div class="panel panel-default" id="elementsPanelID">

    <div class="panel-heading">
        <div class="col-sm-8 text-left"><strong>История счетов</strong></div>
        <div class="col-sm-2 col-sm-offset-2 text-right"></div>
        <div class="clearfix"></div>
    </div><?php

    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(false),
        'htmlOptions' => array('class' => 'panel-grid-view grid-view'),
        'id' => 'directory_grid_view',
        'summaryText' => 'Счета {start}&mdash;{end} из {count}',
        'summaryCssClass' => 'summary panel-summary',
        'itemsCssClass' => 'table table-unbordered table-hovered panel-table table-striped',
        'pagerCssClass' => 'panel-default-pager',
        'rowCssClassExpression' => '$data->id == ' . ArShopInvoices::activeInvoiceId($model->user_id) . ' ? "success" : ""',
        'rowHtmlOptionsExpression' => 'array("id" => $data->id)',
        'columns' => array(
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
                'name' => 'created_at',
                'value' => 'Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm:ss", $data->created_at)',
            ),

            array(
                'header' => '',
                'type' => 'raw',
                'value' => function(){ return '<a href="javascript://" class="t-invoice"><span class="flaticon-verification-of-delivery-list-clipboard-symbol text-primary"></span>&nbsp;<span>Квитанция</span></a>'; },
            ),

        )
    ));?>

</div>

<? Yii::app()->clientScript->registerScript(
    'checkboxGroup',
    ';
    (function($, undefined){

        var getElement = function(sel){
            return $.toInt($(sel).closest("tr").find("td:first input:checkbox").val());
        };


        $(function(){

            $("body").on("click", "a.t-invoice", function(){
                $.showFade();
                var id = getElement(this);
                window.location.href = "' . Yii::app()->createUrl('UsersInvoices/invoice') . '/" + $.toInt($(this).closest("tr").attr("id"));
            });


        })

    })(jQuery);',
    CClientScript::POS_READY
);
