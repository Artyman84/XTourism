<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:50
 * To change this template use File | Settings | File Templates.
 */

class TSearcherDurations extends CWidget{

    /**
     * @var string
     */
    public $selectedNightFrom = 7;

    /**
     * @var string
     */
    public $selectedNightTo = 14;

    /**
     * @var int
     */
    public $minNightNumber = 1;

    /**
     * @var int
     */
    public $maxNightNumber = 30;

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
    protected $nightsList = array();

    public function init(){
        parent::init();

        // defines list of nights
        for( $night=$this->minNightNumber; $night<=$this->maxNightNumber; ++$night ){
            $this->nightsList[$night] = $night;
        }

        // defines selected night "from"
        if( !isset( $this->nightsList[$this->selectedNightFrom] ) ){
            $this->selectedNightFrom = $this->minNightNumber;
        }

        // defines selected night "to"
        if( !isset( $this->nightsList[$this->selectedNightTo] ) ){
            $this->selectedNightTo = $this->minNightNumber;
        }

    }

    public function run(){
        if( isset($this->labelOptions['class']) ){
            $this->labelOptions['class'] .= ' xtourism-label';
        } else {
            $this->labelOptions['class'] = 'xtourism-label';
        }

        echo CHtml::openTag('div', array('id' => $this->getId()));
            echo CHtml::openTag('label', $this->labelOptions);
                echo CHtml::tag('i', array('class' => 'fa fa-clock-o'), '') . 'Количество ночей';
            echo CHtml::closeTag('label');

            echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-select'));
                echo CHtml::tag('span', array('class' => 'prepend'), 'от');
                echo CHtml::dropDownList('nightFrom', $this->selectedNightFrom, $this->nightsList, $this->listOptions);
            echo CHtml::closeTag('div');

            echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-select'));
                echo CHtml::tag('span', array('class' => 'prepend'), 'до');
                echo CHtml::dropDownList('nightTo', $this->selectedNightTo, $this->nightsList, $this->listOptions);
            echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');

        echo '<script type="text/javascript">
                (function(){
                    $(function(){
                        $("body").on("change", "#' . $this->getId() . ' select[name=\'nightFrom\']", function(){
                            var nightFrom = parseInt($(this).val());
                            var nightTo = parseInt($("#' . $this->getId() . ' select[name=\'nightTo\']").val());

                            if( nightFrom > nightTo ){
                                $("#' . $this->getId() . ' select[name=\'nightTo\']").val(nightFrom);
                            }
                        });

                        $("body").on("change", "#' . $this->getId() . ' select[name=\'nightTo\']", function(){
                            var nightTo = parseInt($(this).val());
                            var nightFrom = parseInt($("#' . $this->getId() . ' select[name=\'nightFrom\']").val());

                            if( nightTo < nightFrom ){
                                $("#' . $this->getId() . ' select[name=\'nightFrom\']").val(nightTo);
                            }
                        });
                    })
                })();
              </script>';
    }

}