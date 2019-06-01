<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 28.05.2016
 * Time: 14:47
 */

use TSearch\Searcher AS TSearcher;

class changeDepCity extends CAction {

    /**
     * Departure city
     * @var integer
     */
    public $dirDepCity;

    /**
     * Country
     * @var integer
     */
    public $dirCountry;

    /**
     * User ID
     * @var integer
     */
    public $user_id;

    /**
     * Run Action
     */
    public function run(){

        $settings = null;
        if( $this->user_id ){
            $settings = ArUserSearcher::model()->findByAttributes(['user_id' => $this->user_id])->searcherSettings();
        }

        $searcher = new TSearcher($settings);
        $c = $searcher->countries($this->dirDepCity, false);
        $r = $h = $o = [];

        if( !empty($c) ) {
            $isValidCnt = false;
            foreach( $c as $country ) {
                if($this->dirCountry == $country->id){
                    $isValidCnt = true;
                    break;
                }
            }

            $dirCurrentCnt = $isValidCnt ? $this->dirCountry : $c[0]->id;
            list($r, $h, $o) = $searcher->RHO($this->dirDepCity, $dirCurrentCnt);
        }


        echo CJSON::encode(['c' => $c, 'o' => $o, 'r' => $r, 'h' => $h]);
    }
}