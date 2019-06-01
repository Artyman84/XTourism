<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 09.04.2015
 * Time: 9:55
 * @var ArUserTourShowcase $userShowcase
 */

$this->breadcrumbs=array(
    '<span class="fa fa-search"></span> Поисковик туров'
);?>

<? $this->showFlashMessage('searcher_design');?>
<? $this->showFlashMessage('searcher_default_values');?>
<? $this->showFlashMessage('searcher_filters');?>

<ul id="myTab" class="nav nav-pills" style="margin-bottom: 15px;">
    <li <?=($tab == 0 ? 'class="active"' : '')?>><a href="#searcher_code" tab="code" data-toggle="tab"><span class="fa fa-code"></span> Код для вставки на сайт</a></li>

    <li <?=($tab == 1 ? 'class="active"' : '')?>><a href="#searcher_design" tab="design" data-toggle="tab"><span class="fa fa-th"></span> Внешний вид</a></li>

    <li <?=($tab == 2 ? 'class="active"' : '')?>><a href="#searcher_values" tab="values" data-toggle="tab"><span class="fa fa-sliders"></span> Значения по умолчанию</a></li>

    <li <?=($tab == 3 ? 'class="active"' : '')?>><a href="#searcher_filters" tab="filters" data-toggle="tab"><span class="fa fa-filter"></span> Фильтры</a></li>
</ul>

<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade <?=($tab == 0 ? 'active in' :  '')?>" id="searcher_code">
        <? $this->renderPartial('code'); ?>
    </div>

    <div class="tab-pane fade <?=($tab == 1 ? 'active in' :  '')?>" id="searcher_design">
        <? $this->renderPartial('common_views.searcher.' . $model->type . '_settings', ['model' => $model]); ?>
    </div>

    <div class="tab-pane fade <?=($tab == 2 ? 'active in' :  '')?>" id="searcher_values">
        <? $this->renderPartial('common_views.searcher.values', ['model' => $model]); ?>
    </div>

    <div class="tab-pane fade <?=($tab == 3 ? 'active in' :  '')?>" id="searcher_filters">
        <? $this->renderPartial('common_views.searcher.filters', ['model' => $model]); ?>
    </div>
</div>