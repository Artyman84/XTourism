<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 30.07.2017
 * Time: 14:18
 */

/**
 *
 */
class HComplaint extends CWidget {

    /**
     * @var ArDirHotels
     */
    public $hotel_hash_id;


    /**
     * Init
     */
    public function init(){
        parent::init();
    }

    /**
     * Run
     */
    public function run(){?>
        <div class="alert alert-warning fade in">
            <small>
                <h4><i class="fa fa-warning" aria-hidden="true"></i> Уважаемые посетители!</h4>
                <p>Данный сайт является информационным. Администрация сайта предупреждает о возможном несоответствии в предоставляемой информации и не несет ответственности за качество и достоверность информации. Также администрация сайта не несет ответственности за качество и достоверность информации предоставляемой туроператорами.</p>
                <p id="feedback_paragraph">Если Вы нашли какое-то несоответствие в предоставляемой информации об отеле, огромная просьба <a href="javascript://" onclick="$('#feedback_form').show(); return false;"><strong>сообщить</strong></a> нам!</p>

                <?php $form = $this->beginWidget('CActiveForm', array(
                    'htmlOptions' => array('role' => 'form', 'class' => 'form-horizontal', 'id' => 'feedback_form', 'style' => 'display: none;'),
                    'enableClientValidation' => false,
                    'enableAjaxValidation' => false,
                ));?>


                <? $hotel_complaint = new ArHotelComplaints(); ?>

                <hr>
                <?php echo $form->hiddenField($hotel_complaint, 'dir_hotel_id', ['value' => $this->hotel_hash_id]);?>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>
                                <?php echo $form->checkBox($hotel_complaint, 'name_not_valid'); ?>
                                <strong>Название отеля не соответствует</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>
                                <?php echo $form->checkBox($hotel_complaint, 'category_not_valid'); ?>
                                <strong>Категория отеля не соответствует</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>
                                <?php echo $form->checkBox($hotel_complaint, 'photos_not_valid'); ?>
                                <strong>Фотографии отеля не соответствуют</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-6">
                        <label for="feedback_comment" >Другое</label>
                        <?php echo $form->textArea($hotel_complaint, 'comment', ['class' => 'form-control', 'rows' => 3]); ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <? echo CHtml::ajaxSubmitButton( 'Сообщить', Yii::app()->createUrl('Hotel/saveUserComplaint'),
                            [
                                'beforeSend' => 'js:function(form, data, hasError){

                                    if( !$("#' . CHtml::activeId($hotel_complaint, 'name_not_valid') . ':checked").length &&
                                        !$("#' . CHtml::activeId($hotel_complaint, 'category_not_valid') . ':checked").length &&
                                        !$("#' . CHtml::activeId($hotel_complaint, 'photos_not_valid') . ':checked").length &&
                                        !$.trim($("#' . CHtml::activeId($hotel_complaint, 'comment') . '").val())
                                    ) {
                                        alert("Пожалуйста, выберите хотя бы один вариант на форме и/или оставьте свой коммент.");
                                        return false;
                                    }

                                    $.showFade();

                                }',
                                'success' => 'js:function(r){
                                    $("#feedback_paragraph").after("<div class=\'alert alert-success text-center\'>Спасибо, что проинформировали нас о возможном несоответствии в информации! Наши сотрудники в ближайшее время обработают Ваше сообщение и все перепроверят. Благодарим Вас за понимание и терпение!</div>");
                                    $("#feedback_form").remove();
                                    $("#feedback_paragraph").remove();

                                    $.hideFade();
                                }',
                                'type' => 'POST'
                            ],
                            ['class' => 'btn btn-default btn-xs', 'type' => 'submit']
                        ); ?>
                        <button type="button" class="btn btn-danger btn-xs" onclick="$('#feedback_form').hide(); return false;">Отмена</button>
                    </div>
                </div>
                <hr>

                <? $this->endWidget(); ?>

                <p>Копирование и перепечатка информации, а также других материалов содержащихся на сайте разрешается только с письменного разрешения администрации сайта.</p>
            </small>
        </div><?

    }

}