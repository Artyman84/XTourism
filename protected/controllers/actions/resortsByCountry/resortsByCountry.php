<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 28.05.2016
 * Time: 14:47
 */

class resortsByCountry extends CAction {

    /**
     * Country
     * @var integer
     */
    public $dirCountry;

    /**
     * Run Action
     */
    public function run(){

        $resorts = Yii::app()->db->createCommand()
            ->select('id, name')
            ->from('{{directory_resorts}}')
            ->where('dir_country_id = :cid', [':cid' => $this->dirCountry])
            ->order('name')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        echo CJSON::encode($resorts);
    }

}