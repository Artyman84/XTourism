<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 10.03.2016
 * Time: 14:03
 * @var \TSearch\Searcher $searcher
 * @var SearcherStandardSettings $settings
 */

$f_tab = (int)Yii::app()->request->getParam('f_tab', 0);

$tabs = [
    'operators' => array_values(\TSearch\TOperator::operatorsInfo()),
    'countries' => \TSearch\tbl\Directory::loadData('countries', ['disabled' => 0], false),
    'dep_cities' => \TSearch\tbl\Directory::loadData('dep_cities', ['disabled' => 0], false)
];

$texts = [
    'operators' => 'Выберите те операторы, которые Вы хотите видеть в своем поисковике туров. Если ни один оператор не выбран, поиск будет осуществляться по всем операторам.',
    'countries' => 'Выберите те страны, которые Вы хотите видеть в своем поисковике туров. Если ни одна страна не выбрана, поиск будет осуществляться по всем странам.',
    'dep_cities' => 'Выберите те города вылета, которые Вы хотите видеть в своем поисковике туров. Если ни один город не выбран, поиск будет осуществляться по всем городам.'
];

$settings = $model->searcherSettings();?>


<div class="panel panel-default" id="searcher-filters-settings">

    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12" >

            <ul class="nav nav-tabs">
                <li <?=($f_tab == 0 ? 'class="active"' : '')?>><a href="#filter_operators" tab="operators" data-toggle="tab">Операторы</a></li>
                <li <?=($f_tab == 1 ? 'class="active"' : '')?>><a href="#filter_countries" tab="countries" data-toggle="tab">Страны</a></li>
                <li <?=($f_tab == 2 ? 'class="active"' : '')?>><a href="#filter_dep_cities" tab="dep_cities" data-toggle="tab">Города вылетов</a></li>
            </ul>

            <div class="tab-content"><?

                $t = 0;
                foreach ($tabs as $filter => $data) {?>

                    <div class="tab-pane fade <?=($f_tab == $t ? 'active in' : '')?>" id="filter_<?=$filter?>">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center" style="margin-bottom: 20px;">
                            <p class="help-block"><?=$texts[$filter]?></p>
                        </div><?

                    $params = ['filter' => $filter, 'f_tab' => $t++];
                    isset($user_id) ? $params['user_id'] = $user_id : false;

                    $this->beginWidget('CActiveForm', [
                        'action' => $this->createUrl('UserSearcher/saveFilter', $params),
                        'htmlOptions' => [
                            'role' => 'form',
                            'method' => 'post',
                        ]
                    ]);

                    $total = count($data);
                    $whole = floor($total/4);
                    $rest = $total%4;

                    $c = 0;
                    for($part=1; $part<=4; ++$part) {

                        if( $rest ){
                            $count = $whole;
                            --$rest;
                        } else {
                            $count = $whole - 1;
                        }

                        ?><div class="col-md-3 col-sm-3 col-xs-3"><table class="table table-unbordered table-hovered panel-table table-striped"><tbody><?
                            for($j=0; $j<=$count; ++$j) {
                                $checked = in_array($data[$c]->id, $settings[$filter])?>
                                <tr <?=$checked ? 'class="info"' : ''?>>
                                    <td><?=CHtml::encode($data[$c]->name)?></td>
                                    <td>
                                        <div class="xtourism-checkbox">
                                            <input type="checkbox" name="<?=$filter?>[]" value="<?=$data[$c]->id?>" <?=$checked ? 'checked="checked"' : ''?>>
                                            <span class="glyphicon glyphicon-<?=$checked ? 'check text-info' : 'unchecked'?>"></span>
                                        </div>
                                    </td>
                                </tr>
                                <?
                                ++$c;
                            }
                        ?></tbody></table></div><?
                    }?>


                        <div class="col-md-12">
                            <hr>
                            <button class="btn btn-primary btn-sm" type="submit" onclick="$.showFade();"><span class="glyphicon glyphicon-save"></span> Сохранить фильтр</button>
                            <button class="btn btn-default btn-sm t-filter-check" type="button"><span class="glyphicon glyphicon-check"></span> Выбрать все</button>
                            <button class="btn btn-default btn-sm t-filter-uncheck" type="button"><span class="glyphicon glyphicon-unchecked"></span> Снять выделение</button>
                        </div><?


                        $this->endWidget();?>
                    </div>
                <? } ?>
            </div>
        </div>

    </div>


    <script type="text/javascript">
        /*<![CDATA[*/
        (function($){

            $(function(){

                $("#searcher-filters-settings tbody tr").click(function(e){

                    if( e.target.tagName.toUpperCase() == "TD" || e.target.tagName.toUpperCase() == "TR" || e.target.tagName.toUpperCase() == "DIV" ){
                        $(this).find("span").trigger("click");
                    }

                });

                $(document.body).on("change", "#searcher-filters-settings tbody tr :checkbox", function(){
                    if( $(this).is(":checked") ){
                        $(this).closest("tr").addClass("info");
                    } else {
                        $(this).closest("tr").removeClass("info");
                    }
                });

                $(document.body).on("click", "#searcher-filters-settings .t-filter-check", function(){
                    $(this).closest("form").find("table td span.glyphicon").each(function(i){
                        if( $(this).hasClass("glyphicon-unchecked") ){
                            $(this).closest("tr").addClass("info");
                            $(this).removeClass("glyphicon-unchecked").addClass("glyphicon-check text-info");
                            $(this).parent().find("input").prop("checked", "checked");
                        }
                    });
                });

                $(document.body).on("click", "#searcher-filters-settings .t-filter-uncheck", function(){
                    $(this).closest("form").find("table td span.glyphicon").each(function(i){
                        if( $(this).hasClass("glyphicon-check") ){
                            $(this).closest("tr").removeClass("info");
                            $(this).removeClass("glyphicon-check text-info").addClass("glyphicon-unchecked");
                            $(this).parent().find("input").prop("checked", false);
                        }
                    });
                });

            })
        })(jQuery);
        /*]]>*/
    </script>

</div>