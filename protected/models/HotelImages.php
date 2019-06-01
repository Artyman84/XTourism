<?php

/**
 * HotelImage class.
 */
class HotelImages extends CFormModel {

    /**
     * @var
     */
    public $images;

    /**
     * Declares the validation rules.
     */
    public function rules()	{
        return [
            array('images', 'file', 'types' => 'jpg', 'maxFiles' => 90, 'maxSize' => 3145728),
        ];
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return [
            'images' => 'Изображение',
        ];
    }
}