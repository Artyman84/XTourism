<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */

class TSearcherResorts extends CWidget{

    /**
     * @var int|array
     */
    public $selectedIds = array();

    /**
     * @var bool
     */
    public $flightNonstop = true;

    /**
     * @var array
     */
    public $htmlOptions = array();

    /**
     * @var array
     */
    public $labelOptions = array();

    /**
     * @var array
     */
    public $listOptions = array();

    /**
     * @var array
     */
    public $data = [];

    /**
     * Init
     */
    public function init(){
        parent::init();
    }

    public function run(){

        echo CHtml::openTag('div', array('id' => $this->getId()));
            echo CHtml::openTag('div', array('class' => 'xtourism-subsection'));
                echo CHtml::openTag('label', array('class' => 'xtourism-label'));
                    echo CHtml::tag('i', array('class' => 'fa fa-globe'), '') . 'Курорт';
                echo CHtml::closeTag('label');

                //echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-group t-resorts-group'));
                echo CHtml::openTag('div', array('class' => 'xtourism-input-group', 'style' => 'overflow-x: hidden; overflow-y: hidden; padding:0;'));
                echo CHtml::openTag('div', array('class' => 'xtourism-input t-resorts-group', 'style' => 'overflow-x: hidden; overflow-y: scroll; height: 262px; padding: 3px 0 3px 10px;'));

                    // категории курортов делятся на "Популярные" и "Остальные". Пока будут только "Остальные".
                    //echo CHtml::tag('div', array('class' => 'xtourism-input-group-title'), 'Остальные');
                    echo CHtml::openTag('ul', array('class' => 't-resortsList'));

                        $selectedIds = array_flip($this->selectedIds);
                        foreach( $this->data as $id => $resort ){

                            $attributes = array('value' => $id);

                            if( isset($selectedIds[$id]) ){
                                $attributes['class'] = 'active';
                            }

                            echo CHtml::tag('li', $attributes, '<i></i>' . $resort);
                        }
                    echo CHtml::closeTag('ul');

                echo CHtml::closeTag('div');
                echo CHtml::closeTag('div');

            echo CHtml::closeTag('div');

            echo CHtml::openTag('div', array('class' => 'xtourism-subsection'));
                echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-checkbox'));
                    echo CHtml::checkBox('fli', (bool)$this->flightNonstop, array('id' => 'fli', 'value' => 1));
                    echo CHtml::label('Есть авиабилеты', 'fli');
                echo CHtml::closeTag('div');
            echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');
    }

}