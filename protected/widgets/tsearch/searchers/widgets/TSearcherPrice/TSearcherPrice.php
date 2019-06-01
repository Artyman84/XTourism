<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:50
 * To change this template use File | Settings | File Templates.
 */

class TSearcherPrice extends CWidget{

    /**
     * @var int
     */
    public $minPrice = 0;

    /**
     * @var int
     */
    public $maxPrice = 1000000;

    /**
     * @var array
     */
    public $htmlOptions = array();

    /**
     * @var array
     */
    public $labelOptions = array();


    public function init(){
        parent::init();
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
            echo CHtml::label('<i class="fa fa-usd"></i>Стоимость тура', null, $this->labelOptions);

            echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-text'));
                echo CHtml::tag('span', array('class' => 'prepend'), 'от');
                echo CHtml::textField('minPrice', $this->minPrice, array('id' => 'minPrice', 'maxlength' => 7));
            echo CHtml::closeTag('div');

            echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-text'));
                echo CHtml::tag('span', array('class' => 'prepend'), 'до');
                echo CHtml::textField('maxPrice', $this->maxPrice, array('id' => 'maxPrice', 'maxlength' => 7));
            echo CHtml::closeTag('div');

        echo CHtml::closeTag('div');
    }

}