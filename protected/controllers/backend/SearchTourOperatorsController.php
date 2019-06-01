<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 19.08.14
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

class SearchTourOperatorsController extends BackendController {

    /**
     * Filters
     * @return array
     */
    public function filters(){
        return array(
            'accessControl',
            'ajaxOnly + deleteOperators',
            ['application.filters.XssFilter + editOperator'],
        );
    }

    /**
     * Права доступа
     */
    public function accessRules() {
        return array(
            array(
                'allow',
                'roles' => array('superadmin'),
            ),

            // запрещаем все остальное
            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * Action "Index"
     */
    public function actionIndex(){
        $model = new ArOperators('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['ArOperators'])) {
            $model->attributes = $_GET['ArOperators'];
        }

        $this->render('index', array('model' => $model, 'search' => $model->search()));
    }


    /**
     * Action "Enable/Disable operators"
     */
    public function actionEnableOperators(){
        $ids = (array)Yii::app()->request->getParam('ids', array());
        $enable = (int)Yii::app()->request->getParam('enable');

        ArOperators::model()->updateByPk($ids, array('blocked' => !$enable));
    }


    /**
     * Action "Edit Operator"
     * @param int|null $id
     */
    public function actionEditOperator($id=null){

        if( $id ){
            $model = ArOperators::model()->findByPk($id);
        } else {
            $model = new ArOperators();
        }


        if( isset($_POST['ArOperators']) ){

            $model->attributes = $_POST['ArOperators'];
            if( $model->save() ){
                $url = Yii::app()->createUrl('SearchTourOperators/index') . '#blink=' . $model->id;
                $this->redirect($url);
            }
        }

        $this->render('editOperator', array('model' => $model));
    }


    /**
     * Action "Delete operators"
     */
    public function actionDeleteOperators(){

        // Do not delete!!!
        return false;

        $ids = (array)Yii::app()->request->getParam('ids', array());

        $operators = ArOperators::model()->findAllByAttributes(array('id' => $ids));
        foreach( $operators as $operator ){
            $operator->delete();
        }

    }

}