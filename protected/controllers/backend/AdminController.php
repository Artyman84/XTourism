<?php

class AdminController extends BackendController {

    /**
     * Default controller action
     * @var string
     */
    public $defaultAction = 'login';

    /**
     * Filters
     * @return array
     */
    public function filters(){
        return array(
            'accessControl + index, loadImages',
            ['application.filters.XssFilter + login'],
        );
    }

    /*
    Права доступа
*/
    public function accessRules() {
        return array(
            array(
                'allow',
                'roles' => array('moderator'),
            ),

            // запрещаем все остальное
            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }


    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $this->render('index');
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $this->layout = 'login';

        if( Yii::app()->user->isGuest ){
            $model = new LoginForm;

            // if it is ajax validation request
            if(isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if(isset($_POST['LoginForm'])) {
                $model->attributes = $_POST['LoginForm'];
                // validate user input and redirect to the previous page if valid

                if($model->validate() && $model->login()){
                    $this->redirect(Yii::app()->createUrl('Admin/index'));
                }
            }

            // display the login form
            $this->render('login', array('model' => $model));

        } else {
            $this->redirect(Yii::app()->createUrl('Admin/index'));
        }
    }


    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout(false);
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if( $error=Yii::app()->errorHandler->error ) {
            $this->render('common_views.errors.dev',  ['error' => $error]);
        }
    }
}