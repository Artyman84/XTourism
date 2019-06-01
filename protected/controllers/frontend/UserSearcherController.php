<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 09.04.2015
 * Time: 9:51
 */


class UserSearcherController extends FrontendController{

    /**
     * Filters
     * @return array
     */
    public function filters(){
        return array(
            'accessControl',
            ['application.filters.HasSearcherProduct', 'user_id' => Yii::app()->user->getId()],
            ['application.filters.XssFilter + saveDesign, saveDefaultValues, saveFilter'],
            'postOnly + saveDesign, saveDefaultValues, saveFilter',
        );
    }


    /**
     * Actions
     * @return array
     */
    public function actions(){
        $user_id = Yii::app()->user->getId();

        return [
            'saveDesign' => [
                'class' => 'actions.user_searcher.saveDesign.saveDesign',
                'user_id' => $user_id,
                'redirect' => ['UserSearcher/index', 'tab' => 1]
            ],
            'saveDefaultValues' => [
                'class' => 'actions.user_searcher.saveDefaultValues.saveDefaultValues',
                'user_id' => $user_id,
                'redirect' => ['UserSearcher/index', 'tab' => 2]
            ],
            'saveFilter' => [
                'class' => 'actions.user_searcher.saveFilter.saveFilter',
                'user_id' => $user_id,
                'filter' => Yii::app()->request->getParam('filter', ''),
                'redirect' => ['UserSearcher/index', 'tab' => 3, 'f_tab' => Yii::app()->request->getParam('f_tab', 0)]
            ],
        ];
    }


    /**
     * Index
     */
    public function actionIndex(){

        $model = $this->loadUserSearcherModel();
        $tab = (int)Yii::app()->request->getParam('tab', 0);

        $this->render( 'index', [ 'model' => $model, 'tab' => $tab ] );
    }



    /********************************* Protected *********************************/

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @return ArUserSearcher the loaded model
     * @throws CHttpException
     */
    protected function loadUserSearcherModel() {
        $model = ArUserSearcher::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
        if( !$model ){
            throw new CHttpException(403, 'Поск туров недоступен. Пожалуйста, обратитесь к вашему администратору: ' . Yii::app()->params['adminEmail']);
        }

        return $model;
    }
}



