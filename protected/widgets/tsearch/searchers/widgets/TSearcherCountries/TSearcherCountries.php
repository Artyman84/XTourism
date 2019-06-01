<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */

class TSearcherCountries extends CWidget{

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

    /**
     * Init
     */
    public function init(){
        parent::init();

        $this->selectedId = (int)$this->selectedId;

        if( !isset($this->data[$this->selectedId]) ){
            $currentCnt = key($this->data);
            $this->selectedId = $currentCnt;
        }
    }

    public function run(){

        if( isset($this->labelOptions['class']) ){
            $this->labelOptions['class'] .= ' xtourism-label';
        } else {
            $this->labelOptions['class'] = 'xtourism-label';
        }

        $this->listOptions['id'] = 'country';

        echo CHtml::openTag('label', $this->labelOptions);
            echo CHtml::tag('i', array('class' => 'fa fa-plane fa-rotate-90'), '') . 'Куда';
        echo CHtml::closeTag('label');

        echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-select'));
        echo CHtml::dropDownList('country', (int)$this->selectedId, $this->data, $this->listOptions);
        echo CHtml::closeTag('div');
    }

}