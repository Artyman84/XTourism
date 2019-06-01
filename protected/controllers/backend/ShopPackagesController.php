<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 08.04.2016
 * Time: 16:42
 */

class ShopPackagesController extends BackendController {


    /**
     * Filters
     * @return array
     */
    public function filters(){
        return [
            'accessControl',
            'ajaxOnly + delete',
            ['application.filters.XssFilter + edit, delete', 'clean' => 'GET,POST,COOKIE'],
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
        $model = new ArShopPackages('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['ArShopPackages'])) {
            $model->attributes = $_GET['ArShopPackages'];
        }

        $this->render('index', ['model' => $model]);

    }

    public function actionEdit($id=null) {
        $model = $this->loadProductModel($id);

        if( isset($_POST['ArShopPackages']) ) {

            $attributes = $_POST['ArShopPackages'];
            $model->attributes = $attributes;

            if( !empty($attributes['products']) && $model->save() ){

                $this->saveProducts($model->id, $attributes['products']);

                $url = Yii::app()->createUrl('ShopPackages/index') . '#blink=' . $model->id;
                $this->redirect($url);
            }

        }

        $this->render('edit', array('model' => $model));
    }


    public function actionDelete() {

        $ids = (array)Yii::app()->request->getParam('ids', []);
        $packages = ArShopPackages::model()->findAllByAttributes(['id' => $ids]);

        foreach( $packages as $package ){
            $package->delete();
        }

    }


    /******************************************** Protected ********************************************/

    /**
     * Returns the data model based on the primary key given in the GET variable or empty model.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ArShopPackages
     * @throws CHttpException
     */
    protected function loadProductModel($id=0) {
        if( $id ){
            $model = ArShopPackages::model()->findByPk($id);

            if($model === null) {
                throw new CHttpException(404, 'Запрашиваемая страница не существует.');
            }

        } else {
            $model = new ArShopPackages();
        }

        return $model;
    }

    /**
     * Saves products for package
     * @param integer $package_id
     * @param integer $products
     */
    protected function saveProducts($package_id, $products){
        $db = Yii::app()->db;
        $db->createcommand()->delete('{{shop_products_to_packages}}', 'package_id = :pid', [':pid' => $package_id]);

        foreach( $products as $product ) {
            $db->createcommand()->insert('{{shop_products_to_packages}}', ['package_id' => $package_id, 'product_id' => $product]);
        }
    }


}