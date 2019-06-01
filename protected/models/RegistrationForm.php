<?php

/**
 * RegistrationForm class.
 * RegistrationForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class RegistrationForm extends CFormModel {
    public $name;
    public $lastname;
    public $email;
    public $phone;
    public $password;
    public $password2;
    public $city_id;
    public $company;
    public $verifyCode;

    /**
     * Declares the validation rules.
     */
    public function rules()	{
        return array(
            // name, email, subject and body are required
            array('name, lastname, email, phone, password, password2', 'required'),
            // email has to be a valid email address
            array('email', 'email'),
            array('phone', 'numerical'),
            array('email', 'emailUnique'),
            array('password2', 'compare', 'compareAttribute' => 'password'),
            array('company, city_id', 'safe'),
            // verifyCode needs to be entered correctly
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper  case.
     */
    public function attributeLabels() {
        return array(
            'verifyCode' => 'Код',
            'name' => 'Имя',
            'lastname' => 'Фамилия',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'password' => 'Пароль',
            'password2' => 'Подтверждение пароля',
            'city_id' => 'Город',
            'company' => 'Компания',
        );
    }

    /**
     * Проверка на существование емейла
     * @param string $attribute
     * @return bool
     */
    public function emailUnique($attribute){
        $model = ArUsers::model()->find('email = :email', array(':email' => $this->$attribute));

        if( $model !== null ){
            $this->addError($attribute, 'Пользователь с таким емейлом уже существует!');
        }

        return false;
    }

}