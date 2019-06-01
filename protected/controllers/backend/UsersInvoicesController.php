<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 15.04.2016
 * Time: 22:02
 */

class UsersInvoicesController extends BackendController {

    /**
     * Filters
     * @return array
     */
    public function filters(){
        return [
            'accessControl',
        ];
    }

    /**
     * ѕрава доступа
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
     * Index
     */
    public function actionIndex() {
        $model = new ArShopInvoices('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['ArShopInvoices'])) {
            $model->attributes = $_GET['ArShopInvoices'];
        }

        $this->render('index', ['model' => $model]);
    }

    /**
     * Index
     * @param integer $id
     */
    public function actionHistory($id) {
        $model = new ArShopInvoices('search');
        $model->unsetAttributes();  // clear any default values

        $model->user_id = $id;
        if(isset($_GET['ArShopInvoices'])) {
            $model->attributes = $_GET['ArShopInvoices'];
        }

        $this->render('history', ['model' => $model]);
    }


    /**
     * @param integer $id
     */
    public function actionInvoice($id) {
        $this->render('invoice', ['invoice_id' => $id]);
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
            $model = ArShopInvoices::model()->findByPk($id);

            if($model === null) {
                throw new CHttpException(404, '«апрашиваема€ страница не существует.');
            }

        } else {
            $model = new ArShopInvoices();
        }

        return $model;
    }

}