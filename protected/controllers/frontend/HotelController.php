<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 17.05.2016
 * Time: 16:41
 */

Yii::import('tsearch.hotel.*');


class HotelController extends FrontendController {

    /**
     * @var string
     */
    public $layout = 'tourInfo';

    /*
     * Filters
     */
    public function filters() {
        return [];
    }

    /**
     * Action "Hotel Info"
     */
    public function actionHotelInfo() {
        $hId = Yii::app()->request->getParam('hId');
        $id = TUtil::decode_hotel_id($hId);

        $hotel = ArDirHotels::model()->with(['services', 'residence', 'photos', 'cards', 'ratings', 'category'])->findByPk($id, 't.disabled = 0');
        $uid = Yii::app()->request->getParam('uid', null);

        if( !$hotel ){
            $this->decorator('alert', '<strong><span class="glyphicon glyphicon-warning-sign"></span> Информация об отеле временно недоступна.</strong>', ['class' => 'warning']);
            Yii::app()->end();
        }

        $this->render('hotelInfo', ['hotel' => $hotel, 'uid' => $uid]);
    }

    public function actionSaveUserComplaint(){
        $model = new ArHotelComplaints();
        $attributes = Yii::app()->request->getPost('ArHotelComplaints', []);

        $attributes['dir_hotel_id'] = TUtil::decode_hotel_id($attributes['dir_hotel_id']);
        $attributes['ip'] = $_SERVER['REMOTE_ADDR'];
        $attributes['user_id'] = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
        $attributes['time'] = time();

        $model->attributes = $attributes;
        $model->save();
    }

    /**
     * Action "Operator's Hotel Info"
     * @param integer $id
     */
    public function actionOperatorHotelInfo($id) {
        $oid = Yii::app()->request->getParam('oid', 0);

        if( $operator = TSearch\TOperator::newOperator($oid) ) {
            $hotel_info = $operator->load('hotel_info', ['id' => $id]);
            if( $hotel_info ){
                $this->render('operatorHotelInfo', ['hotel_info' => $hotel_info[0]]);
            }
        }

        Yii::app()->end();
    }
}