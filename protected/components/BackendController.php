<?php

class BackendController extends Controller {

    // лейаут
    public $layout = 'main';

    // меню
    public $menu = array();

    // крошки
    public $breadcrumbs = array();

    /**
     * Filters
     * @return array
     */
    public function filters() {
        return ['accessControl'];
    }

    /*
        Права доступа
    */
    public function accessRules() {
        return array(
            // даем доступ только админам
            array(
                'allow',
                'roles' => array('admin'),
            ),

            // всем остальным разрешаем посмотреть только на страницу авторизации
            array(
                'allow',
                'actions' => array('login'),
                'users' => array('?'),
            ),

            // запрещаем все остальное
            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }

}