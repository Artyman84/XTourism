<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 15.04.2016
 * Time: 22:02
 */

class UsersPackagesController extends BackendController {

    /**
     * Filters
     * @return array
     */
    public function filters(){
        return [
            'accessControl',
            //'ajaxOnly + delete',
            //['application.filters.XssFilter + edit, delete', 'clean' => 'GET,POST,COOKIE'],
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
     * Index
     */
    public function actionIndex() {
        $model = new ArShopUsersPackages('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['ArShopUsersPackages'])) {
            $model->attributes = $_GET['ArShopUsersPackages'];
        }

        $this->render('index', ['model' => $model]);
    }


}