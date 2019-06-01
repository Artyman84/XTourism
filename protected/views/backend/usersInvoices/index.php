
<?php $this->breadcrumbs=array(
    '<span class="flaticon-verification-of-delivery-list-clipboard-symbol"></span> Счета турагентов'
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
	$('#directory_grid_view').yiiGridView('update', {
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
        <?php  $this->renderPartial('search',['model' => $model]); ?>
    </div><!-- search-form -->
</div>
<br/>
<br/>


<div class="panel panel-default" id="elementsPanelID">

    <div class="panel-heading">
        <div class="col-sm-8 text-left"><strong>Список счетов</strong></div>
        <div class="col-sm-2 col-sm-offset-2 text-right"></div>
        <div class="clearfix"></div>
    </div><?php

    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(true),
        'htmlOptions' => array('class' => 'panel-grid-view grid-view'),
        'id' => 'directory_grid_view',
        'summaryText' => 'Счета {start}&mdash;{end} из {count}',
        'summaryCssClass' => 'summary panel-summary',
        'itemsCssClass' => 'table table-unbordered table-hovered panel-table table-striped',
        'pagerCssClass' => 'panel-default-pager',
        'rowHtmlOptionsExpression' => 'array("id" => $data->id, "user_id" => $data->user_id)',
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
                'name' => 'user_name',
                'type' => 'html',
                'value' => '$data->user ? CHtml::encode($data->user->userName()) : "<i>Агент удален</i>"'
            ),

            array(
                'name' => 'created_at',
                'value' => 'Yii::app()->dateFormatter->format("dd.MM.yyyy", $data->created_at)',
            ),

            array(
                'header' => '',
                'type' => 'raw',
                'value' => function(){ return '<a href="javascript://" class="t-invoice"><span class="flaticon-verification-of-delivery-list-clipboard-symbol text-primary"></span>&nbsp;<span>Квитанция</span></a>'; },
            ),

            array(
                'header' => '',
                'type' => 'raw',
                'value' => function(){ return '<a href="javascript://" class="t-history"><span class="glyphicon glyphicon-list-alt text-primary"></span>&nbsp;<span>История счетов</span></a>'; },
            ),

//            array(
//                'htmlOptions' => array('class' => 'text-right'),
//                'type' => 'raw',
//                'value' => function($data){
//
//                    $ret = '<div class="btn-group t-ownActions" id="element_act_' . $data->id . '">
//                            <a href="#" class="dropdown-toggle text-nowrap" data-toggle="dropdown">Действие&nbsp;<span class="caret"></span></a>
//                            <ul class="dropdown-menu backend-dropdown-menu" role="menu">
//                                <li><a href="javascript://" class="t-history"><span class="glyphicon glyphicon-list-alt text-primary"></span>&nbsp;<span>История счетов</span></a></li>
//                                <li><a href="javascript://" class="t-invoice"><span class="flaticon-verification-of-delivery-list-clipboard-symbol text-primary"></span>&nbsp;<span>Квитанция</span></a></li>
//                            </ul>
//                        </div>';
//
//                    return $ret;
//                }
//            )

        )
    ));?>

</div>

<?


Yii::app()->clientScript->registerScript(
    'checkboxGroup',
    ';
    (function($, undefined){
        $(function(){


            $("body").on("click", "a.t-history", function(){
                $.showFade();
                window.location.href = "' . Yii::app()->createUrl('UsersInvoices/history') . '/" + $.toInt($(this).closest("tr").attr("user_id"));
            });

            $("body").on("click", "a.t-invoice", function(){
                $.showFade();
                window.location.href = "' . Yii::app()->createUrl('UsersInvoices/invoice') . '/" + $.toInt($(this).closest("tr").attr("id"));
            });

        })

    })(jQuery);',
    CClientScript::POS_READY
);
