<?php

class TSearcherResultTours extends CWidget{

    /**
     * List of hotels
     * @var array
     */
    public $data = [];

    /**
     * @var array
     */
    public $htmlOptions = [];

    /**
     * Init Result
     */
    public function init(){
    }

    /**
     * Run Result
     */
    public function run(){

        if( !empty($this->data) ){

            echo CHtml::openTag('table', array('class' => 'xtourism-results-table')),
                    CHtml::openTag('tr'),
                        CHtml::openTag('td', array('colspan' => 4, 'class' => 'bigger')),
                            CHtml::openTag('table'), CHtml::openTag('tbody'),
                                CHtml::openTag('tr'),
                                    CHtml::tag('th', array('collspan' => 11), 'отель, курорт'),
                                CHtml::closeTag('tr'),

                                CHtml::openTag('tr'),
                                    CHtml::tag('th', array(), 'даты'),
                                    CHtml::tag('th', array(), 'питание'),
                                    CHtml::tag('th', array(), 'оператор'),
                                    CHtml::tag('th', array(), 'размещение'),
                                    CHtml::tag('th', array('colspan' => 5), 'доступность'),
                                    CHtml::tag('th', array(), 'стоимость'),
                                    CHtml::tag('th', array(), '&nbsp;'),
                                CHtml::closeTag('tr');

                                foreach( $this->data as $tour ){
                                    $tour = (object)$tour;

                                    echo
                                    CHtml::openTag('tr'),
                                        CHtml::openTag('td', array('class' => 'hinfo', 'colspan' => 11)),
                                            CHtml::link($tour->hName . '&nbsp;' . $tour->hCategory, Yii::app()->createUrl('Hotel/hotelInfo', array('id' => $tour->hId)), array('class' => 'cap ib', 'target' => '_blank')), '&nbsp;&nbsp;', CHtml::tag('span', array(), $tour->hResort),
                                        CHtml::closeTag('td'),
                                    CHtml::closeTag('tr'),

                                    CHtml::openTag('tr', array('class' => 'unbord')),
                                        CHtml::openTag('td'),
                                            CHtml::tag('span', array(), $tour->tStartResDateDM . '<i>' . $tour->tNightsTxt . '</i>'),
                                            CHtml::openTag('div', array('class' => 'tip')),
                                                CHtml::openTag('div', array('class' => 'tip-wrap')),
                                                    CHtml::tag('div', array('class' => 'tip-text'), $tour->tStartWeekDay . ' - ' . $tour->tEndWeekDay . '<br>' . $tour->tDaysTxt . ', ' . $tour->tNightsTxt),
                                                CHtml::closeTag('div'),
                                            CHtml::closeTag('div'),
                                        CHtml::closeTag('td'),

                                        CHtml::openTag('td', array('class' => 'resfood')),
                                            CHtml::tag('span', array(), $tour->tMeal),
                                            CHtml::openTag('div', array('class' => 'tip')),
                                                CHtml::openTag('div', array('class' => 'tip-wrap')),
                                                    CHtml::tag('div', array('class' => 'tip-text'), $tour->tMealDescription),
                                                CHtml::closeTag('div'),
                                            CHtml::closeTag('div'),
                                        CHtml::closeTag('td'),

                                        CHtml::openTag('td'),
                                            CHtml::openTag('span'),
                                                CHtml::link('<img src="' . $tour->oImgPath . '" height="25" border="0/">', $tour->oUrl, array('target' => '_blank')),
                                            CHtml::closeTag('span'),
                                            CHtml::openTag('div', array('class' => 'tip')),
                                                CHtml::openTag('div', array('class' => 'tip-wrap')),
                                                    CHtml::openTag('div', array('class' => 'tip-text')),
                                                        CHtml::tag('span', array(), '<a href="' . $tour->oUrl . '" target="_blank">' . $tour->oName . '</a>'),
                                                    CHtml::closeTag('div'),
                                                CHtml::closeTag('div'),
                                            CHtml::closeTag('div'),
                                        CHtml::closeTag('td'),

                                        CHtml::openTag('td'),
                                            CHtml::tag('span', array(), $tour->hResidence),
                                            CHtml::openTag('div', array('class' => 'tip')),
                                                CHtml::openTag('div', array('class' => 'tip-wrap')),
                                                    CHtml::tag('div', array('class' => 'tip-text'), $tour->tRoom),
                                                CHtml::closeTag('div'),
                                            CHtml::closeTag('div'),
                                        CHtml::closeTag('td'),



                                        CHtml::openTag('td', array('class' => 'icon ' . $tour->hCssStatus)),
                                            CHtml::tag('i', array('class' => 'fa fa-home'), ''),
                                                CHtml::openTag('div', array('class' => 'tip')),
                                                    CHtml::openTag('div', array('class' => 'tip-wrap')),
                                                        CHtml::tag('div', array('class' => 'tip-text'), 'Места в отеле: ' . $tour->hStatusDescription),
                                                    CHtml::closeTag('div'),
                                            CHtml::closeTag('div'),
                                        CHtml::closeTag('td'),

                                        CHtml::openTag('td', array('class' => 'icon ' . $tour->hCssETicketTo)),
                                            CHtml::tag('i', array('class' => 'fa fa-plane'), ''),
                                            CHtml::openTag('div', array('class' => 'tip')),
                                                CHtml::openTag('div', array('class' => 'tip-wrap')),
                                                    CHtml::tag('div', array('class' => 'tip-text'), 'Билеты туда (эконом класс): ' . $tour->hETicketDescriptionTo),
                                                CHtml::closeTag('div'),
                                            CHtml::closeTag('div'),
                                        CHtml::closeTag('td'),

                                        CHtml::openTag('td', array('class' => 'icon ' . $tour->hCssETicketFrom)),
                                            CHtml::tag('i', array('class' => 'fa fa-plane fa-rotate-180'), ''),
                                            CHtml::openTag('div', array('class' => 'tip')),
                                                CHtml::openTag('div', array('class' => 'tip-wrap')),
                                                    CHtml::tag('div', array('class' => 'tip-text'), 'Билеты обратно (эконом класс): ' . $tour->hETicketDescriptionFrom),
                                                CHtml::closeTag('div'),
                                            CHtml::closeTag('div'),
                                        CHtml::closeTag('td'),

                                        CHtml::openTag('td', array('class' => 'icon ' . $tour->hCssBTicketTo)),
                                            CHtml::tag('i', array('class' => 'fa fa-plane'), ''),
                                            CHtml::openTag('div', array('class' => 'tip')),
                                                CHtml::openTag('div', array('class' => 'tip-wrap')),
                                                    CHtml::tag('div', array('class' => 'tip-text'), 'Билеты туда (бизнес класс): ' . $tour->hBTicketDescriptionTo),
                                                CHtml::closeTag('div'),
                                            CHtml::closeTag('div'),
                                        CHtml::closeTag('td'),

                                        CHtml::openTag('td', array('class' => 'icon ' . $tour->hCssBTicketFrom)),
                                            CHtml::tag('i', array('class' => 'fa fa-plane fa-rotate-180'), ''),
                                            CHtml::openTag('div', array('class' => 'tip')),
                                                CHtml::openTag('div', array('class' => 'tip-wrap')),
                                                    CHtml::tag('div', array('class' => 'tip-text'), 'Билеты обратно (бизнес класс): ' . $tour->hBTicketDescriptionFrom),
                                                CHtml::closeTag('div'),
                                            CHtml::closeTag('div'),
                                        CHtml::closeTag('td'),



                                        CHtml::openTag('td'),
                                            CHtml::tag('div', array('class' => 'price'), '<span>' . $tour->tNormalizedPrice . '</span>&nbsp;' . $tour->tHtmlCurrency),
                                        CHtml::closeTag('td'),

                                        CHtml::openTag('td'),
                                            CHtml::openTag('div', array('class' => 'button', 'style' => 'width: 82px;')),
                                        CHtml::link('Заказ', Yii::app()->createUrl('FrontSearcher/tourRequest', ['hid' => $tour->hId, 'oid' => $tour->oId, 'start' => $tour->tStartResDate, 'nights' => $tour->tNights, 'dir_meal_id' => $tour->tMealDirId, 'residence' => $tour->hResidence, 'room' => $tour->tRoom, 'price' => $tour->tPrice, 'currency' => $tour->tCurrency]), ['target' => '_blank', 'class' => 'text']) .
                                            CHtml::closeTag('div'),
                                        CHtml::closeTag('td'),

                                    CHtml::closeTag('tr');
                                }


            echo            CHtml::closeTag('tbody'), CHtml::closeTag('table'),
                        CHtml::closeTag('td'),
                    CHtml::closeTag('tr'),
                CHtml::closeTag('table');
        }
    }

}