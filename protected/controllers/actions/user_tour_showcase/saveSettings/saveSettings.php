<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 28.05.2016
 * Time: 14:47
 */


class saveSettings extends CAction {

    /**
     * User ID
     * @var integer
     */
    public $user_id;

    /**
     * Redirect
     * @var array
     */
    public $redirect;


    /**
     * Run Action
     */
    public function run(){

        $user_showcase = ArUserTourShowcase::model()->findByAttributes(['user_id' => $this->user_id]);
        $settings = $user_showcase->showcaseSettings();
        $modelName = get_class($settings);

        $message = $this->controller->flashMessage('danger', 'При сохранении настроек возникли проблемы. Пожалуйтса, попробуйте еще раз.', true);
        if( isset($_POST[$modelName]) ){
            $attributes = $_POST[$modelName];

            if( $settings->validate($attributes) ){

                $user_showcase->settings = $attributes;

                if( $user_showcase->save() ) {
                    $message = $this->controller->flashMessage('success', '<strong>Настройки витрины</strong> были успешно изменены.', true);
                }
            }
        }

        Yii::app()->user->setFlash('showcase_settings', $message);

        $this->controller->redirect($this->redirect);
    }

}