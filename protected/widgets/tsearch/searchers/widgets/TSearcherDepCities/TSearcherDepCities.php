<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:33
 * To change this template use File | Settings | File Templates.
 */

class TSearcherDepCities extends CWidget{
    /**
     * @var int
     */
    public $selectedId = 0;

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

    public function init(){
        parent::init();

        $this->selectedId = (int)$this->selectedId;

        if( !isset($this->data[$this->selectedId]) ){
            $currentDepCity = key($this->data);
            $this->selectedId = $currentDepCity;
        }

    }

    public function run(){

        if( isset($this->labelOptions['class']) ){
            $this->labelOptions['class'] .= ' xtourism-label';
        } else {
            $this->labelOptions['class'] = 'xtourism-label';
        }

        $this->listOptions['id'] = 'depCity';

        echo CHtml::openTag('label', $this->labelOptions);
            echo CHtml::tag('i', array('class' => 'fa fa-plane'), '') . 'Откуда';
        echo CHtml::closeTag('label');

        echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-select'));
            echo CHtml::dropDownList('depCity', $this->selectedId, $this->data, $this->listOptions);
        echo CHtml::closeTag('div');
    }

}