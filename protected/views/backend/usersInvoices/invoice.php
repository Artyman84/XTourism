<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 22.04.2016
 * Time: 12:42
 */

$userInvoice = ArShopInvoices::model()->findByPk($invoice_id);
$invoice = CJSON::decode($userInvoice->invoice);
$agent = ArUsers::model()->findByPk($userInvoice->user_id);

$this->addCssFile('print', 'webroot.css', 'print');

$this->breadcrumbs=array(
    '<span class="flaticon-verification-of-delivery-list-clipboard-symbol"></span> Счета турагентов' => Yii::app()->createUrl('UsersInvoices/index'),
    '<span class="flaticon-verification-of-delivery-list-clipboard-symbol"></span> История счетов - ' . ($agent ? $agent->userName() : '(Агент удален)') =>  Yii::app()->createUrl('UsersInvoices/history', ['id' => $userInvoice->user_id]),
    '<span class="flaticon-verification-of-delivery-list-clipboard-symbol"></span> Квитанция'
);?>

<br>
<div class="row">

    <div class="col-sm-12 text-center" style="font-size: 16px;"><strong>Квитанция</strong></div>
    <br>
    <br>

    <div class="col-sm-6 col-sm-offset-3">
        <table class="table table-hovered panel-table table-bordered">
            <tbody>
                <tr>
                    <th style="width: 33%;">Турагент:</th>
                    <td><?=$agent ? CHtml::encode($agent->userName()) : 'Агент удален'?></td>
                </tr>
                <tr>
                    <th>Название пакета:</th>
                    <td><?=CHtml::encode($invoice['package_name'])?></td>
                </tr>
                <tr>
                    <th>Дата начала:</th>
                    <td><?=$invoice['start'] != '' ? Yii::app()->dateFormatter->format('dd.MM.yyyy', $invoice['start']) : ''?></td>
                </tr>
                <tr>
                    <th>Действителен до:</th>
                    <td><?=$invoice['expired'] != '' ? Yii::app()->dateFormatter->format('dd.MM.yyyy', $invoice['expired']) : ''?></td>
                </tr>
                <tr>
                    <th>Комментарий к квитанции:</th>
                    <td><?=nl2br(CHtml::encode($invoice['comment']))?></td>
                </tr>
                <tr>
                    <th>Цена в гривнах:</th>
                    <td><?=$invoice['price_uah']?>&nbsp;&#8372;</td>
                </tr>
                <tr>
                    <th>Цена в рублях:</th>
                    <td><?=$invoice['price_rub']?>&nbsp;₽</td>
                </tr>
                <tr>
                    <th>Продукты:</th>
                    <td><?
                        $products = '<ul style="padding-left: 15px;">';
                        foreach( $invoice['products'] as $product ) {
                            $products .= '<li>' . CHtml::encode($product) . '</li>';
                        }
                        $products .= '</ul>';
                        echo $products;
                    ?></td>
                </tr>
                <tr>
                    <th>Время оплаты:</th>
                    <td><?=Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm:ss', $userInvoice->created_at)?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        <div class="form-actions">
            <button type="button" class="btn btn-default" onclick="window.history.go(-1);"><span class="glyphicon glyphicon-arrow-left"></span> Обратно</button>
            <button type="button" class="btn btn-primary" onclick="window.print();"><span class="glyphicon glyphicon-print"></span> Печать</button>
        </div>
    </div>
</div>

