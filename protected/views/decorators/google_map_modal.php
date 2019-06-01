<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 24.08.2016
 * Time: 20:12
 */
?>
<div class="modal fade" id="modalGoogleMap" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo CHtml::encode($address)?></h4>
            </div>

            <div class="modal-body">
                <iframe src="<?=$content?>&output=embed" frameborder="0" width="100%" height="500px;" scrolling="no"></iframe>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>

        </div>
    </div>
</div>