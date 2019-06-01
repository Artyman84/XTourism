<?php
$related = isset($related) ? (int)$related : null;
$operatorElements = (array)$operatorElements;

$i = 0;
$related_count = 0;

if( ($table == 'countries' || $table == 'resorts') && isset($operatorElements[0]) ){

    $elements_ids = [];
    foreach($operatorElements as $element){
        $elements_ids[] = $element->element_id;
    }

    $db = Yii::app()->db;
    if( $table == 'countries' ) {

        $_resorts = $db->createCommand()
            ->select('country, COUNT(element_id) AS resorts')
            ->from('{{operator_resorts}}')
            ->where(['AND', 'operator_id = :oid', 'unread = 1', ['IN', 'country', $elements_ids]], [':oid' => $operatorElements[0]->operator_id])
            ->group('country')
            ->having('resorts > 0')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $resorts = [];
        foreach ($_resorts as $_r) {
            $resorts[$_r->country] = $_r->resorts;
        }

        $_hotels = $db->createCommand()
            ->select('r.country, COUNT(h.element_id) AS hotels')
            ->from('{{operator_resorts}} AS r')
            ->join('{{operator_hotels}} AS h', 'h.resort = r.element_id AND h.operator_id = r.operator_id')
            ->where(['AND', 'r.operator_id = :oid', 'h.unread = 1', ['IN', 'r.country', $elements_ids]], [':oid' => $operatorElements[0]->operator_id])
            ->group('r.country')
            ->having('hotels > 0')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $hotels = [];
        foreach ($_hotels as $_h) {
            $hotels[$_h->country] = $_h->hotels;
        }

    } else {

        $_hotels = $db->createCommand()
            ->select('resort, COUNT(element_id) AS hotels')
            ->from('{{operator_hotels}}')
            ->where(['AND', 'operator_id = :oid', 'unread = 1', ['IN', 'resort', $elements_ids]], [':oid' => $operatorElements[0]->operator_id])
            ->group('resort')
            ->having('hotels > 0')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $hotels = [];
        foreach ($_hotels as $_h) {
            $hotels[$_h->resort] = $_h->hotels;
        }
    }

}?>

<div style="float: left;">
    <p class="muted">
        <b>Тур Оператор</b>
        <a type="button" class="btn btn-xs t-synchronizeElements <?=($related_count == count($operatorElements) ? 'disabled' : '')?>" data-toggle="tooltip" data-placement="top" title="Скрестить выбранные элементы туроператра с идентичными элементами справочника">
            Скрестить по сравнению
            &nbsp;<span class="fa fa-link"></span>
        </a>
    </p>
</div>
<div id="relatedStatusId" style="float: right;" class="btn-group">
    <button type="button" class="btn btn-xs <?php echo ($related === null ? 'active' : '')?> t-allElements">Все</button>
    <button type="button" class="btn btn-success btn-xs <?php echo ($related === 1 ? 'active' : '')?> t-relatedElements">Связанные</button>
    <button type="button" class="btn btn-danger btn-xs <?php echo ($related === 0 ? 'active' : '')?> t-freeElements">Свободные</button>
</div>


<?
$nameWidth = 80;
if( $table == 'countries' ) {
    $nameWidth = '55';
} elseif( $table == 'resorts' ) {
    $nameWidth = '65';
}
?>



<table class="table table-gray-head" >
    <thead table="operator_<?php echo $table;?>">
    <tr>
        <th style="width: 13%; text-align: center;"><a href="#" class="t-mark-elements_read" data-toggle="tooltip" data-placement="top" data-original-title="Просмотреть все элементы"><span class="glyphicon glyphicon-eye-open"></span></a></th>
        <th style="width: <?=$nameWidth?>%;"><?php echo $name;?></th>
        <? if( $table == 'countries' ) {?>
            <th style="width: 11%;">R+</th>
            <th style="width: 11%;">H+</th>
        <? } elseif( $table == 'resorts' ) {?>
            <th style="width: 11%;">H+</th>
        <? } ?>
        <th ></th>
    </tr>
    </thead>
</table>


<div class="t-scroll">
    <table class="table t-oTable table-condensed">
        <thead class="t-noPadding">
        <tr>
            <th style="width: 13%; text-align: center;"></th>
            <th style="width: <?=$nameWidth?>%;"></th>
            <? if( $table == 'countries' ) {?>
                <th style="width: 11%;"></th>
                <th style="width: 11%;"></th>
            <? } elseif( $table == 'resorts' ) {?>
                <th style="width: 11%;"></th>
            <? } ?>
            <th></th>
        </tr>
        </thead>
        <tbody table="operator_<?php echo $table;?>"><?php

            foreach($operatorElements as $element){

                $trClass = '';
                $title = '';

                $i++;

                if( $element->directory_id ){
                    $trClass = 'success';
                    ++$related_count;
                }

                if( isset($element->f_deleted) && $element->f_deleted ){
                    $trClass .= ' t-blocked';
                    $title = 'title="удален"';
                }

                if( $element->unread ){
                    $trClass .= ' info';
                }?>

                <tr <?php echo (!empty($trClass) ? 'class="' . $trClass . '"' : '');?> <?php echo isset($title) ? $title : '';?> directoryid="<?php echo $element->directory_id; ?>" elementid="<?php echo $element->element_id;?>">
                    <td class="directory-handle">
                        <span class="t-number"><?php echo isset($i) ? $i : 0; ?></span>
                    </td>
                    <td><?php

                        if( $table == 'hotels' ){
                            echo CHtml::link(CHtml::encode($element->name), Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/index.php/Hotel/operatorHotelInfo/' . $element->element_id . '?oid=' . $element->operator_id, ['target' => '_blank']) . '&nbsp;' . CHtml::encode($element->category_name);
                        } else {
                            echo CHtml::encode($element->name);
                        }

                        ?></td>
                    <? if( $table == 'countries' ) {?>
                        <td class="text-center"><?=isset($resorts[$element->element_id]) ? '<span class="badge">' . $resorts[$element->element_id] . '</span>' : ''?></td>
                        <td class="text-center"><?=isset($hotels[$element->element_id]) ? '<span class="badge">' . $hotels[$element->element_id] . '</span>' : ''?></td>
                    <? } elseif( $table == 'resorts' ) {?>
                        <td><?=isset($hotels[$element->element_id]) ? '<span class="badge">' . $hotels[$element->element_id] . '</span>' : ''?></td>
                    <? } ?>
                    <td class="button-column text-right"><?
                        if( $element->directory_id ){?>
                            <a title="Разъединить" data-toggle="tooltip" data-placement="top" rel="tooltip" class="t-unbindElement" href="#"><span class="fa fa-unlink text-danger"></span></a><?
                        } else {?>
                            <a title="Связать" data-toggle="tooltip" data-placement="top" rel="tooltip" class="t-bindElement" href="#"><span class="fa fa-link text-success"></span></a><?
                        }?>
                    </td>
                </tr><?

            }

        ?>
        </tbody>
    </table>
</div>