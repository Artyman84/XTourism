<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 28.05.2016
 * Time: 14:47
 */


class searchByHotel extends CAction {

    /**
     * Hotel ID
     * @var integer
     */
    public $hotel_id;

    /**
     * Param
     * @var array
     */
    public $params;


    /**
     * Run Action
     */
    public function run(){

        $hotel = ArDirHotels::model()->findByPk($this->hotel_id);

        if( $hotel ) {
            $this->params['dirCountry'] = $hotel->dir_country_id;
            $this->params['dirHotels'][0] = $hotel->id;
            $this->params['flightNonstop'] = 1;
            $this->params['hotelNonstop'] = 1;

            (new TSearch\SearchTour($this->params))->loadTours(1);
        }

    }

}