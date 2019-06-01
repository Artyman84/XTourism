<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:50
 * To change this template use File | Settings | File Templates.
 */

class TSearcherCurrency extends CWidget{

    /**
     * @var int
     */
    public $isCU = true;

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

            echo CHtml::label('<i class="fa fa-usd"></i>Валюта поиска', null, $this->labelOptions);

            echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-switcher xtourism-input-switcher-2'));
                echo CHtml::radioButton('currency', !$this->isCU, array('id' => 'currency0', 'value' => 0));
                echo CHtml::label('руб.', 'currency0');
                echo CHtml::radioButton('currency', $this->isCU, array('id' => 'currency1', 'value' => 1));
                echo CHtml::label('у.е.', 'currency1');
            echo CHtml::closeTag('div');

        echo CHtml::closeTag('div');
    }

}