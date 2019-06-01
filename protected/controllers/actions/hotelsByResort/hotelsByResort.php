<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 28.05.2016
 * Time: 14:47
 */

class hotelsByResort extends CAction {

    /**
     * Resort
     * @var integer
     */
    public $dirResort;

    /**
     * Run Action
     */
    public function run(){

        $hotels = Yii::app()->db->createCommand()
            ->select('id, name')
            ->from('{{directory_hotels}}')
            ->where('dir_resort_id = :rid', [':rid' => $this->dirResort])
            ->order('name')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        echo CJSON::encode($hotels);
    }

}