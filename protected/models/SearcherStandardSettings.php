<?php

/**
 * SearcherStandardSettings class.
 * SearcherStandardSettings is the data structure for searcher's settings
 *
 * @property string $bg_color_class
 * @property integer $spinner
 * @property integer $rounding
 * @property integer $depCity
 * @property integer $country
 * @property integer $hotelCategoryMore
 * @property integer $hotelCategory
 * @property integer $nightFrom
 * @property integer $nightTo
 * @property integer $minPrice
 * @property integer $maxPrice
 * @property integer $currency
 * @property integer $adults
 * @property integer $children
 * @property integer $child1
 * @property integer $child2
 * @property integer $child3
 * @property integer $mealsMore
 * @property integer $meals
 */
class SearcherStandardSettings extends CFormModel {

    /********* Design *********/

    /**
     * @var string
     */
    public $bg_color_class;

    /**
     * @var integer
     */
    public $spinner;

    /**
     * @var integer
     */
    public $rounding;


    /********* Default values *********/

    /**
     * @var integer
     */
    public $depCity;

    /**
     * @var integer
     */
    public $country;

    /**
     * @var integer
     */
    public $hotelCategoryMore;

    /**
     * @var integer
     */
    public $hotelCategory;

    /**
     * @var integer
     */
    public $nightFrom;

    /**
     * @var integer
     */
    public $nightTo;

    /**
     * @var integer
     */
    public $minPrice;

    /**
     * @var integer
     */
    public $maxPrice;

    /**
     * @var integer
     */
    public $currency;

    /**
     * @var integer
     */
    public $adults;

    /**
     * @var integer
     */
    public $children;

    /**
     * @var integer
     */
    public $child1;

    /**
     * @var integer
     */
    public $child2;

    /**
     * @var integer
     */
    public $child3;

    /**
     * @var integer
     */
    public $mealsMore;

    /**
     * @var integer
     */
    public $meals;



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
     * @var array
     */
    public $dep_cities;


    /**
     * Declares the validation rules.
     */
    public function rules()	{
        return [
            // name, email, subject and body are required
            ['bg_color_class', 'length', 'max' => 20],
            ['rounding, spinner, depCity, country, hotelCategoryMore, hotelCategory, nightFrom, nightTo, minPrice, maxPrice, currency, adults, children, child1, child2, child3, mealsMore, meals', 'numerical', 'integerOnly' => true],
            ['operators, countries, dep_cities', 'safe'],
        ];
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'bg_color_class' => 'Цвет оформления',
            'spinner' => 'Тип спиннера',
            'rounding' => 'Закругления углов',
        );
    }

    /**
     * Returns possibles backgrounds classes of colors
     * @return array
     */
    public function bgColorClassesList(){
        return [ 'orange', 'yellow', 'red', 'green', 'cyan', 'blue', 'purple', 'marengo' ];
    }

    /**
     * Returns possibles rounding of searcher
     * @return array
     */
    public function roundingList(){
        return [ 0, 4, 8, 12, 18 ];
    }

    /**
     * Returns possibles spinners of searcher
     * @return array
     */
    public function spinnersList(){
        return [
            0 => 'Стандартный',
            1 => 'Эквалайзер',
            5 => 'Круг',
//            4 => 'Круговой 1',
//            5 => 'Круговой 2',
//            6 => 'Круговой 3',
            7 => 'Горизонтальный',
            8 => 'Обруч',
        ];
    }

}