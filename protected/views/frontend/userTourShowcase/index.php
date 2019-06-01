<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 09.04.2015
 * Time: 9:55
 * @var ArUserTourShowcase $userShowcase
 */

$this->breadcrumbs=[
    '<span class="glyphicon glyphicon-th"></span> Витрина туров'
];?>

<? $this->showFlashMessage('showcase_settings');?>
<? $this->showFlashMessage('showcase_values');?>
<? $this->showFlashMessage('tour_showcase_filters');?>


<ul id="myTab" class="nav nav-pills" style="margin-bottom: 15px;">
    <li <?=($tab == 0 ? 'class="active"' : '')?>><a href="#showcase_code" tab="code" data-toggle="tab"><span class="fa fa-code"></span> Код для вставки на сайт</a></li>

    <li <?=($tab == 1 ? 'class="active"' : '')?>><a href="#showcase_settings" tab="settings" data-toggle="tab"><span class="fa fa-sliders"></span> Настройки</a></li>

    <li <?=($tab == 2 ? 'class="active"' : '')?>><a href="#showcase_params" tab="settings" data-toggle="tab"><span class="fa fa-th"></span> Внешний вид</a></li>

    <li <?=($tab == 3 ? 'class="active"' : '')?>><a href="#showcase_filters" tab="filters" data-toggle="tab"><span class="fa fa-filter"></span> Фильтры</a></li>
</ul>

<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade <?=($tab == 0 ? 'active in' : '')?>" id="showcase_code">
        <? $this->renderPartial('code'); ?>
    </div>

    <div class="tab-pane fade <?=($tab == 1 ? 'active in' :  '')?>" id="showcase_settings">
        <? $this->renderPartial('common_views.tour_showcase.values', ['model' => $model]); ?>
    </div>

    <div class="tab-pane fade <?=($tab == 2 ? 'active in' :  '')?>" id="showcase_params">
        <? $this->renderPartial('common_views.tour_showcase.' . $model->type . '_settings', ['model' => $model, 'settings' => $settings, 'defaultSettings' => $defaultSettings]); ?>
    </div>

    <div class="tab-pane fade <?=($tab == 3 ? 'active in' :  '')?>" id="showcase_filters">
        <? $this->renderPartial('common_views.tour_showcase.filters', ['model' => $model]); ?>
    </div>
</div>