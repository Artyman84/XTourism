<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 02.06.2015
 * Time: 11:29
 */

class UserTourShowcaseController extends BackendController{

    /**
     * Filters
     * @return array
     */
    public function filters() {
        return [
            'accessControl',
            ['application.filters.XssFilter + settings'],
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
                'user_id' => Yii::app()->getRequest()->getParam('user_id'),
                'redirect' => ['UserTourShowcase/Settings', 'user_id' => Yii::app()->getRequest()->getParam('user_id')]
            ],
            'saveValues' => [
                'class' => 'actions.user_tour_showcase.saveValues.saveValues',
                'user_id' => Yii::app()->getRequest()->getParam('user_id'),
                'data' => Yii::app()->request->getPost('showcase', 0),
                'redirect' => ['UserTourShowcase/Values', 'user_id' => Yii::app()->getRequest()->getParam('user_id')]
            ],
            'saveFilter' => [
                'class' => 'actions.user_tour_showcase.saveFilter.saveFilter',
                'user_id' => Yii::app()->getRequest()->getParam('user_id'),
                'filter' => Yii::app()->request->getParam('filter', ''),
                'redirect' => ['UserTourShowcase/Filters', 'user_id' => Yii::app()->getRequest()->getParam('user_id')]
            ],
            'resortsByCountry' => [
                'class' => 'actions.user_tour_showcase.resortsByCountry.resortsByCountry',
                'dirCountry' => Yii::app()->request->getParam('country_id', 0),
                'user_id' => Yii::app()->getRequest()->getParam('user_id')
            ]
        ];
    }


    /**
     * Action "Index"
     */
    public function actionIndex() {
        $model = new ArUserTourShowcase('search');
        $model->unsetAttributes();

        if(isset($_GET['ArUserTourShowcase'])) {
            $model->setAttributes($_GET['ArUserTourShowcase']);
        }

        $this->render('index', ['model' => $model]);
    }

    /**
     * Fire Showcase settings
     * @throws CHttpException
     */
    public function actionSettings() {

        $user_id = Yii::app()->request->getParam('user_id', 0);
        $model = $this->loadUserShowcaseModel($user_id);

        $this->render('design', ['model' => $model]);
    }


    /**
     * Fire Showcase default values
     * @throws CHttpException
     */
    public function actionValues() {

        $user_id = Yii::app()->request->getParam('user_id', 0);
        $model = $this->loadUserShowcaseModel($user_id);

        $this->render('values', ['model' => $model]);
    }


    /**
     * Fire Showcase filters
     * @throws CHttpException
     */
    public function actionFilters() {

        $user_id = Yii::app()->request->getParam('user_id', 0);
        $model = $this->loadUserShowcaseModel($user_id);

        $this->render('filters', ['model' => $model]);
    }


    /********************************* Protected *********************************/

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @param integer $user_id
     * @return ArUserTourShowcase the loaded model
     * @throws CHttpException
     */
    protected function loadUserShowcaseModel($user_id) {
        $model = ArUserTourShowcase::model()->findByAttributes(['user_id' => $user_id]);
        if( !$model ){
            throw new CHttpException(403, 'Витрина туров недоступна.');
        }

        return $model;
    }

}