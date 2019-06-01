<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 29.08.2016
 * Time: 23:34
 */

class ToursRequestsController extends BackendController{

    /**
     * @return array
     */
    public function filters() {
        return [
            'accessControl',
            'ajaxOnly + tourInfo',
        ];
    }

    /**
     * Shows showcase of tours
     * @throws CHttpException
     */
    public function actionIndex() {
        $model = new ArClientsToursRequests();
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['ArClientsToursRequests'])) {
            $model->setAttributes($_GET['ArClientsToursRequests'], false);
        }

        $this->render('index', ['model' => $model]);
    }

    /**
     * Tour Info
     */
    public function actionTourInfo() {
        $id = Yii::app()->request->getParam('id', 0);
        $this->renderPartial('common_views.tour_request.info', ['id' => $id]);
    }
}