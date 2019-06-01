<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:50
 * To change this template use File | Settings | File Templates.
 */

class TSearcherPeople extends CWidget{

    /**
     * @var integer
     */
    public $selAdults = 2;

    /**
     * @var integer
     */
    public $selChildren = 0;

    /**
     * @var integer
     */
    public $selChild1 = 1;

    /**
     * @var integer
     */
    public $selChild2 = 1;

    /**
     * @var integer
     */
    public $selChild3 = 1;

    /**
     * @var int
     */
    private $maxAdults = 4;

    /**
     * @var int
     */
    private $maxChildren = 3;

    /**
     * @var int
     */
    private $maxChildrenAge = 14;

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
    private $children = array();

    public function init(){
        parent::init();

        for( $chAge=1; $chAge<=$this->maxChildrenAge; $chAge++ ){
            $this->children[$chAge] = $chAge;
        }
    }

    public function run(){
        if( isset($this->labelOptions['class']) ){
            $this->labelOptions['class'] .= ' xtourism-label xtourism-label-narrow';
        } else {
            $this->labelOptions['class'] = 'xtourism-label xtourism-label-narrow';
        }


        echo CHtml::openTag('div', array('id' => $this->getId()));

            echo CHtml::openTag('div', array('class' => 'xtourism-subsection'));

                echo CHtml::openTag('label', $this->labelOptions);
                    echo CHtml::tag('i', array('class' => 'fa fa-user'), '') . 'Взрослых';
                echo CHtml::closeTag('label');

                echo CHtml::openTag('label', $this->labelOptions);
                    echo CHtml::tag('i', array('class' => 'fa fa-users'), '') . 'Детей';
                echo CHtml::closeTag('label');

                echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-switcher'));
                    for( $adult=1; $adult <= $this->maxAdults; ++$adult ){
                        echo CHtml::radioButton('adults', $this->selAdults == $adult, array('value' => $adult, 'id' => 'adults' . $adult));
                        echo CHtml::tag('label', array('for' => 'adults' . $adult), $adult);
                    }
                echo CHtml::closeTag('div');

                echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-switcher'));
                    for( $child=0; $child <= $this->maxChildren; ++$child ){
                        echo CHtml::radioButton('children', $this->selChildren == $child, array('value' => $child, 'id' => 'children' . $child));
                        echo CHtml::tag('label', array('for' => 'children' . $child), $child);
                    }
                echo CHtml::closeTag('div');

            echo CHtml::closeTag('div');


            echo CHtml::openTag('div', array('class' => 'xtourism-subsection'));
                echo CHtml::openTag('label', array('class' => 'xtourism-label'));
                    echo CHtml::tag('i', array('class' => 'fa fa-child'), '') . 'Возраст детей';
                echo CHtml::closeTag('label');

                $disabledClass = $this->selChildren < 1 ? 'xtourism-input-disabled' : '';
                echo CHtml::openTag('div', array('class' => 'xtourism-input ' . $disabledClass . ' xtourism-input-select'));
                    echo CHtml::dropDownList('child1', $this->selChild1, $this->children, array('disabled' => $disabledClass ? 'disabled' : '', 'id' => 'child1'));
                echo CHtml::closeTag('div');

                $disabledClass = $this->selChildren < 2 ? 'xtourism-input-disabled' : '';
                echo CHtml::openTag('div', array('class' => 'xtourism-input ' . $disabledClass . ' xtourism-input-select'));
                    echo CHtml::dropDownList('child2', $this->selChild2, $this->children, array('disabled' => $disabledClass ? 'disabled' : '', 'id' => 'child2'));
                echo CHtml::closeTag('div');

                $disabledClass = $this->selChildren < 3 ? 'xtourism-input-disabled' : '';
                echo CHtml::openTag('div', array('class' => 'xtourism-input ' . $disabledClass . ' xtourism-input-select'));
                    echo CHtml::dropDownList('child3', $this->selChild3, $this->children, array('disabled' => $disabledClass ? 'disabled' : '', 'id' => 'child3'));
                echo CHtml::closeTag('div');
            echo CHtml::closeTag('div');

        echo CHtml::closeTag('div');

        echo '<script type="text/javascript">
                (function(){
                    $(function(){
                        $("body").on("change", "div#' . $this->getId() . ' :radio[name=\'children\']", function(){

                            var children = parseInt($(this).val());
                            var arChildren = [1, 2, 3];

                            for( var ch in arChildren ){
                                if( children >= arChildren[ch] ){
                                    $("div#' . $this->getId() . ' select[name=\'child" + arChildren[ch] + "\']").attr("disabled", false).parent().removeClass("xtourism-input-disabled");
                                } else {
                                    $("div#' . $this->getId() . ' select[name^=\'child" + arChildren[ch] + "\']").attr("disabled", true).val(1).parent().addClass("xtourism-input-disabled");
                                }
                            }

                        });
                    })
                })();
              </script>';
    }

}