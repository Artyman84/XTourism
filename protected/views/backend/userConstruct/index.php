<?php
/* @var $this UserConstructController */

$this->breadcrumbs=array(
    '<span class="fa fa-magic"></span> Конструктор лендингов'
);
?>

<div class="row">
    <div class="col-md-12">
        <a href="#" class="search-button"><span class="glyphicon glyphicon-triangle-right"></span>  Расширенный поиск <span class="glyphicon glyphicon-filter"></span></a>
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
        <div class="col-sm-8 text-left"><strong>Список конструкторов турагентов</strong></div>
        <div class="col-sm-2 col-sm-offset-2 text-right"></div>
        <div class="clearfix"></div>
    </div><?php

    $today = strtotime('midnight');
    $raw_products = Yii::app()->db->createCommand()
        ->select('uc.user_id, p.type_id')
        ->from('{{user_construct_domains}} AS uc')
        ->join('{{shop_users_packages}} AS up', 'up.user_id = uc.user_id')
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
        'id' => 'users_searcher_grid_view',
        'summaryText' => 'Конструкторы турагентов {start}&mdash;{end} из {count}',
        'summaryCssClass' => 'summary panel-summary',
        'itemsCssClass' => 'table table-hovered panel-table table-unbordered table-striped',
        'pagerCssClass' => 'panel-default-pager',
        'rowHtmlOptionsExpression' => '["user_id" => $data->user_id]',
        'columns' => array(

            array(
                'header' => '#',
                'headerHtmlOptions' => array('style' => 'width: 3%;'),
                'htmlOptions' => array('class' => 't-countElement'),
                'value' => '$row + 1'
            ),

            array(
                'name' => 'user_name',
                'type' => 'html',
                'value' => '$data->user ? CHtml::encode($data->user->userName()) : "<i>Агент удален</i>"',
            ),

            array(
                'name' => 'domain_name',
                'value' => 'CHtml::encode($data->domain_name)',
            ),

            array(
                'name' => 'is_purchased',
                'type' => 'raw',
                'value' => function($data){
                    return $data->is_purchased ? '<span class="fa fa-check text-success"></span>' : '<span class="fa fa-minus text-danger"></span>';
                },
            ),

            array(
                'name' => 'is_active',
                'type' => 'raw',
                'value' => function($data){
                    return $data->is_active ? '<span class="fa fa-check-circle-o text-success"></span>' : '<span class="fa fa-ban text-danger"></span>';
                },
            ),

            array(
                'htmlOptions' => array('class' => 'text-right'),
                'type' => 'raw',
                'value' => function($data) use( $products ) {
                    if( !isset($data->user->package) || !$data->user->package->isValid() || !isset($products[$data->user_id]) || !isset($products[$data->user_id][ArShopProductsTypes::PDT_LP_BUILDER]) ) {
                        return '<span class="text-danger"><span class="fa fa-ban"></span> Нет конструктора</span>';
                    } else {
                        // Actions if need..
                    }
                }
            )
        )
    ));?>

</div>