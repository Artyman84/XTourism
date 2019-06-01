<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */

class TSearcherOperators extends CWidget{

    /**
     * @var int|array
     */
    public $selectedIds = [];

    /**
     * @var integer
     */
    public $height;

    /**
     * @var array
     */
    public $data = [];



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
     * Init
     */
    public function init(){
        parent::init();
    }

    public function run(){

        echo CHtml::openTag('div', array('class' => 'xtourism-subsection', 'id' => $this->getId()));
            echo CHtml::openTag('label', array('class' => 'xtourism-label'));
                echo CHtml::tag('i', array('class' => 'fa fa-building-o'), '') . 'Операторы';
            echo CHtml::closeTag('label');

            $height = $this->height ? ' height: ' . $this->height . 'px;' : '';
            echo CHtml::openTag('div', array('class' => 'xtourism-input-group', 'style' => 'overflow-x: hidden; overflow-y: hidden; padding:0;' . $height));
            echo CHtml::openTag('div', array('class' => 'xtourism-input t-operators-group', 'style' => 'overflow-x: hidden; overflow-y: scroll; padding: 3px 0 3px 10px;' . $height));

                echo CHtml::openTag('ul', array('class' => 't-operatorsList'));

                    $selectedIds = array_flip($this->selectedIds);
                    foreach( $this->data as $id => $operator ){

                        $attributes = ['value' => $id];

                        if( isset($selectedIds[$id]) ){
                            $attributes['class'] = 'active';
                        }

                        echo CHtml::tag('li', $attributes, '<i></i>' . $operator);
                    }
                echo CHtml::closeTag('ul');
            echo CHtml::closeTag('div');
            echo CHtml::closeTag('div');

        echo CHtml::closeTag('div');
    }
}