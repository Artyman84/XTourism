<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 06.08.2017
 * Time: 18:41
 */

//echo $error['message'];

?>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th colspan="2"><h3>Ошибка:<?=$error['code']?></h3></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="text-nowrap">
            <code>Тип:</code>
        </td>
        <td><?=$error['type']?></td>
    </tr>
    <tr>
        <td class="text-nowrap">
            <code>Текст:</code>
        </td>
        <td><?=$error['message']?></td>
    </tr>
    <tr>
        <td class="text-nowrap">
            <code>Файл:</code>
        </td>
        <td><?=$error['file'] . ': ' . $error['line']?></td>
    </tr>
    <tr>
        <td class="text-nowrap">
            <code>Строка:</code>
        </td>
        <td><?=$error['line']?></td>
    </tr>
    <tr>
        <td class="text-nowrap">
            <code>Стэк вызовов:</code>
        </td>
        <td><? echo '<pre>' . $error['trace'] . '</pre>'?></td>
    </tr>
    <tr>
        <td class="text-nowrap">
            <code>Код:</code>
        </td>
        <td><?=$error['source']?></td>
    </tr>
    </tbody>
</table>