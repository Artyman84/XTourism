<?php

$this->addCssFile('migration', 'webroot.css.backend.migration');

$this->breadcrumbs=array(
    '<span class="glyphicon glyphicon-transfer"></span> Скрещивание данных'
);?>


<div id="backend<?=$migrationID?>" class="row" style="padding-bottom: 20px;">

    <div class="col-md-12">

        <div class="row">
            <div class="col-md-4 col-sm-4 form-group">

                <label for="tOperatorsId" >Тур Оператор</label>
                <small class="t-migration-hotel-statistic">
                    <? if( !empty($data['statistic_by_operator']) ) {
                        $this->renderHotelStatistic($data['statistic_by_operator']);
                    } ?>
                </small>

                <div class="input-group">
                    <span class="input-group-btn">
                        <a href="#" title="Обновить данные туроператора" data-toggle="tooltip" class="t-updateOperatorData btn btn-default btn-sm" type="button">
                            <span class="fa fa-refresh text-info"></span>
                        </a>
                    </span>
                    <select id="tOperatorsId" class="form-control input-sm <?php echo ( count($freeElements) ? 't-el-error' : '' )?>"><?
                        foreach( $operators as $operator ){
                            $style = '';
                            if( ArOperators::isFreeOperator($operator->id) ){
                                $style = 'style="color:red;"';
                            }
                            echo '<option value="' . $operator->id . '" ' . $style . ' ' . ($oid ==$operator->id  ? 'selected="selected"' : '') . '>' . $operator->name . '</option>';
                        }?>
                    </select>
                </div>

            </div>


            <div class="col-md-4 col-sm-4 form-group">
                <label for="tCountriesId" >Страна</label>
                <small class="t-migration-hotel-statistic">
                    <? if( !empty($data['statistic_by_country']) ) {
                        $this->renderHotelStatistic($data['statistic_by_country']);
                    } ?>
                </small>

                <?php $this->renderPartial('operators/comboCountries', array('countries' => $data['comboCountries'])); ?>
            </div>


            <div class="col-md-4 col-sm-4 form-group">
                <label for="tResortsId" >Курорт</label>
                <small class="t-migration-hotel-statistic">
                    <? if( !empty($data['statistic_by_resort']) ) {
                        $this->renderHotelStatistic($data['statistic_by_resort']);
                    } ?>
                </small>

                <?php $this->renderPartial('operators/comboResorts', array('resorts' => $data['comboResorts'])); ?>
            </div>
        </div>





    </div>

    <div class="col-md-12" id="tabsWrapperId" style="margin-top: 30px;">


                <ul id="yw45" class="nav nav-pills nav-justified"><?
                    $id = 0;
                    foreach( $tables as $table => $name ){?>
                        <li <?=($id == $tabId ? 'class="active"' : '')?>>
                            <a data-toggle="tab" href="#dictionaryTabsID_tab_<?=$id?>" tab-table="<?=$table?>" class="text-nowrap">
                                <?=$name?>
                                <? if( !empty($data['unread_elements'][$table]) ) {?>
                                    <span class="badge"><?=$data['unread_elements'][$table]?></span>
                                <? } ?>
                            </a>
                        </li><?
                        $id++;
                    }?>
                </ul>

                <div class="tab-content"><?php
                    $id = 0;
                    $oElementsId = [];
                    foreach( $tables as $table => $name ){
                        $dTable = 'directory_' . $table;
                        $oElementsId[$id] = uniqid($table . '_');?>

                        <div id="dictionaryTabsID_tab_<?=$id?>" class="tab-pane fade <?=( $id == $tabId ? 'active in' : '')?>">
                            <div class="t-tabRow row">
                                <div class="t-OperatorRow migration-operator-block col-md-6" id="<?=$oElementsId[$id]?>"><?php
                                    $this->renderOperatorElements($data[$table], $table, null, false);?>
                                </div>
                                <div class="t-DirectoryRow migration-directory-block col-md-6"><?php
                                     $this->renderDirectoryElements($table, isset($data[$dTable]) ? $data[$dTable] : null, $table == 'hotels' ? $data[$table] : [],  false);?>
                                </div>
                            </div>
                        </div><?
                        $id++;
                    }?>
                </div>
        </div>

</div>

<?php

Yii::app()->clientScript->registerScript(
    $migrationID,
    '
    window.initMigrationCore({
        "url": {},
        "extra": {
            "wrapID": "backend' . $migrationID . '",
            "dir_hotel_categories": ' . $dir_hotel_categories . '
        }
    });

    $.initCheckboxGroup(
        ' . CJSON::encode($oElementsId) . ',
        function(input){
            input.closest("tr").addClass("info");
        },
        function(input){
            input.closest("tr").removeClass("info");
        }
    );

    $.bindPopover();

$("body").tooltip({selector: "[data-toggle=tooltip]"});     
    ',
    CClientScript::POS_END
);