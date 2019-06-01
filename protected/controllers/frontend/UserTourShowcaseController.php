<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 09.04.2015
 * Time: 9:51
 */


class UserTourShowcaseController extends FrontendController{

    /**
     * Filters
     * @return array
     */
    public function filters(){
        return [
            'accessControl',

            ['application.filters.HasTourShowcaseProduct', 'user_id' => Yii::app()->user->getId()],
            ['application.filters.XssFilter + saveSettings', 'clean' => 'GET,POST,COOKIE'],
        ];
    }


    /**
     * Actions
     * @return array
     */
    public function actions(){
        return [
            'saveSettings' => [
                'class' => 'actions.user_tour_showcase.saveSettings.saveSettings',
                'user_id' => Yii::app()->user->getId(),
                'redirect' => ['UserTourShowcase/index', 'tab' => 2]
            ],
            'saveValues' => [
                'class' => 'actions.user_tour_showcase.saveValues.saveValues',
                'user_id' => Yii::app()->user->getId(),
                'data' => Yii::app()->request->getPost('showcase', 0),
                'redirect' => ['UserTourShowcase/index', 'tab' => 1]
            ],
            'saveFilter' => [
                'class' => 'actions.user_tour_showcase.saveFilter.saveFilter',
                'user_id' => Yii::app()->user->getId(),
                'filter' => Yii::app()->request->getParam('filter', ''),
                'redirect' => ['UserTourShowcase/index', 'tab' => 3, 'f_tab' => Yii::app()->request->getParam('f_tab', 0)]
            ],
            'resortsByCountry' => [
                'class' => 'actions.user_tour_showcase.resortsByCountry.resortsByCountry',
                'dirCountry' => Yii::app()->request->getParam('country_id', 0),
                'user_id' => Yii::app()->user->getId()
            ]
        ];
    }

    /**
     * Index
     */
    public function actionIndex(){

        // first tab
        $model = $this->loadUserShowcaseModel();

        // second tab
        $settings = $model->showcaseSettings();
        $defaultSettings = ArUserTourShowcase::defaultSettings($model->type);

        $tab = (int)Yii::app()->request->getParam('tab', 0);

        $this->render(
            'index',
            array(
                'model' => $model,

                'settings' => $settings,
                'defaultSettings' => $defaultSettings,

                'tab' => $tab
            )
        );
    }


    /********************************* Protected *********************************/

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @return ArUserTourShowcase the loaded model
     * @throws CHttpException
     */
    protected function loadUserShowcaseModel() {
        $model = ArUserTourShowcase::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
        if( !$model ){
            throw new CHttpException(403, 'Витрина туров недоступна.');
        }

        return $model;
    }

}