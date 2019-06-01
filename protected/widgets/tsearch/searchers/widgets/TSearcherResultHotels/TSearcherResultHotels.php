<?php

class TSearcherResultHotels extends CWidget{

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

            echo CHtml::openTag('table', array('class' => 'xtourism-results-table'));

            foreach( $this->data as $tours ){
                $tours = (array)$tours;
                $tCount = count($tours);

                $firstTour = (object)$tours[0];
                $lastTour = (object)$tours[$tCount-1];

                $tCountText = 'показать ' . TSearch\TourHelper::getToursCount($tCount);

                echo CHtml::openTag('tr'),

                        CHtml::openTag('td'),
                            CHtml::image($firstTour->hImgPath, '', array('width' => 165, 'height' => 70)),
                        CHtml::closeTag('td'),

                        CHtml::openTag('td'),
                            CHtml::openTag('h4'),
                                CHtml::link( $firstTour->hName . ' ' . $firstTour->hCategory, Yii::app()->createUrl('Hotel/hotelInfo', array('id' => $firstTour->hId)), array('class' => 'cap ib', 'target' => '_blank')), '&nbsp;',
                                CHtml::openTag('h4'),
                                    CHtml::tag('span', array('class' => 'cap ib'), $firstTour->hResort),
                                CHtml::closeTag('h4'),
                            CHtml::closeTag('h4'),
                        CHtml::closeTag('td'),

                        CHtml::openTag('td', array('class' => 'rating')),
                            'рейтинг: ', CHtml::tag('em', array(), $firstTour->hRating ? $firstTour->hRating : ''),
                            CHtml::openTag('div', array('class' => 'tip')),
                                CHtml::openTag('div', array('class' => 'tip-wrap')),
                                    CHtml::tag('div', array('class' => 'tip-text'), $firstTour->hScores),
                                CHtml::closeTag('div'),
                            CHtml::closeTag('div'),
                            CHtml::tag('span', array(), $firstTour->hVoices), '&nbsp;отзывов',
                        CHtml::closeTag('td'),

                        CHtml::openTag('td'),
                            CHtml::openTag('div', array('class' => 'price')),
                                CHtml::tag('span', array(), $firstTour->tNormalizedPrice), '&nbsp;&mdash;&nbsp;', CHtml::tag('span', array(), $lastTour->tNormalizedPrice), '&nbsp;', $firstTour->tHtmlCurrency,
                            CHtml::closeTag('div'),

                            CHtml::openTag('div', array('class' => 'show-all')),
                                CHtml::tag('a', array('href' => '#', 'class' => 't-showTours'), $tCountText),
                                CHtml::closeTag('div'),
                        CHtml::closeTag('td'),

                    CHtml::closeTag('tr'),

                    CHtml::openTag('tr'),
                        CHtml::openTag('td', array('colspan' => '4')),
                            $this->getHotelTours($tours),
                        CHtml::closeTag('td'),
                    CHtml::closeTag('tr');
            }

