<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 08.04.2016
 * Time: 16:42
 */

class ShopProductsController extends BackendController {


    /**
     * Filters
     * @return array
     */
    public function filters(){
        return [
            'accessControl',
            'ajaxOnly + publish, delete',
            ['application.filters.XssFilter + edit, publish, delete', 'clean' => 'GET,POST,COOKIE'],
        ];
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


    public function actionIndex() {
        $model = new ArShopProducts('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['ArShopProducts'])) {
            $model->attributes = $_GET['ArShopProducts'];
        }

        $this->render('index', ['model' => $model]);

    }

    public function actionEdit($id=null) {
        $model = $this->loadProductModel($id);

        if( isset($_POST['ArShopProducts']) ){

            $model->attributes = $_POST['ArShopProducts'];
            if( $model->save() ){
                $url = Yii::app()->createUrl('ShopProducts/index') . '#blink=' . $model->id;
                $this->redirect($url);
            }

        }

        $this->render('edit', array('model' => $model));
    }

    public function actionPublish() {
        $ids = (array)Yii::app()->request->getParam('ids', array());
        $publish = (int)Yii::app()->request->getParam('publish');

        ArShopProducts::model()->updateByPk($ids, ['published' => $publish]);
    }


    public function actionDelete() {

        $ids = (array)Yii::app()->request->getParam('ids', []);
        $products = ArShopProducts::model()->findAllByAttributes(['id' => $ids/*, 'type_id' => -1*/]);

        $not_deleted_ids = [];
        $not_deleted_names = [];
        foreach ($products as $product){
            if( !$product->delete() ){
                $not_deleted_ids[] = $product->id;
                $not_deleted_names[] = $product->name;
            }
        }

        echo CJSON::encode(['ids' => $not_deleted_ids, 'names' => $not_deleted_names]);
    }


    /******************************************** Protected ********************************************/

    /**
     * Returns the data model based on the primary key given in the GET variable or empty model.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ArShopProducts
     * @throws CHttpException
     */
    public function loadProductModel($id=0) {
        if( $id ){
            $model = ArShopProducts::model()->findByPk($id);

            if($model === null) {
                throw new CHttpException(404, 'Запрашиваемая страница не существует.');
            }

        } else {
            $model = new ArShopProducts();
        }

        return $model;
    }


}