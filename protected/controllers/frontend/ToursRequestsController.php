<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 29.08.2016
 * Time: 23:34
 */

class ToursRequestsController extends FrontendController{

    /**
     * @return array
     */
    public function filters() {
        return array_merge(
            parent::filters(),
            [
              'ajaxOnly + changeState, delete, tourInfo',
              'ACL'
            ]
        );
    }

    /**
     * Checks user's ACL for "Fire Showcase" and "Searcher"
     * @param $filterChain
     */
    public function filterACL($filterChain){
        $package = ArShopUsersPackages::model()->findByAttributes(['user_id' => Yii::app()->user->id]);

        if( $package && $package->isValid() && ($package->hasProduct(ArShopProductsTypes::PDT_TOUR_SHOWCASE) || $package->hasProduct(ArShopProductsTypes::PDT_SEARCHER)) ) {

            $filterChain->run();

        } else {

            if (Yii::app()->request->isAjaxRequest) {
                echo 'expired';
            } else {
                $this->decorator('alert', '<p style="font-size: 18px;"><span style="top: 3px;" class="glyphicon glyphicon-ban-circle"></span> Доступ запрещен</p>', ['class' => 'danger']);
            }
        }

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

        $model->agent_id = Yii::app()->user->id;

        $this->render('index', ['tab' => 0, 'model' => $model]);
    }

    /**
     * Change tour's state
     * @throws CHttpException
     */
    public function actionChangeState() {
        $ids = (array)Yii::app()->request->getParam('ids', []);
        $state = Yii::app()->request->getParam('state');
        $db = Yii::app()->db;

        $db->createCommand()->update(
            '{{clients_tours_requests}}',
            ['state' => $state],
            ['AND', 'agent_id = :user_id', ['IN', 'id', $ids]], [':user_id' => Yii::app()->user->id]
        );

        echo $this->unreadCount();
    }


    /**
     * Change tour's state
     * @throws CHttpException
     */
    public function actionDelete() {
        $ids = (array)Yii::app()->request->getParam('ids', []);
        $db = Yii::app()->db;

        $db->createCommand()->delete(
            '{{clients_tours_requests}}',
            ['AND', 'agent_id = :user_id', ['IN', 'id', $ids]], [':user_id' => Yii::app()->user->id]
        );

        echo $this->unreadCount();
    }


    /**
     * Tour Info
     */
    public function actionTourInfo() {
        $id = Yii::app()->request->getParam('id', 0);
        Yii::app()->db->createCommand()->update('{{clients_tours_requests}}', ['state' => 0], 'id = :id AND state = 2', [':id' => $id]);

        echo CJSON::encode([
            'view' => $this->renderPartial('common_views.tour_request.info', ['id' => $id], true),
            'unread' => $this->unreadCount()
        ]);
    }


    /**
     * @return mixed
     */
    protected function unreadCount(){
        return Yii::app()->db->createCommand()
            ->select('COUNT(id)')
            ->from('{{clients_tours_requests}}')
            ->where('agent_id = :user_id AND state = 2',[':user_id' => Yii::app()->user->id])
            ->queryScalar();
    }
}