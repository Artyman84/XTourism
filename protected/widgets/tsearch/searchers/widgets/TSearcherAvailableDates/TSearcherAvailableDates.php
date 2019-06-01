<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:51
 * To change this template use File | Settings | File Templates.
 */

class TSearcherAvailableDates extends CWidget{

    /**
     * @var string
     * must be timestamp
     */
    public $selectedDateFrom = null;

    /**
     * @var string
     * must be timestamp
     */
    public $selectedDateTo = null;

    /**
     * @var int
     * timestamp
     */
    private $today;

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
    public $inputOptions = array();

    public function init(){
        parent::init();

        $this->today = strtotime('midnight');

        if( $this->selectedDateFrom < $this->today ){
            $this->selectedDateFrom = strtotime('+1 Days', $this->today);
        }

        if( $this->selectedDateFrom > $this->selectedDateTo ){
            $this->selectedDateTo = strtotime('+3 Days', $this->selectedDateFrom);
        }
    }

    public function run(){
        if( isset($this->labelOptions['class']) ){
            $this->labelOptions['class'] .= ' xtourism-label';
        } else {
            $this->labelOptions['class'] = 'xtourism-label';
        }

        echo CHtml::openTag('label', $this->labelOptions);
            echo CHtml::tag('i', array('class' => 'fa fa-calendar'), '') . 'Дата вылета';
        echo CHtml::closeTag('label');

        echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-text'));
            echo CHtml::tag('span', array('class' => 'prepend'), 'с');

            $this->widget('zii.widgets.jui.CJuiDatePicker', [
                'name' => 'availableDateFrom',
                'value' => Yii::app()->dateFormatter->format('dd.MM.yyyy', $this->selectedDateFrom),
                'options' => [
                    'showButtonPanel' => false,
                    'minDate' => Yii::app()->dateFormatter->format('dd.MM.yyyy', $this->today),
                    'onSelect' => 'js:function(dateText, inst){ $("#availableDateTo").datepicker("option", {"minDate": dateText}); }'
                ],
                'htmlOptions' => ['class' => 'start-date', 'id' => 'availableDateFrom'],
            ]);

        echo CHtml::closeTag('div');

        echo CHtml::openTag('div', array('class' => 'xtourism-input xtourism-input-text'));
            echo CHtml::tag('span', array('class' => 'prepend'), 'по');

            $this->widget('zii.widgets.jui.CJuiDatePicker', [
                'name' => 'availableDateTo',
                'value' => Yii::app()->dateFormatter->format('dd.MM.yyyy', $this->selectedDateTo),
                'options' =>[
                    'showButtonPanel' => false,
                    'minDate' => Yii::app()->dateFormatter->format('dd.MM.yyyy', $this->selectedDateFrom)
                ],
                'htmlOptions' => ['class' => 'end-date', 'id' => 'availableDateTo'],
            ]);

        echo CHtml::closeTag('div');
    }
}