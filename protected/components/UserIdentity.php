<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * @var int
     */
    private $_id;


	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {

        $username = strtolower($this->username);
        $user = ArUsers::model()->find('LOWER(email)=?', array($username));

        if($user === null) {

            $this->errorCode = self::ERROR_USERNAME_INVALID;

        } elseif(!$user->validatePassword($this->password)) {

            $this->errorCode = self::ERROR_PASSWORD_INVALID;

        } elseif($user->role == ArUsers::ROLE_GUEST){

            throw new CHttpException(403, 'Извините, но Ваш аккаунт еще не подтвержден.');

        } elseif($user->role != ArUsers::ROLE_MODERATOR && $user->role != ArUsers::ROLE_ADMIN && $user->role != ArUsers::ROLE_SUPERADMIN && Yii::app()->user->stateKeyPrefix == 'backend_'){

            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;

        } elseif ( $user->state != 0 ){

            throw new CHttpException(403, 'Ваш аккаунт не доступен.');

        } else {

            $this->_id = $user->id;
            $this->setState('lp_builder_hash', md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']));
//            $this->setState('my_email', $user->email);

            $this->errorCode = self::ERROR_NONE;
        }

        return $this->errorCode == self::ERROR_NONE;
    }

    /**
     * Returns user's identifier
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

}