<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 13.04.2015
 * Time: 13:43
 *
 * @var CActiveForm $form
 * @var ArTourShowcaseTours $model
 */

$this->breadcrumbs=array(
    '<span class="glyphicon glyphicon-th"></span> Витрины туров' => Yii::app()->createUrl('UserTourShowcase/index'),
    '<span class="fa fa-list-alt"></span> Туры' => Yii::app()->createUrl('UserTourShowcase/tours', ['id' => $model->user_showcase_id]),
    ($model->isNewRecord ? '<span class="fa fa-file-o"></span> Создание тура' : '<span class="fa fa-edit"></span> Редактирование тура') . ' - ' .  $model->showcase->user->userName()
);?>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'tour-form',
    'htmlOptions' => array(
        'role' => 'form',
        'method' => 'post'
    ),
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'afterValidate' => 'js:function(form, data, hasError){
            if( !hasError ) {
                $.showFade();
                return true;
            }
        }',
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success'
    ),
));?>

    <fieldset>
        <legend class="text-info">Форма редактирования</legend>

        <div class="row">

            <div class="col-sm-4">
                <div class="form-group">
                    <? $hotel = $model->getIsNewRecord() ? null : ArDirHotels::model()->findByPk($model->hotel_id); ?>
                    <span class="glyphicon glyphicon-globe i-margin text-muted"></span>
                    <?php echo $form->labelEx($model, 'country_name', ['class' => 'text-muted']); ?>
                    <?php echo CHtml::dropDownList(get_class($model) . '[country_id]', $hotel ? $hotel->dir_country_id : null, CHtml::listData(ArDirCountries::model()->findAll(['order' => 'name']), 'id', 'name'), ['class' => 'form-control', 'empty' => '--Выберите страну--']); ?>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-globe i-margin text-muted"></span>
                    <?php echo $form->labelEx($model, 'resort_name', ['class' => 'text-muted']); ?>
                    <?php echo CHtml::dropDownList(get_class($model) . '[resort_id]', $hotel ? $hotel->dir_resort_id : null, $hotel ? CHtml::listData(ArDirResorts::model()->findAllByAttributes(['dir_country_id' => $hotel->dir_country_id]), 'id', 'name') : [], ['class' => 'form-control', 'empty' => '--Выберите курорт--']); ?>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <span class="fa fa-building i-margin text-muted"></span>
                    <?php echo $form->labelEx($model, 'hotel_id', ['class' => 'text-muted']); ?>
                    <?php echo $form->dropDownList($model, 'hotel_id', $hotel ? CHtml::listData(ArDirHotels::model()->findAllByAttributes(['dir_resort_id' => $hotel->dir_resort_id]), 'id', 'name') : [], ['class' => 'form-control', 'empty' => '--Выберите отель--']); ?>
                    <?php echo $form->error($model, 'hotel_id', ['class' => 'text-danger', 'errorCssClass' => 'has-error']);?>
                </div>
            </div>

        </div>

        <br/>

        <div class="row">

            <div class="col-sm-4">
                <div class="form-group">
                    <span class="flaticon-call-center-worker-with-headset text-muted"></span>
                    <?php echo $form->labelEx($model, 'operator_id', ['class' => 'text-muted']); ?>
                    <?php echo $form->dropDownList($model, 'operator_id', CHtml::listData(ArOperators::model()->findAll(['order' => 'name']), 'id', 'name'), ['class' => 'form-control', 'empty' => '--Выберите туроператор--']); ?>
                    <?php echo $form->error($model, 'operator_id', ['class' => 'text-danger', 'errorCssClass' => 'has-error']);?>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <span class="fa fa-plane i-margin text-muted"></span>
                    <?php echo $form->labelEx($model, 'city_id', ['class' => 'text-muted']); ?>
                    <?php echo $form->dropDownList($model, 'city_id', CHtml::listData(ArDirDepCities::model()->findAll(['order' => 'name']), 'id', 'name'), ['class' => 'form-control', 'empty' => '--Выберите город вылета--']); ?>
                    <?php echo $form->error($model, 'city_id', ['class' => 'text-danger', 'errorCssClass' => 'has-error']);?>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <span class="fa fa-cutlery i-margin text-muted"></span>
                    <?php echo $form->labelEx($model, 'meal_id', ['class' => 'text-muted']); ?>
                    <?php echo $form->dropDownList($model, 'meal_id', CHtml::listData(ArDirMeals::model()->findAll(['order' => 'name']), 'id', 'name'), ['class' => 'form-control', 'empty' => '--Выберите тип питания--']); ?>
                    <?php echo $form->error($model, 'meal_id', ['class' => 'text-danger', 'errorCssClass' => 'has-error']);?>
                </div>
            </div>

        </div>

        <br/>
        <br/>
        <legend class="text-info"></legend>
        <br/>

        <div class="row">

            <div class="col-sm-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-time i-margin text-muted"></span>
                    <?php echo $form->labelEx($model, 'nights', ['class' => 'text-muted']); ?>
                    <?php $nights = []; for($i=1; $i<=30; ++$i) $nights[$i] = $i;?>
                    <?php echo $form->dropDownList($model, 'nights', $nights, ['class' => 'form-control', 'empty' => '--Выберите количество ночей--']); ?>
                    <?php echo $form->error($model, 'nights', ['class' => 'text-danger', 'errorCssClass' => 'has-error']);?>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <span class="fa fa-user i-margin text-muted"></span>
                    <?php echo $form->labelEx($model, 'adults', ['class' => 'text-muted']); ?>
                    <?php $adults = []; for($i=1; $i<=4; ++$i) $adults[$i] = $i;?>
                    <? $model->adults = $model->getIsNewRecord() ? 2 : $model->adults; ?>
                    <?php echo $form->dropDownList($model, 'adults', $adults, ['class' => 'form-control', 'empty' => '--Выберите количество взрослых--']); ?>
                    <?php echo $form->error($model, 'adults', ['class' => 'text-danger', 'errorCssClass' => 'has-error']);?>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <span class="fa fa-users i-margin text-muted"></span>
                    <?php echo $form->labelEx($model, 'kids', ['class' => 'text-muted']); ?>
                    <?php $kids = []; for($i=0; $i<=3; ++$i) $kids[$i] = $i;?>
                    <?php echo $form->dropDownList($model, 'kids', $kids, ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'kids', ['class' => 'text-danger', 'errorCssClass' => 'has-error']);?>
                </div>
            </div>

        </div>

        <br>

        <div class="row">

            <div class="col-sm-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-calendar i-margin text-muted"></span>
                    <?php echo $form->labelEx($model, 'start_date', ['class' => 'text-muted']); ?>
                    <?php $this->widget('zii.widgets.jui.CJuiDatePicker', [
                                            'name' => CHtml::activeName($model, 'start_date'),
                                            'value' => Yii::app()->dateFormatter->format('dd.MM.yyyy', $model->getAttribute('start_date')),
                                            'model' => $model,

                    ]);?>
                    <?php echo $form->error($model, 'start_date', array('class' => 'text-danger', 'errorCssClass' => 'has-error'));?>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <span class="fa fa-money i-margin text-muted"></span>
                    <?php echo $form->labelEx($model, 'price', ['class' => 'text-muted']); ?>
                    <?php echo $form->textField($model, 'price', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'price', ['class' => 'text-danger', 'errorCssClass' => 'has-error']);?>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <span class="fa fa-rouble i-margin text-muted"></span>
                    <span class="fa fa-euro i-margin text-muted"></span>
                    <span class="fa fa-dollar i-margin text-muted"></span>
                    <?php echo $form->labelEx($model, 'currency', ['class' => 'text-muted']); ?>
                    <?php echo $form->dropDownList($model, 'currency', $model::currencies(), ['class' => 'form-control', 'empty' => '--Выберите валюту--']); ?>
                    <?php echo $form->error($model, 'currency', ['class' => 'text-danger', 'errorCssClass' => 'has-error']);?>
                </div>
            </div>

        </div>

        <br>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <span class="glyphicon glyphicon-bed i-margin text-muted"></span>
                    <?php echo $form->labelEx($model, 'room', ['class' => 'text-muted']); ?>
                    <?php echo $form->textField($model, 'room', ['class' => 'form-control', 'placeholder' => 'Пример: Deluxe']); ?>
                    <?php echo $form->error($model, 'room', ['class' => 'text-danger', 'errorCssClass' => 'has-error']);?>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="form-group">

                <div class="col-md-12">
                    <div class="checkbox">
                        <div class="xtourism-checkbox">
                            <?php echo $form->checkBox($model, 'published', ['checked' => $model->getAttribute('published') || $model->isNewRecord]); ?>
                            <span class="glyphicon glyphicon-<?=($model->getAttribute('published') || $model->isNewRecord ? 'check text-info' : 'unchecked')?>"></span>
                            <strong class="text-info" style="cursor: pointer;" onclick="$(this).prev().trigger('click');"><?=$model->getAttributeLabel('published')?></strong>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <div class="form-actions">
                    <?php echo CHtml::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
                    <button type="button" class="btn btn-default" onclick="$.showFade(); window.location.href='<?php echo Yii::app()->createUrl('UserTourShowcase/tours', ['id' => $model->user_showcase_id]);?>'; return false;">Отмена</button>
                </div>
            </div>
        </div>


    </fieldset>

<?php $this->endWidget();?>

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        $("#<?=get_class($model)?>_country_id").change(function(){
            $.sendRequest("UserTourShowcase/resortsByCountry", {id: $(this).val()}, function(resorts){
                var options = ['<option value="">--Выберите курорт--</option>'];
                for(var i=0, l=resorts.length; i<l; ++i){
                    options[i+1] = '<option value="' + resorts[i].id + '">' + $.escapeHtml(resorts[i].name) + '</option>';
                }

                $("#<?=get_class($model)?>_resort_id").get(0).innerHTML = options.join("");
            })
        });

        $("#<?=get_class($model)?>_resort_id").change(function(){
            $.sendRequest("UserTourShowcase/hotelsByResort", {id: $(this).val()}, function(hotels){
                var options = ['<option value="">--Выберите отель--</option>'];
                for(var i=0, l=hotels.length; i<l; ++i){
                    options[i+1] = '<option value="' + hotels[i].id + '">' + $.escapeHtml(hotels[i].name) + '</option>';
                }

                $("#<?=get_class($model)?>_hotel_id").get(0).innerHTML = options.join("");
            })
        });
    });
    /*]]>*/
</script>
