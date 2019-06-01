<?php

/**
 * TourShowcaseStandardSettings class.
 * TourShowcaseStandardSettings is the data structure for showcase's settings
 *
 * @property string $bg_color
 * @property string $bg_block_color
 * @property string $tour_link_color
 * @property string $price_color
 * @property string $price_label_color
 * @property string $icon_color
 * @property string $pagination_color
 * @property integer $rounding
 * @property integer $open_tour_target
 * @property integer $country
 * @property integer $resort
 * @property integer $category
 * @property array $operators
 * @property array $countries
 * @property integer $per_row
 * @property integer $currency
 */
class TourShowcaseStandardSettings extends CFormModel {


    /********* Design *********/

    /**
     * @var string
     */
    public $bg_color;

    /**
     * @var string
     */
    public $bg_block_color;

    /**
     * @var string
     */
    public $tour_link_color;

    /**
     * @var string
     */
    public $price_color;

    /**
     * @var string
     */
    public $price_label_color;

    /**
     * @var string
     */
    public $icon_color;

    /**
     * @var string
     */
    public $pagination_color;

    /**
     * @var int
     */
    public $rounding;

    /**
     * @var string
     */
    public $open_tour_target;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var int
     */
    public $rows;

//    public $per_row;



    /********* Default values *********/

    /**
     * @var int
     */
    public $country;

    /**
     * @var int
     */
    public $resort;

    /**
     * @var int
     */
    public $category;


    /********* Filters *********/

    /**
     * @var array
     */
    public $operators;

    /**
     * @var array
     */
    public $countries;




    /**
     * Declares the validation rules.
     */
    public function rules()	{
        return [
            // name, email, subject and body are required
            ['bg_color, bg_block_color, tour_link_color, price_color, price_label_color, icon_color, pagination_color', 'length', 'max' => 20],
            ['rounding, open_tour_target, country, resort, category, currency, rows', 'numerical', 'integerOnly' => true],
            ['operators, countries', 'safe'],
        ];
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'bg_color' => 'Общий фон',
            'bg_block_color' => 'Фон блоков',
            'tour_link_color' => 'Ссылки отелей',
            'currency' => 'Валюта',
            'price_color' => 'Цены',
            'price_label_color' => 'Этикетка цены',
            'icon_color' => 'Иконки',
            'pagination_color' => 'Кнопка закгрузки туров',
            'rounding' => 'Закругления общего фона',
            'open_tour_target' => 'Открывать тур',
            'rows' => 'Количество рядов',
//            'per_row' => 'Количество туров в ряду',
        );
    }


    /**
     * Returns list of tour's targets
     * @return array
     */
    public function tourTargetsList(){
        return [
            '_popup' => 'В всплывающем окне',
            '_blank' => 'В новом окне',
            '_parent' => 'В текущем окне',
        ];
    }

    /**
     * Returns list of currencies
     * @return array
     */
    public function currenciesList(){
        return [
            0 => 'Валюта страны',
            1 => 'Рубли',
        ];
    }

    /**
     * Returns list of rounding
     * @return array
     */
    public function roundingList(){
        return array(
            '0' => 'Без закруглений',
            '1' => '1 px',
            '2' => '2 px',
            '3' => '3 px',
            '4' => '4 px',
            '5' => '5 px',
            '6' => '6 px',
            '7' => '7 px',
            '8' => '8 px',
            '9' => '9 px',
            '10' => '10 px',
            '11' => '11 px',
            '12' => '12 px',
            '13' => '13 px',
            '14' => '14 px',
            '15' => '15 px',
            '16' => '16 px',
            '17' => '17 px',
            '18' => '18 px',
            '19' => '19 px',
            '20' => '20 px',
        );
    }


    /**
     * Returns list of rows
     * @return array
     */
    public function rowsList(){
        return [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10=> 10
        ];
    }

}