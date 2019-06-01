<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 15.04.2016
 * Time: 22:02
 */

class UsersDraftPackagesController extends BackendController {

    /**
     * Filters
     * @return array
     */
    public function filters(){
        return [
            'accessControl',
            'ajaxOnly + delete, packageData',
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

    /**
     *
     */
    public function actionIndex() {
        $model = new ArShopUsersDraftPackages('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['ArShopUsersDraftPackages'])) {
            $model->attributes = $_GET['ArShopUsersDraftPackages'];
        }

        $this->render('index', ['model' => $model]);
    }

    /**
     * "Edit" action
     * @param integer|null $id
     * @throws CHttpException
     */
    public function actionEdit($id=null) {
        $model = $this->loadProductModel($id);

        if( isset($_POST['ArShopUsersDraftPackages']) ) {

            $attributes = $_POST['ArShopUsersDraftPackages'];
            $products = $attributes['products'];
            unset($attributes['products']);

            $model->attributes = $attributes;

            if( !empty($products) && $model->start <= $model->expired && $model->save() ){

                $this->saveProducts($model->id, $products);

                if( Yii::app()->request->getParam('activate', null) && $model->expired != '' && $model->start != '' ) {
                    $model->activatePackage();
                }

                $url = Yii::app()->createUrl('UsersDraftPackages/index') . '#blink=' . $model->id;
                $this->redirect($url);
            }

        }

        $this->render('edit', array('model' => $model));
    }

    /**
     *
     */
    public function actionPackageData() {
        $package = Yii::app()->request->getParam('package', null);
        $data = null;

        if ( ($package = ArShopPackages::model()->with('products')->findByPk($package)) ) {
            $data['name'] = $package->name;
            $data['period'] = $package->period;
            $data['price_uah'] = $package->price_uah;
            $data['price_rub'] = $package->price_rub;
            $data['products'] = TUtil::keys($package->products);
        }

        echo CJSON::encode($data);
    }


    public function actionDelete() {

        $ids = (array)Yii::app()->request->getParam('ids', []);
        $packages = ArShopUsersDraftPackages::model()->findAllByAttributes(['id' => $ids]);

        foreach( $packages as $package ){
            $package->delete();
        }

    }


    /******************************************** Protected ********************************************/

    /**
     * Returns the data model based on the primary key given in the GET variable or empty model.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ArShopUsersDraftPackages
     * @throws CHttpException
     */
    protected function loadProductModel($id=0) {
        if( $id ){
            $model = ArShopUsersDraftPackages::model()->findByPk($id);

            if($model === null) {
                throw new CHttpException(404, 'Запрашиваемая страница не существует.');
            }

        } else {
            $model = new ArShopUsersDraftPackages();
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
        $db->createcommand()->delete('{{shop_users_draft_products_to_packages}}', 'user_draft_package_id = :pid', [':pid' => $package_id]);

        foreach( $products as $product ) {
            $db->createcommand()->insert('{{shop_users_draft_products_to_packages}}', ['user_draft_package_id' => $package_id, 'product_id' => $product]);
        }
    }

}