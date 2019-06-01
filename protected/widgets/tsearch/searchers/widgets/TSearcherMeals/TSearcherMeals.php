<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:33
 * To change this template use File | Settings | File Templates.
 */

class TSearcherMeals extends CWidget{

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
     * @var bool
     */
    public $more = true;

    /**
     * @var array
     */
    public $data = array();

    /**
     * Initializing
     */
    public function init(){
        parent::init();

        $this->selectedId = (int)$this->selectedId;

        if( !isset( $this->data[$this->selectedId] ) ){
            $this->selectedId = key($this->data);
        }

        $this->listOptions['encode'] = false;
    }

    public function run(){

        if( isset($this->labelOptions['class']) ){
            $this->labelOptions['class'] .= ' xtourism-label';
        } else {
            $this->labelOptions['class'] = 'xtourism-label';
        }

        if( isset($this->htmlOptions['class']) ){
            $this->htmlOptions['class'] .= ' xtourism-subsection';
        } else {
            $this->htmlOptions['class'] = 'xtourism-subsection';
        }

        echo CHtml::openTag('div', $this->htmlOptions);
            echo CHtml::label('<i class="fa fa-cutlery"></i>Тип питания', null, $this->labelOptions);

            echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-checkbox'));
                echo CHtml::checkBox('mealsMore', $this->more, array('id' => 'mealsMore', 'value' => 1));
                echo CHtml::label('и лучше', 'mealsMore');
            echo CHtml::closeTag('div');


            echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-select'));
                echo CHtml::dropDownList('meals', $this->selectedId, $this->data, $this->listOptions);
            echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');
    }
}