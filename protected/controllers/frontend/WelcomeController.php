<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 02.04.2015
 * Time: 10:31
 */

class WelcomeController extends FrontendController{

    /**
     * Default controller action
     * @var string
     */
    public $defaultAction = 'login';

    /*
     * Фильтры
     */
    public function filters() {
        return [
            ['application.filters.XssFilter + index, support'],
            'accessControl + index, support'
            //'ajaxOnly + isAuthorized'
        ];
    }

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),

            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
//            'page'=>array(
//                'class'=>'CViewAction',
//            ),
        );
    }

    /**
     * Action "Index"
     */
    public function actionIndex(){

//        $operator = \TSearch\TOperator::newOperator(1);
//        TUtil::LogPre($operator, true);

//        // collect user input data
//        if(isset($_POST['LoginForm'])) {
//
//            $model=new LoginForm;
//            $model->attributes=$_POST['LoginForm'];
//
//            // validate user input and redirect to the previous page if valid
//            if($model->validate() && $model->login()) {
//                Yii::app()->user->setFlash('registration', parent::flashMessage('success', 'Вы успешно вошли!'));
//                $this->redirect(Yii::app()->user->returnUrl);
//            }
//
//            $msg = '';
//            if( $model->hasErrors('email') ){
//                $msg = $model->getError('email');
//            }
//
//            if( $model->hasErrors('password') ){
//                $msg .= ($msg ? '<br/>' : '') . $model->getError('password');
//            }
//
//            Yii::app()->user->setFlash('registration', parent::flashMessage('danger', $msg));
//        }


        $this->render('index');
    }

    public function actionLogin(){
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
                    $this->redirect(Yii::app()->createUrl('Welcome/index'));
                }
            }

            // display the login form
            $this->render('login_pa', ['model' => $model]);

        } else {
            $this->redirect(Yii::app()->createUrl('Welcome/index'));
        }
    }

    /**
     * Action "Logout"
     */
    public function actionLogout(){
        Yii::app()->user->logout(false);
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * Not Authorized in LP Builder
     */
    public function actionLPNotAuthorized(){
        Yii::app()->user->setFlash('registration', parent::flashMessage('danger', 'Для работы с конструктором необходима авторизация.'));
        $this->render('index');
    }

    /**
     * Action "Support"
     */
    public function actionSupport(){
        $model=new SupportForm;

        if(isset($_POST['SupportForm'])) {
            $model->attributes=$_POST['SupportForm'];

            if( !Yii::app()->user->isGuest ){
                $model->setAttributes(array(
                    'name' => Yii::app()->user->name,
                    'email' => Yii::app()->user->email
                ));
            }

            if($model->validate()) {
                TNotify::notifyByMail([Yii::app()->params['adminEmail'], Yii::app()->params['shopEmail']], $model->subject, $model->body, $model->email, $model->name);
                Yii::app()->user->setFlash('contact', parent::flashMessage('success', '<strong>Спасибо, что написали нам в техподдержку! <br>Наши специалисты ответят Вам на email в ближайшее время.</strong>', false));
                $this->refresh();
            }
        }

        $this->render('support', array('model'=>$model));
    }

    /**
     * Action "Promotion"
     */
    public function actionPromotion(){
        $this->render('promotion');
    }

//    /**
//     * "Registration"
//     */
//    public function actionRegistration(){
//
//        if( !Yii::app()->user->isGuest ){
//            throw new CHttpException(500, 'Вы уже зарегистрированны!');
//        }
//
//        $model = new RegistrationForm;
//        $this->performAjaxValidation($model);
//
//        if( isset($_POST['RegistrationForm']) ){
//
//            $model->attributes = $_POST['RegistrationForm'];
//
//            if($model->validate()) {
//
//                $user = new ArUsers;
//                $user->attributes = array(
//                    'name' => $model->attributes['name'],
//                    'lastname' => $model->attributes['lastname'],
//                    'email' => $model->attributes['email'],
//                    'phone' => $model->attributes['phone'],
//                    'company' => $model->attributes['company'],
//                    'city_id' => $model->attributes['city_id'],
//                    'password' => CPasswordHelper::hashPassword($model->attributes['password']),
//                    'role' => ArUsers::ROLE_GUEST,
//                    'state' => 2
//                );
//
//                if( $user->save() ){
//                    Yii::app()->user->setFlash('registration', parent::flashMessage('success', 'Поздравляем, Вы успешно зарегистрировались! На Ваш емейл было выслано письмо с паролем, который вы указали при регистрации.<br/>Пожалуйста, ожидайте, пока наш менеджер подтвердит ваши данные. Спасибо)', false));
//                    TNotify::notifyAgentAboutRegistration($user, $model->attributes['password']);
//                    $this->refresh();
//                }
//            }
//        }
//
//        $this->render('registration', array('model' => $model));
//    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if($error=Yii::app()->errorHandler->error) {
            if( !YII_DEBUG ) {
                $content_error = $this->renderPartial('common_views.errors.prod', null, true);
                $this->decorator('alert', $content_error, ['class' => 'danger']);
            } else {
                $this->layout = 'system_error';
                $this->render('common_views.errors.dev',  ['error' => $error]);
            }
        }
    }

    /******************* Protected *******************/

    /**
     * Performs the AJAX validation.
     * @param Authassignment $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if(isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}