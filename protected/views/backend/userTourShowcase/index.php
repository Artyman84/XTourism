<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 02.06.2015
 * Time: 11:49
 */

$this->breadcrumbs=array(
    '<span class="glyphicon glyphicon-th"></span> Витрины туров'
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
	$('#users_showcase_grid_view').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");?>

<div class="row">
    <div class="col-md-12">
        <a href="#" class="search-button"><span class="glyphicon glyphicon-triangle-right"></span>  Расширенный поиск <span class="glyphicon glyphicon-filter"></span></a>
    </div>

    <div class="search-form col-md-12" style="display:none">
        <?php $this->renderPartial('_search', ['model'=>$model]); ?>
    </div><!-- search-form -->
</div>
<br/>
<br/>


<div class="panel panel-default" id="elementsPanelID">

    <div class="panel-heading">
        <div class="col-sm-8 text-left"><strong>Список витрин турагентов</strong></div>
        <div class="col-sm-2 col-sm-offset-2 text-right"></div>
        <div class="clearfix"></div>
    </div><?php

    $today = strtotime('midnight');
    $raw_products = Yii::app()->db->createCommand()
        ->select('us.user_id, p.type_id')
        ->from('{{user_tour_showcase}} AS us')
        ->join('{{shop_users_packages}} AS up', 'up.user_id = us.user_id')
        ->join('{{shop_users_products_to_packages}} AS p_to_p', 'p_to_p.user_package_id = up.id')
        ->join('{{shop_products}} AS p', 'p.id = p_to_p.product_id')
        ->where('up.start <= :today AND up.expired >= :today', [':today' => $today])
        ->setFetchMode(PDO::FETCH_OBJ)
        ->queryAll();

    $products = [];
    foreach( $raw_products as $product){
        $products[$product->user_id][$product->type_id] = 1;
    }

    unset($raw_products);

    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
        'htmlOptions' => array('class' => 'panel-grid-view grid-view'),
        'id' => 'users_showcase_grid_view',
        'summaryText' => 'Витрины турагентов {start}&mdash;{end} из {count}',
        'summaryCssClass' => 'summary panel-summary',
        'itemsCssClass' => 'table table-hovered panel-table table-unbordered table-striped',
        'pagerCssClass' => 'panel-default-pager',
        'rowHtmlOptionsExpression' => '["id" => $data->id, "user_id" => $data->user_id]',
        'columns' => array(

            array(
                'header' => '#',
                'headerHtmlOptions' => array('style' => 'width: 3%;'),
                'htmlOptions' => array('class' => 't-countElement'),
                'value' => '$row + 1'
            ),

            array(
                'name' => 'id',
                'headerHtmlOptions' => array('style' => 'width: 3%;'),
            ),

            array(
                'name' => 'user_name',
                'value' => '$data->user->userName();',
            ),

            array(
                'htmlOptions' => array('class' => 'text-right'),
                'type' => 'raw',
                'value' => function($data) use( $products ){
                    $menu_items = '<span class="text-danger"><span class="fa fa-ban"></span> Нет витрины</span>';

                    if( isset($data->user->package) ) {
                        $package = $data->user->package;

                        if( $package->isValid() && isset($products[$data->user_id]) && isset($products[$data->user_id][ArShopProductsTypes::PDT_TOUR_SHOWCASE]) ) {
                            $menu_items = '<a href="javascript://" title="Внешний вид" class="t-showcaseSettings"><span class="fa fa-th text-primary"></span></a>&nbsp;
                                           <a href="javascript://" title="Настройки" class="t-showcaseValues"><span class="fa fa-sliders text-primary"></span></a>&nbsp;
                                           <a href="javascript://" title="Фильтры" class="t-showcaseFilters"><span class="fa fa-filter text-primary"></span></a>&nbsp;';
                        }

                    }

                    return $menu_items;
                }
            )
        )
    ));?>

</div>

<?

$js = <<<JS
;
    (function($, undefined){

        $(function(){

            $("body").on("click", "a.t-showcaseSettings", function(){
                $.showFade();
                window.location.href = $.createUrl("UserTourShowcase/Settings", {"user_id": $(this).closest("tr").attr("user_id")});
            });

            $("body").on("click", "a.t-showcaseValues", function(){
                $.showFade();
                window.location.href = $.createUrl("UserTourShowcase/Values", {"user_id": $(this).closest("tr").attr("user_id")});
            });

            $("body").on("click", "a.t-showcaseFilters", function(){
                $.showFade();
                window.location.href = $.createUrl("UserTourShowcase/Filters/" + $(this).closest("tr").attr("id"), {user_id: $(this).closest("tr").attr("user_id")});
            });
        })

    })(jQuery);
JS;


Yii::app()->clientScript->registerScript( 'UserTourShowcase', $js, CClientScript::POS_READY );