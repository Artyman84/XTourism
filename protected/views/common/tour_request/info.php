<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 17.09.2016
 * Time: 1:02
 */

$db = Yii::app()->db;

$info = $db->createCommand()
    ->select('
                ctr.*,
                t.name AS product_type,
                o.name AS operator_name,
                o.url AS operator_url,
                h.name AS hotel_name,
                c.name AS dep_city_name,
                cnt.name AS country_name,
                r.name AS resort_name,
                m.name AS meal_name,
                m.description AS meal_description
            ')
    ->from('{{clients_tours_requests}} AS ctr')
    ->join('{{shop_products_types}} AS t', 't.id = ctr.product_type')
    ->join('{{operators}} AS o', 'o.id = ctr.operator_id')
    ->join('{{directory_dep_cities}} AS c', 'c.id = ctr.dep_city_id')
    ->join('{{directory_meals}} AS m', 'm.id = ctr.meal_id')
    ->join('{{directory_hotels}} AS h', 'h.id = ctr.hotel_id')
    ->join('{{directory_countries}} AS cnt', 'cnt.id = h.dir_country_id')
    ->join('{{directory_resorts}} AS r', 'r.id = h.dir_resort_id')
    ->where('ctr.id = :id', [':id' => $id])
    ->setFetchMode(PDO::FETCH_OBJ)
    ->queryRow();

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Заявка № <span style="text-decoration: underline;"><?=$info->id?></span></h4>
</div>

<div class="modal-body">

    <table class="table table-striped table-unbordered">
        <tbody id="main-tour-info">
            <tr><td style="padding: 0;" colspan="2"></td></tr>
            <tr>
                <td class="text-nowrap text-center" colspan="2" style="padding-bottom: 13px; padding-top: 0;"><strong><?=TSearch\TourHelper::formatDate2($info->created_at) . ', ' . date('H:i', $info->created_at)?></strong></td>
            </tr>
            <tr>
                <th class="text-nowrap"> Турист</th>
                <td><?=CHtml::encode($info->client_name)?></td>
            </tr>
            <tr>
                <th class="text-nowrap"> Телефон</th>
                <td><?=CHtml::encode($info->client_phone)?></td>
            </tr>
            <tr>
                <th class="text-nowrap"> E-mail</th>
                <td><?=CHtml::encode($info->client_email)?></td>
            </tr>
            <tr>
                <th class="text-nowrap" style="padding-bottom: 0;"> Комментарий к заявке</th>
                <td style="padding-bottom: 0;"><?=$info->client_comment ? nl2br(CHtml::encode($info->client_comment)) : '&mdash;'?></td>
            </tr>
            <tr><td style="padding: 0;" colspan="2"></td></tr>
            <tr>
                <td colspan="2" class="text-center">
                    <strong>Инфо</strong>
                </td>
            </tr>
            <tr>
                <th class="text-nowrap"><i class="flaticon-package-cube-box-for-delivery"></i> Продукт</th>
                <td><?=CHtml::encode($info->product_type)?></td>
            </tr>
            <tr>
                <th class="text-nowrap"><i class="fa fa-check fa-correction"></i> ID тура</th>
                <td><?=$info->tour_id?></td>
            </tr>
            <tr>
                <th class="text-nowrap"><i class="flaticon-call-center-worker-with-headset"></i> Туроператор</th>
                <td><a href="<?=$info->operator_url?>" target="_blank"><?=CHtml::encode($info->operator_name)?></a></td>
            </tr>
            <tr>
                <th class="text-nowrap"><i class="fa fa-plane fa-correction"></i> Вылет из</th>
                <td><?=CHtml::encode($info->dep_city_name)?></td>
            </tr>
            <tr>
                <th class="text-nowrap"><i class="fa fa-building fa-correction"></i> Отель</th>
                <td><?=CHtml::encode($info->country_name) . ', ' . CHtml::encode($info->resort_name)?> <a href="<?=Yii::app()->createUrl('Hotel/hotelInfo', ['hId' => TUtil::encode_hotel_id($info->hotel_id)])?>" target="_blank"><?=CHtml::encode($info->hotel_name)?></a></td>
            </tr>
            <tr>
                <th class="text-nowrap"><span class="glyphicon glyphicon-calendar i-margin"></span> Дата начала</th>
                <td><?=Yii::app()->dateFormatter->format("dd.MM.yyyy", $info->start_date)?></td>
            </tr>
            <tr>
                <th class="text-nowrap"><span class="glyphicon glyphicon-time i-margin"></span> Ночей</th>
                <td><?=$info->nights?></td>
            </tr>
            <tr>
                <th class="text-nowrap"><span class="glyphicon glyphicon-cutlery i-margin"></span> Питание</th>
                <td><strong><?=CHtml::encode($info->meal_name)?></strong>&nbsp;&nbsp;<?=CHtml::encode($info->meal_description)?></td>
            </tr>
            <tr>
                <th class="text-nowrap"><i class="glyphicon glyphicon-bed i-margin"></i> Размещение</th>
                <td><?=TSearch\TourHelper::getResidence($info->adults, $info->kids, true) . ' ' . $info->room;?></td>
            </tr>
            <tr>
                <th class="text-nowrap"><i class="fa fa-money i-margin fa-correction"></i> Цена</th>
                <td><?=TSearch\TourHelper::normalizePrice($info->price) . ' ' . TSearch\TourHelper::htmlCurrency($info->currency)?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-primary" onclick="window.print();"><span class="glyphicon glyphicon-print"></span> Печать</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
</div>
