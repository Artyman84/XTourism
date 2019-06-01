<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 28.05.2016
 * Time: 14:47
 */


class saveFilter extends CAction {

    /**
     * User ID
     * @var integer
     */
    public $user_id;

    /**
     * Filter
     * @var string
     */
    public $filter;

    /**
     * Redirect
     * @var array
     */
    public $redirect;

    /**
     * Run Action
     */
    public function run(){

        if( ($model = ArUserTourShowcase::model()->findByAttributes(['user_id' => $this->user_id])) ) {

            $message = $this->controller->flashMessage('danger', 'При сохранении настроек возникли проблемы. Пожалуйтса, попробуйте еще раз.', true);

            if( in_array($this->filter, ['operators', 'countries']) ) {

                $settings = [$this->filter => isset($_POST[$this->filter]) ? $_POST[$this->filter] : []];

                if( $this->filter == 'countries' && isset($_POST[$this->filter]) && !in_array($model->settings['country'], $_POST[$this->filter])){
                    $settings['country'] = 0;
                }

                $model->settings = $settings;

                if($model->save()){
                    $message = $this->controller->flashMessage('success', '<strong>Фильтр</strong> был успешно сохранен.', true);
                }
            }

            Yii::app()->user->setFlash('tour_showcase_filters', $message);
            $this->controller->redirect($this->redirect);
        }

    }

}