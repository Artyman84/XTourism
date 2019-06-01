<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 01.05.2015
 * Time: 8:50
 */

class WebUser extends CWebUser {

    /**
     * @var ArUsers
     */
    private $_model = null;

    /**
     * @var ArShopUsersPackages
     */
    private $package;


    public function getRole() {
        if($user = $this->getModel()){
            // в таблице User есть поле role
            return $user->role;
        }
    }

    public function getEmail(){
        if($user = $this->getModel()){
            // в таблице User есть поле role
            return $user->email;
        }
    }

    public function getName(){
        if($user = $this->getModel()){
            // в таблице User есть поле role
            return $user->name;
        }
    }

    public function getLastName(){
        if($user = $this->getModel()){
            // в таблице User есть поле role
            return $user->lastname;
        }
    }

    public function getUserState(){
        if($user = $this->getModel()){
            // в таблице User есть поле role
            return $user->state;
        }
    }


    /**
     * @return ArShopUsersPackages|null
     */
    public function getPackage() {
        if (!$this->isGuest && $this->package === null) {
            $this->package = ArShopUsersPackages::model()->with('products')->findByAttributes(['user_id' => $this->id]);
        }

        return $this->package;
    }

    /**
     * @return array|ArUsers|mixed|null|static
     */
    private function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = ArUsers::model()->findByPk($this->id, array('select' => '*'));
        }
        return $this->_model;
    }

}