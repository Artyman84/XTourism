<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 28.05.2016
 * Time: 14:47
 */


class saveDesign extends CAction {

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

        if( ($model = ArUserSearcher::model()->findByAttributes(['user_id' => $this->user_id])) ) {

            $settings = $model->searcherSettings();
            $modelName = get_class($settings);

            $message = $this->controller->flashMessage('danger', 'При сохранении настроек возникли проблемы. Пожалуйтса, попробуйте еще раз.', true);
            if( isset($_POST[$modelName]) ){
                $attributes = $_POST[$modelName];

                if( $settings->validate($attributes) ){

                    $model->settings = $attributes;

                    if($model->save()) {
                        $message = $this->controller->flashMessage('success', '<strong>Настройки поисковика</strong> были успешно изменены.', true);
                    }
                }
            }

            Yii::app()->user->setFlash('searcher_design', $message);
            $this->controller->redirect($this->redirect);
        }

    }

}