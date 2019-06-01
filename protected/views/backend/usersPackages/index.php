
<?php $this->breadcrumbs=array(
    '<span class="fa fa-briefcase i-margin"></span> Активные пакеты турагентов'
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
        <div class="col-sm-2 col-sm-offset-2 text-right"></div>
        <div class="clearfix"></div>
    </div><?php
    $today = strtotime('midnight');

    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
        'htmlOptions' => array('class' => 'panel-grid-view grid-view'),
        'id' => 'directory_grid_view',
        'summaryText' => 'Пакеты {start}&mdash;{end} из {count}',
        'summaryCssClass' => 'summary panel-summary',
        'itemsCssClass' => 'table table-unbordered table-hovered panel-table table-striped',
        'pagerCssClass' => 'panel-default-pager',
        'rowCssClassExpression' => '$data->start <= ' . $today . ' && $data->expired > ' . $today . ' ? "success" : ($data->start > ' . $today . ' ? "info" : "danger")',
        'rowHtmlOptionsExpression' => '["id" => "element_id_" . $data->id ]',
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
                'name' => 'name',
            ),

            array(
                'name' => 'start',
                'value' => 'Yii::app()->dateFormatter->format("dd.MM.yyyy", $data->start)',
            ),

            array(
                'name' => 'expired',
                'value' => 'Yii::app()->dateFormatter->format("dd.MM.yyyy", $data->expired)',
            ),

            array(
                'header' => 'Продукты',
                'type' => 'raw',
                'value' => function($data) {
                    $ret = '<ul style="padding-left: 15px;">';
                    foreach( $data->products as $product ) {
                        $ret .= '<li>' . CHtml::encode($product->name) . '</li>';
                    }
                    $ret .= '</ul>';

                    return $ret;
                }
            )

        )
    ));?>

</div>