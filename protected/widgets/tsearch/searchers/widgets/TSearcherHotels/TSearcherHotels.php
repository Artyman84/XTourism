<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */

class TSearcherHotels extends CWidget{

    /**
     * @var int|array
     */
    public $selectedIds = array();

    /**
     * @var bool
     */
    public $hotelNonstop = true;

    /**
     * @var string
     */
    public $searchText = '';

    /**
     * @var array
     */
    public $htmlOptions = [];

    /**
     * @var array
     */
    public $labelOptions = [];

    /**
     * @var array
     */
    public $listOptions = [];

    /**
     * @var array
     */
    public $data = [];

    /**
     * Init
     */
    public function init(){
        parent::init();
        if( !isset($this->htmlOptions['id']) ){
            $this->htmlOptions['id'] = $this->getId();
        }
    }

    public function run(){

        echo CHtml::openTag('div', $this->htmlOptions);

            echo CHtml::openTag('div', array('class' => 'xtourism-subsection'));
                echo CHtml::openTag('label', array('class' => 'xtourism-label hotel-label'));
                    echo CHtml::tag('i', array('class' => 'fa fa-building-o'), '') . 'Отель';
                echo CHtml::closeTag('label');

                echo CHtml::openTag('span', array('class' => 'xtourism-label hotel-actions-label'));
                    echo CHtml::tag('a', array('href' => '#', 'id' => 'allHotels', 'class' => 'non-active'), 'показать все');
                    echo CHtml::tag('a', array('href' => '#', 'id' => 'selectedHotels', 'class' => 'non-access'), 'показать выбранные');
                echo CHtml::closeTag('span');

                echo CHtml::openTag('div', array('class' => 't-hotels-group'));
                    echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-text'));
                        echo CHtml::textField('searchText', $this->searchText, array('id' => 'searchHotelText', 'placeholder' => 'Введите название отеля...'));
                    echo CHtml::closeTag('div');

                    //echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-group', 'id' => 'hotels-group'));
                    echo CHtml::openTag('div', array('class' => 'xtourism-input-group', 'style' => 'overflow-x: hidden; overflow-y: hidden; padding:0;'));
                    echo CHtml::openTag('div', array('class' => 'xtourism-input', 'id' => 'hotels-group', 'style' => 'overflow-x: hidden; overflow-y: scroll; height: 231px; padding: 3px 0 3px 10px;'));


                        // категории отелей делятся на "Популярные" и "Остальные". Пака будут только "Остальные".
                        //echo CHtml::tag('div', array('class' => 'xtourism-input-group-title'), 'Остальные');
                        echo CHtml::openTag('ul', array('class' => 't-hotelsList'));

                            $selectedIds = array_flip($this->selectedIds);
                            foreach( $this->data as $id => $hotel ){

                                $attributes = array('value' => $id);

                                if( isset($selectedIds[$id]) ){
                                    $attributes['class'] = 'active';
                                }

                                echo CHtml::tag('li', $attributes, '<i></i>' . CHtml::encode($hotel));
                            }
                        echo CHtml::closeTag('ul');
                    echo CHtml::closeTag('div');
                    echo CHtml::closeTag('div');

                echo CHtml::closeTag('div');

            echo CHtml::closeTag('div');

            echo CHtml::openTag('div', array('class' => 'xtourism-subsection'));
                echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-checkbox'));
                    echo CHtml::checkBox('ph', (bool)$this->hotelNonstop, array('id' => 'ph', 'value' => 1));
                    echo CHtml::label('Есть места в отеле', 'ph');
                echo CHtml::closeTag('div');
            echo CHtml::closeTag('div');

        echo CHtml::closeTag('div');
    }

}