            echo CHtml::closeTag('table');
        }
    }

    /**
     * Returns all tours of hotel
     * @param array $tours
     * @return string
     */
    private function getHotelTours($tours){
        $htmlTours =
            CHtml::openTag('table') . CHtml::openTag('tbody') . CHtml::openTag('tr') .
                 CHtml::tag('th', array(), 'даты') .
                 CHtml::tag('th', array(), 'питание') .
                 CHtml::tag('th', array(), 'оператор') .
                 CHtml::tag('th', array(), 'размещение') .
                 CHtml::tag('th', array('colspan' => 5), 'доступность') .
                 CHtml::tag('th', array(), 'стоимость') .
                 CHtml::tag('th', array(), '&nbsp;') .
            CHtml::closeTag('tr');


        foreach( $tours as $tour ){
            $tour = (object)$tour;

            $htmlTours .=
                CHtml::openTag('tr') .

                    CHtml::openTag('td') .
                        CHtml::tag('span', array(), $tour->tStartResDateDM . '<i>' . $tour->tNightsTxt . '</i>') .
                        CHtml::openTag('div', array('class' => 'tip')) .
                            CHtml::openTag('div', array('class' => 'tip-wrap')) .
                                CHtml::tag('div', array('class' => 'tip-text'), $tour->tStartWeekDay . ' - ' . $tour->tEndWeekDay . '<br>' . $tour->tDaysTxt . ', ' . $tour->tNightsTxt) .
                            CHtml::closeTag('div') .
                        CHtml::closeTag('div') .
                    CHtml::closeTag('td') .

                    CHtml::openTag('td', array('class' => 'resfood')) .
                        CHtml::tag('span', array(), $tour->tMeal) .
                        CHtml::openTag('div', array('class' => 'tip')) .
                            CHtml::openTag('div', array('class' => 'tip-wrap')) .
                                CHtml::tag('div', array('class' => 'tip-text'), $tour->tMealDescription) .
                            CHtml::closeTag('div') .
                        CHtml::closeTag('div') .
                    CHtml::closeTag('td') .

                    CHtml::openTag('td') .
                        CHtml::openTag('span') .
                            CHtml::link('<img src="' . $tour->oImgPath . '" height="25" border="0/">', $tour->oUrl, array('target' => '_blank')) .
                        CHtml::closeTag('span') .
                        CHtml::openTag('div', array('class' => 'tip')) .
                            CHtml::openTag('div', array('class' => 'tip-wrap')) .
                                CHtml::openTag('div', array('class' => 'tip-text')) .
                                    CHtml::tag('span', array(), '<a href="' . $tour->oUrl . '" target="_blank">' . $tour->oName . '</a>') .
                                CHtml::closeTag('div') .
                            CHtml::closeTag('div') .
                        CHtml::closeTag('div') .
                    CHtml::closeTag('td') .

                    CHtml::openTag('td', array('class' => 'cap')) .
                        CHtml::tag('span', array(), $tour->hResidence) .
                        CHtml::openTag('div', array('class' => 'tip')) .
                            CHtml::openTag('div', array('class' => 'tip-wrap')) .
                                CHtml::tag('div', array('class' => 'tip-text'), $tour->tRoom) .
                            CHtml::closeTag('div') .
                        CHtml::closeTag('div') .
                    CHtml::closeTag('td') .

                    CHtml::openTag('td', array('class' => 'icon ' . $tour->hCssStatus)) .
                        CHtml::tag('i', array('class' => 'fa fa-home'), '') .
                        CHtml::openTag('div', array('class' => 'tip')) .
                            CHtml::openTag('div', array('class' => 'tip-wrap')) .
                                CHtml::tag('div', array('class' => 'tip-text'), 'Места в отеле: ' . $tour->hStatusDescription) .
                            CHtml::closeTag('div') .
                        CHtml::closeTag('div') .
                    CHtml::closeTag('td') .

                    CHtml::openTag('td', array('class' => 'icon ' . $tour->hCssETicketTo)) .
                        CHtml::tag('i', array('class' => 'fa fa-plane'), '') .
                        CHtml::openTag('div', array('class' => 'tip')) .
                            CHtml::openTag('div', array('class' => 'tip-wrap')) .
                                CHtml::tag('div', array('class' => 'tip-text'), 'Билеты туда (эконом класс): ' . $tour->hETicketDescriptionTo) .
                            CHtml::closeTag('div') .
                        CHtml::closeTag('div') .
                    CHtml::closeTag('td') .

                    CHtml::openTag('td', array('class' => 'icon ' . $tour->hCssETicketFrom)) .
                        CHtml::tag('i', array('class' => 'fa fa-plane fa-rotate-180'), '') .
                        CHtml::openTag('div', array('class' => 'tip')) .
                            CHtml::openTag('div', array('class' => 'tip-wrap')) .
                                CHtml::tag('div', array('class' => 'tip-text'), 'Билеты обратно (эконом класс): ' . $tour->hETicketDescriptionFrom) .
                            CHtml::closeTag('div') .
                        CHtml::closeTag('div') .
                    CHtml::closeTag('td') .

                    CHtml::openTag('td', array('class' => 'icon ' . $tour->hCssBTicketTo)) .
                        CHtml::tag('i', array('class' => 'fa fa-plane'), '') .
                        CHtml::openTag('div', array('class' => 'tip')) .
                            CHtml::openTag('div', array('class' => 'tip-wrap')) .
                                CHtml::tag('div', array('class' => 'tip-text'), 'Билеты туда (бизнес класс): ' . $tour->hBTicketDescriptionTo) .
                            CHtml::closeTag('div') .
                        CHtml::closeTag('div') .
                    CHtml::closeTag('td') .

                    CHtml::openTag('td', array('class' => 'icon ' . $tour->hCssBTicketTo)) .
                        CHtml::tag('i', array('class' => 'fa fa-plane fa-rotate-180'), '') .
                        CHtml::openTag('div', array('class' => 'tip')) .
                            CHtml::openTag('div', array('class' => 'tip-wrap')) .
                                CHtml::tag('div', array('class' => 'tip-text'), 'Билеты обратно (бизнес класс): ' . $tour->hBTicketDescriptionFrom) .
                            CHtml::closeTag('div') .
                        CHtml::closeTag('div') .
                    CHtml::closeTag('td') .

                    CHtml::openTag('td') .
                        CHtml::tag('div', array('class' => 'price'), '<span>' . $tour->tNormalizedPrice . '</span>&nbsp;' . $tour->tHtmlCurrency) .
                    CHtml::closeTag('td') .

                    CHtml::openTag('td') .
                        CHtml::openTag('div', array('class' => 'button', 'style' => 'width: 82px;')) .
                            CHtml::link('Заказ', Yii::app()->createUrl('FrontSearcher/tourRequest', ['hid' => $tour->hId, 'oid' => $tour->oId, 'start' => $tour->tStartResDate, 'nights' => $tour->tNights, 'dir_meal_id' => $tour->tMealDirId, 'residence' => $tour->hResidence, 'room' => $tour->tRoom, 'price' => $tour->tPrice, 'currency' => $tour->tCurrency]), ['target' => '_blank', 'class' => 'text']) .
                        CHtml::closeTag('div') .
                    CHtml::closeTag('td') .


                CHtml::closeTag('tr');

        }

        $htmlTours .= CHtml::closeTag('tbody') . CHtml::closeTag('table');

        return $htmlTours;
    }
}