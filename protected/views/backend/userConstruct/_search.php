<?php
/* @var $this UserConstructController */
/* @var $model ArUserConstructDomains */
/* @var $form CActiveForm */

?>


<hr/>

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'htmlOptions' => array(
        'role' => 'form',
        'method' => 'post',
    ),
    'method'=>'get',
));?>

<div class="row">
    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'user_id'); ?>
            <?php echo CHtml::activeDropDownList($model, 'user_id', ArUsers::simpleUsersList(ArUsers::model()->active()->agent()->findAll()), ['class' => 'form-control', 'empty' => '']); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'domain_name'); ?>
            <?php echo $form->textField($model, 'domain_name', ['class' => 'form-control']); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'is_purchased'); ?>
            <?php echo $form->dropDownList($model, 'is_purchased', [0 => 'Не оплачен', 1 => 'Оплачен'], ['class' => 'form-control', 'empty' => '']); ?>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group font-bold input-group-sm">
            <?php echo $form->label($model, 'is_active'); ?>
            <?php echo $form->dropDownList($model, 'is_active', [0 => 'Не активен', 1 => 'Активен'], ['class' => 'form-control', 'empty' => '']); ?>
        </div>
    </div>

</div>



<hr/>
<button typeof="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Найти</button>
<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        (function($) {

            $('.search-button').click(function () {

                if ($('.search-form').is(':hidden')) {
                    $(this).find('span:first').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
                } else {
                    $(this).find('span:first').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-right');
                }

                $('.search-form').toggle();
                return false;
            });

            $('.search-form form').submit(function () {
                $('#users_searcher_grid_view').yiiGridView('update', {
                    data: $(this).serialize()
                });
                return false;
            });

        })($);
    });
    /*]]>*/
</script>


<?php $this->endWidget(); ?>

<!-- search-form -->