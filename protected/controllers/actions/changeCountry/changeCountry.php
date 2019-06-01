<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 28.05.2016
 * Time: 14:47
 */

use TSearch\Searcher AS TSearcher;

class changeCountry extends CAction {

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

        list($r, $h, $o) = $searcher->RHO($this->dirDepCity, $this->dirCountry);

        echo CJSON::encode(['r' => $r, 'h' => $h, 'o' => $o]);

    }

}