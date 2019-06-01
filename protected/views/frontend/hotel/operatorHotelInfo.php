<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 03.04.2016
 * Time: 13:30
 */

?>
<div class="row">
    <div class="col-xs-12">
        <h2 class="text-center"><?=$hotel_info['name'] . ' ' . $hotel_info['category_name']?></h2>
        <br>
        <br>
        <table class="table ">
            <tbody>
                <tr>
                    <th>Фото</th>
                    <td>
                        <div class="row">
                            <? foreach( $hotel_info['images'] as $image ) {?>
                                <div class="col-md-4">
                                    <a href="#" class="thumbnail" onclick="return false;">
                                        <img src="<?=str_replace(':88', '', $image)?>" style="height: 150px;">
                                    </a>
                                </div>
                            <? } ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Описание</th>
                    <td><?=$hotel_info['description']?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?