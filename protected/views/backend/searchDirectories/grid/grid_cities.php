
<?php $this->breadcrumbs=array(
    '<span class="fa fa-book"></span> ' . ArDirectorySearch::getTableName($view)
);?>

<?php Yii::app()->clientScript->registerScript('search', "
$('body').on('click', '.search-button', function(){

	if( $('.search-form').is(':hidden') ){
	    $(this).find('span:first').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
	} else {
	    $(this).find('span:first').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-right');
	}

	$('.search-form').toggle();
	return false;
});

$('.t-main-search-form select').change(function(){
    $(this).closest('form').submit();
});

$('body').on('submit', '.search-form form, .t-main-search-form', function(){

    var forms = $(this).hasClass('t-main-search-form') ? '.t-main-search-form' : '.t-main-search-form, .search-form form';

	$('#directory_grid_view').yiiGridView('update', {
		data: $(forms).serialize(),
		complete: function(jqXHR, status) {
            if (status=='success'){
                var data = $('<div>' + jqXHR.responseText + '</div>');

                if( $('.search-form').is(':hidden') ){
                    $('.search-form', data).hide();
                } else {
                    $('.search-form', data).show();
                }

                $('.search-form').replaceWith($('.search-form', data));
            }
        }
	});
	return false;
});
");?>

<div class="row">
    <div class="col-md-12">
        <a href="#" class="search-button"><span class="glyphicon glyphicon-triangle-right"></span> Расширенный поиск <span class="glyphicon glyphicon-filter"></span></a>
    </div>

    <div class="search-form col-md-12" style="display:none">
        <?php $this->renderPartial('search/search_cities', ['model' => $model, 'regions' => $regions]); ?>
    </div>
</div>
<br/>
<br/>

<div class="panel panel-default" id="elementsPanelID">

    <div class="panel-heading">
        <div class="col-sm-2 text-left" style="margin-top: 4px;"><strong>Список элементов</strong></div>

        <div class="col-sm-1 input-group-sm text-right" style="margin-top: 4px;">
            <?php echo CHtml::label('Страны', CHtml::activeId($model, 'dir_country_id'), ['class' => 'small']); ?>
        </div>

        <div class="col-sm-3">

            <?php $form=$this->beginWidget('CActiveForm', [
                'action'=>Yii::app()->createUrl($this->route),
                'htmlOptions' => [ 'class' => 't-main-search-form' ],
                'method'=>'get',
            ]); ?>
            <div class="input-group-sm" >
                <?php echo $form->dropDownList($model, 'dir_country_id', $countries , ['class' => 'form-control', 'value' => $country_id]); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>

        <div class="col-sm-2 col-sm-offset-4 text-right">

        </div>

        <div class="clearfix"></div>
    </div><?php

    $columns = [
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
        )
    ];

    if( isset( $regions[ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_ISLAND)] ) ){
        $columns[] = [
            'name' => 'island',
            'type' => 'raw',
            'value' => function($data){
                $name = '';
                foreach( $data->region as $region ){
                    if( $region->type == ArDirRegions::REG_TYPE_ISLAND ){
                        $name = $region->name;
                        break;
                    }
                }

                return $name;
            },
        ];
    }

    if( isset( $regions[ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_PROVINCE)] ) ){
        $columns[] = array(
            'name' => 'province',
            'type' => 'raw',
            'value' => function($data){
                $name = '';
                foreach( $data->region as $region ){
                    if( $region->type == ArDirRegions::REG_TYPE_PROVINCE ){
                        $name = $region->name;
                        break;
                    }
                }

                return $name;
            },
        );
    }

    if( isset( $regions[ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_REGION)] ) ){
        $columns[] = [
            'name' => 'free_region',
            'type' => 'raw',
            'value' => function($data){
                $name = '';
                foreach( $data->region as $region ){
                    if( $region->type == ArDirRegions::REG_TYPE_REGION ){
                        $name = $region->name;
                        break;
                    }
                }

                return $name;
            },
        ];
    }

    if( isset( $regions[ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_DISTRICT)] ) ){
        $columns[] = [
            'name' => 'district',
            'type' => 'raw',
            'value' => function($data){
                $name = '';
                foreach( $data->region as $region ){
                    if( $region->type == ArDirRegions::REG_TYPE_DISTRICT ){
                        $name = $region->name;
                        break;
                    }
                }

                return $name;
            },
        ];
    }

    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
        'htmlOptions' => array('class' => 'panel-grid-view grid-view'),
        'id' => 'directory_grid_view',
        'summaryText' => 'Элементы {start}&mdash;{end} из {count}',
        'summaryCssClass' => 'summary panel-summary',
        'itemsCssClass' => 'table table-unbordered table-hovered panel-table table-striped',
        'pagerCssClass' => 'panel-default-pager',
        'rowHtmlOptionsExpression' => '["id" => "element_id_" . $data->id]',
        'columns' => $columns
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
    );',
    CClientScript::POS_READY
);
