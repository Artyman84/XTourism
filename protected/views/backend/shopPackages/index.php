
<?php $this->breadcrumbs=array(
    '<span class="flaticon-delivery-package-opened"></span> Пакеты'
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
        <div class="col-sm-8 text-left"><strong>Список пакетов</strong></div>
        <div class="col-sm-2 col-sm-offset-2 text-right">
            <div class="btn-group t-mainActions">
                <a href="#" class="dropdown-toggle text-nowrap" data-toggle="dropdown"><strong>Действие</strong>&nbsp;<span class="caret"></span></a>
                <ul class="dropdown-menu backend-dropdown-menu" role="menu">
                    <li><a href="javascript://" class="t-createElement"><span class="glyphicon glyphicon-plus text-success"></span>&nbsp;Новый пакет</a></li>
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
        'summaryText' => 'Пакеты {start}&mdash;{end} из {count}',
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
                'headerHtmlOptions' => ['style' => 'width: 2%;'],
            ),

            array(
                'header' => '#',
                'headerHtmlOptions' => ['style' => 'width: 2%;'],
                'htmlOptions' => ['class' => 't-countElement'],
                'value' => '$row + 1'
            ),

            array(
                'name' => 'id',
                'headerHtmlOptions' => ['style' => 'width: 5%;', 'class' => 'text-nowrap'],
            ),

            array(
                'headerHtmlOptions' => ['class' => 'text-nowrap'],
                'name' => 'name',
                'type' => 'html',
                'value' => 'CHtml::link($data->name, Yii::app()->createUrl("ShopPackages/edit", ["id"=> $data->id]))',
            ),

            array(
                'headerHtmlOptions' => ['class' => 'text-nowrap'],
                'htmlOptions' => ['class' => 'text-nowrap'],
                'name' => 'period',
                'type' => 'html',
                'value' => 'ArShopPackages::periodName($data->period)',
            ),

            array(
                'headerHtmlOptions' => ['class' => 'text-nowrap'],
                'name' => 'price_uah',
            ),

            array(
                'headerHtmlOptions' => ['class' => 'text-nowrap'],
                'name' => 'price_rub',
            ),

            array(
                'htmlOptions' => array('class' => 'text-right'),
                'type' => 'raw',
                'value' => function($data){

                    $ret = '<div class="btn-group t-ownActions" id="element_act_' . $data->id . '">
                            <a href="#" class="dropdown-toggle text-nowrap" data-toggle="dropdown">Действие&nbsp;<span class="caret"></span></a>
                            <ul class="dropdown-menu backend-dropdown-menu" role="menu">
                                <li><a href="javascript://" class="t-editElement"><span class="glyphicon glyphicon-edit text-warning"></span>&nbsp;Редактировать</a></li>
                                <li><a href="javascript://" class="t-deleteElement"><span class="glyphicon glyphicon-remove text-danger"></span>&nbsp;<span >Удалить</span></a></li>
                            </ul>
                        </div>';

                    return $ret;
                }
            )

        )
    ));?>

</div>

<?


Yii::app()->clientScript->registerScript(
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

        var deleteElements = function(ids){
            $.sendRequest("ShopPackages/delete", {ids: ids}, function(){

                for(var i=0, l=ids.length; i<l; ++i){
                    $("#element_act_" + ids[i]).closest("tr").remove();
                }

                $.reCountElements( $("#directory_grid_view tbody") );
            }, "html");
        }


        $(function(){

            $("body").on("click", "div.t-mainActions li a.t-createElement", function(){
                $.showFade();
                window.location.href = "' . Yii::app()->createUrl('ShopPackages/edit') . '";
            });

            $("body").on("click", "div.t-ownActions li a.t-editElement", function(){
                $.showFade();
                var id = $(this).closest("div.t-ownActions").attr("id").replace("element_act_", "");
                window.location.href = "' . Yii::app()->createUrl('ShopPackages/edit') . '/" + id;
            });

            $("body").on("click", "div.t-ownActions li a.t-deleteElement", function(){
                if(confirm("Внимание!!! \n Вы действительно хотите удалить этот пакет ?")){
                    var id = getElement(this);
                    deleteElements([id]);
                }
            });

            $("body").on("click", "div.t-mainActions li a.t-deleteElements", function(){
                var ids = getElements();
                if(!ids.length){
                    alert("Необходимо для начала выбрать продукты");
                } else {
                    if(confirm("Внимание!!! \n Вы действительно хотите удалить эти пакет")){
                        deleteElements(ids);
                    }
                }
            });


        })


        $.blinkHash("element_id_");

    })(jQuery);',
    CClientScript::POS_READY
);
