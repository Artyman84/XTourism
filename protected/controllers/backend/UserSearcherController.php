<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 13.02.2016
 * Time: 11:31
 */

class UserSearcherController extends BackendController {

    /**
     * Filters
     * @return array
     */
    public function filters(){
        return [
            'accessControl',
            ['application.filters.HasSearcherProduct + saveDesign, saveDefaultValues, saveFilter', 'user_id' => Yii::app()->request->getParam('user_id', 0)],
            ['application.filters.XssFilter + settings, saveDesign, saveDefaultValues, saveFilter'],
            'postOnly + saveDesign, SaveDefaultValues, saveFilter',
        ];
    }

    /**
     * Actions
     * @return array
     */
    public function actions(){
        $user_id = Yii::app()->request->getParam('user_id', 0);

        return [
            'saveDesign' => [
                'class' => 'actions.user_searcher.saveDesign.saveDesign',
                'user_id' => $user_id,
                'redirect' => ['UserSearcher/settings', 'user_id' => $user_id]
            ],
            'saveDefaultValues' => [
                'class' => 'actions.user_searcher.saveDefaultValues.saveDefaultValues',
                'user_id' => $user_id,
                'redirect' => ['UserSearcher/values', 'user_id' => $user_id]
            ],
            'saveFilter' => [
                'class' => 'actions.user_searcher.saveFilter.saveFilter',
                'user_id' => $user_id,
                'filter' => Yii::app()->request->getParam('filter', ''),
                'redirect' => ['UserSearcher/filters', 'user_id' => $user_id]
            ]
        ];
    }

    /**
     * Action "Index"
     */
    public function actionIndex() {
        $model = new ArUserSearcher('search');
        $model->unsetAttributes();

        if(isset($_GET['ArUserSearcher'])) {
            $model->setAttributes($_GET['ArUserSearcher']);
        }

        $this->render('index', ['model' => $model]);
    }

    /**
     * Searcher settings
     * @throws CHttpException
     */
    public function actionSettings() {
        $user_id = Yii::app()->request->getParam('user_id', 0);
        $model = $this->loadUserSearcherModel($user_id);

        $this->render('design', ['model' => $model, 'user_id' => $user_id, ]);
    }

    /**
     * Action "Tours"
     */
    public function actionValues() {
        $user_id = Yii::app()->request->getParam('user_id', 0);
        $model = $this->loadUserSearcherModel($user_id);

        $this->render('values', ['model' => $model, 'user_id' => $user_id]);
    }

    /**
     * Searcher filters
     * @throws CHttpException
     */
    public function actionFilters(){
        $user_id = Yii::app()->request->getParam('user_id', 0);
        $model = $this->loadUserSearcherModel($user_id);

        $this->render('filters', ['model' => $model, 'user_id' => $user_id]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @param integer $user_id
     * @return ArUserSearcher the loaded model
     * @throws CHttpException
     */
    protected function loadUserSearcherModel($user_id) {
        $model = ArUserSearcher::model()->findByAttributes(['user_id' => $user_id]);
        if( !$model ){
            throw new CHttpException(403, 'Поск туров недоступен. Пожалуйста, обратитесь к вашему администратору: ' . Yii::app()->params['adminEmail']);
        }

        return $model;
    }

}