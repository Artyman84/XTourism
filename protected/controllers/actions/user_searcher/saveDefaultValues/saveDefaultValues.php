<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 28.05.2016
 * Time: 14:47
 */


class saveDefaultValues extends CAction {

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
            $message = $this->controller->flashMessage('danger', 'При сохранении настроек возникли проблемы. Пожалуйтса, попробуйте еще раз.', true);

            if( $_POST ){

                $attributes = $_POST;

                if( !isset($attributes['hotelCategoryMore']) ){
                    $attributes['hotelCategoryMore'] = 0;
                }

                if( !isset($attributes['mealsMore']) ){
                    $attributes['mealsMore'] = 0;
                }

                if( !isset($attributes['child1']) ){
                    $attributes['child1'] = 1;
                }

                if( !isset($attributes['child2']) ){
                    $attributes['child2'] = 1;
                }

                if( !isset($attributes['child3']) ){
                    $attributes['child3'] = 1;
                }

                if( $settings->validate($attributes) ){

                    $model->settings = $attributes;

                    if($model->save()){
                        $message = $this->controller->flashMessage('success', '<strong>Значения по умолчанию</strong> были успешно изменены.', true);
                    }
                }
            }

            Yii::app()->user->setFlash('searcher_default_values', $message);
            $this->controller->redirect($this->redirect);
        }

    }

}