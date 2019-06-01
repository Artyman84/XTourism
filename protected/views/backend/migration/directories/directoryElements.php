
<div>
    <div style="float: left;">
        <p class="muted"><b>Справочник</b></p>
    </div>
    <div style="float: right;" class="has-feedback">
        <input class="t-directoryFilter form-control input-sm" type="text" style="width: 230px;">
        <span class="glyphicon glyphicon-search form-control-feedback"></span>
    </div>
</div>
<table class="table table-gray-head">
    <thead>
    <tr>
        <th style="width: 13%; text-align: center;">#</th>
        <th>ID</th>
        <th style="width: 65%;"><?php echo ArDirectorySearch::getTableName($table);?></th>
        <th></th>
    </tr>
    </thead>
</table>

<div class="t-scroll">
    <table class="table t-oTable table-condensed">
        <thead class="t-noPadding">
        <tr>
            <th style="width: 13%; text-align: center;"></th>
            <th></th>
            <th style="width: 65%;"></th>
            <th></th>
        </tr>
        </thead>
        <tbody table="<?php echo $table;?>"><?php
            $i = 1;
            foreach($directoryElements as $directoryElement){

                $trClasses = '';
                $trContent = '';
                $trMeta = '';

                switch( $table ){
                    case 'resorts':
                        $trContent .= CHtml::encode($directoryElement->name);

                        if( $directoryElement->is_combined ){
                            $children = '';
                            $arrChildren = Yii::app()->db->createCommand()->select('name')->from('{{directory_resorts}}')->where('parent_id = :id', [':id' => $directoryElement->id])->order('name')->queryColumn();

                            foreach( $arrChildren as $child ) {
                                $children .= CHtml::encode($child) . '<br>';
                            }

                            $trContent .= '&nbsp;&nbsp;<a href="#" data-toggle="popover" class="text-warning" title="" data-placement="top" data-content="' . $children . '" role="button" data-original-title="Объединенные курорты"><strong><i class="fa fa-object-group"></i></strong></a>';
                        }

                        $trClasses = isset($related_ids[$directoryElement->id]) ? 'success' : '';
                        break;

                    case 'hotels':

                        $trContent .= CHtml::link('<span class="fa fa-external-link"></span>', $directoryElement->url, ['target' => '_blank'])  . '&nbsp;&nbsp;&nbsp;';
                        $trContent .= CHtml::link(CHtml::encode($directoryElement->name), Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/index.php/Hotel/hotelInfo/?hId=' . TUtil::encode_hotel_id($directoryElement->id), ['target' => '_blank']);
                        $trContent .= '&nbsp;<span class="t-cat">' . CHtml::encode(TSearch\TourHelper::normalizeHotelCategory($directoryElement->category_name)) . '</span>';
                        $trContent .= '&nbsp;&nbsp;<small>(' . CHtml::encode($directoryElement->resort_name) . ')</small>';
                        $trClasses = isset($related_ids[$directoryElement->id]) ? 'success' : '';
                        $trMeta = 'data-category-id="' . $directoryElement->category_id . '"';
                        break;

                    default:
                        $trContent = CHtml::encode($directoryElement->name);
                }


                if( $directoryElement->disabled ){
                    $trClasses .= ' t-el-disabled';
                }?>

                <tr directoryid="<?php echo $directoryElement->id; ?>" <?php echo $trClasses ? 'class="' . $trClasses . '"' : '';?> <?=$trMeta?>>
                    <td class="directory-handle">
                        <span class="t-number"><?php echo ($i++) ?></span>
                    </td>
                    <td><?php echo $directoryElement->id; ?></td>
                    <td class="t-dirName"><?php echo $trContent; ?></td>
                    <td class="text-right" style="vertical-align: middle;">
                        <? if( $table == 'hotels' ) {?>
                            <a title="Редактировать" data-toggle="tooltip" data-placement="top" class="t-edit" rel="tooltip" href="#">
                                <span class="glyphicon glyphicon-edit"></span></a>
                        <? } ?>
                        <a title="<?=$directoryElement->disabled ? 'Разблокировать' : 'Заблокировать'?>" data-toggle="tooltip" data-placement="top" class="t-block" rel="tooltip" href="#">
                            <span class="glyphicon glyphicon-<?=$directoryElement->disabled ? 'ok-circle text-success' : 'ban-circle text-danger'?>"></span>
                        </a>
                    </td>
                </tr><?
            }?>
            <tr class="no-hover">
                <td colspan="4" style="padding: 0px;">
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?
