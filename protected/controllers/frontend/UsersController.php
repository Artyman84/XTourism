<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 02.04.2015
 * Time: 10:31
 */

class UsersController extends FrontendController{

    /**
     * Action "Profile"
     */
    public function actionProfile(){

        $model = ArUsers::model()->findByPk(Yii::app()->user->id);

        if( isset($_POST['ArUsers']) ){

            $attributes = $_POST['ArUsers'];

            if( !empty( $attributes['password'] ) ){
                $attributes['password'] = CPasswordHelper::hashPassword($attributes['password']);
            }

            $model->attributes = $attributes;

            if( $model->save() ){

                $notifyByEmail = Yii::app()->request->getParam('notifyByEmail', 0);
                if($notifyByEmail){
                    TNotify::notifyUserAboutChanging($model, $_POST['ArUsers']['password']);
                }

                // Модераторы не могут видеть список пользователей
                Yii::app()->user->setFlash('agent_profile', parent::flashMessage('success', 'Ваш профайл был успешно изменен.', true));
                $this->refresh();
            }
        }

        $this->render('profile', array('model' => $model));
    }

}