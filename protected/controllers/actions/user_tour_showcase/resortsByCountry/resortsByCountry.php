<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 28.05.2016
 * Time: 14:47
 */

class resortsByCountry extends CAction {

    /**
     * User ID
     * @var integer
     */
    public $user_id;

    /**
     * Country
     * @var integer
     */
    public $dirCountry;

    /**
     * Run Action
     */
    public function run(){
        $userShowcase = ArUserTourShowcase::model()->findByAttributes(['user_id' => $this->user_id]);
        $resorts = (new \TSearch\ShowcaseTour())->resortsList($userShowcase->dc_dir_id, $this->dirCountry, false);
        echo CJSON::encode($resorts);
    }

}