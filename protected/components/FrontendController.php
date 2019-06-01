<?php

class FrontendController extends Controller {

    // лейаут
    public $layout = 'backend';

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


    /**
     * Access Rules for AccessControl filter
     * @return array
     */
    public function accessRules(){
        return array(
            array(
                'allow',
                'roles' => array('agent')
            ),

            array(
                'deny',
                'users' => array('*')
            )

        );
    }
}