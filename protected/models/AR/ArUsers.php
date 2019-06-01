<?php

/**
 * This is the model class for table "{{users}}".
 *
 * The followings are the available columns in table '{{users}}':
 * @property string $id
 * @property string $name
 * @property string $lastname
 * @property string $password
 * @property string $email
 * @property integer $phone
 * @property string $company
 * @property integer $city_id
 * @property integer $city_name
 * @property string $role
 * @property integer $state
 */
class ArUsers extends CActiveRecord {

    /**
     *
     */
    const ROLE_SUPERADMIN = 'superadmin';

    /**
     *
     */
    const ROLE_ADMIN = 'admin';

    /**
     *
     */
    const ROLE_MODERATOR = 'moderator';

    /**
     *
     */
    const ROLE_AGENT = 'agent';

    /**
     *
     */
    const ROLE_GUEST = 'guest';

    /**
     * @var array
     */
    private static $roleNames = array(
        self::ROLE_SUPERADMIN => 'Суперадмин',
        self::ROLE_ADMIN => 'Админ',
        self::ROLE_MODERATOR => 'Модератор',
        self::ROLE_AGENT => 'Турагент',
        self::ROLE_GUEST => 'Гость'
    );

    /**
     * @var
     */
    private $city_name;

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{users}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('city_id, name, lastname, password, email, role, state', 'required'),
			array('state, phone', 'numerical', 'integerOnly' => true),
            array('city_id', 'length', 'max'=>11),
			array('name, lastname, password, email, company, role', 'length', 'max' => 255),
			array('email', 'unique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, lastname, password, email, phone, city_name, company, city_id, role, state', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
            'user_tour_showcase' => array(self::HAS_ONE, 'ArUserTourShowcase', 'user_id'),
            'user_searcher' => array(self::HAS_ONE, 'ArUserSearcher', 'user_id'),
            'package' => [self::HAS_ONE, 'ArShopUsersPackages', 'user_id'],
		];
	}

    public function scopes(){
        return array(
            'superadmin' => array(
                'condition' => 't.role="' . self::ROLE_SUPERADMIN . '"'
            ),

            'admin' => array(
                'condition' => 't.role="' . self::ROLE_ADMIN . '"'
            ),

            'agent' => array(
                'condition' => 't.role="' . self::ROLE_AGENT . '"'
            ),

            'withoutPackages' => array(
                'condition' => 't.role="' . self::ROLE_AGENT . '" AND t.id NOT IN(SELECT `pd`.`user_id` FROM {{shop_users_draft_packages}} AS `pd`)',
            ),

            'active' => array(
                'condition' => 't.state=0'
            ),

            'inactive' => array(
                'condition' => 't.state=1'
            ),

            'unidentified' => array(
                'condition' => 't.state=2'
            ),

            'notSuperAdmins' => array(
                'condition' => 'role NOT IN("' . self::ROLE_SUPERADMIN . '")'
            ),

            'notAdmins' => array(
                'condition' => 'role NOT IN("' . self::ROLE_ADMIN . '", "' . self::ROLE_SUPERADMIN . '")'
            ),

            'notGuest' => array(
                'condition' => 'role != "' . self::ROLE_GUEST . '"'
            )
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Имя',
			'lastname' => 'Фамилия',
			'password' => 'Пароль',
			'email' => 'Емейл',
			'phone' => 'Телефон',
			'company' => 'Компания',
			'city_id' => 'Город',
			'role' => 'Должность',
			'state' => 'Состояние',
		);
	}


	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
     * @param string|array $scope
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search($scope='') {
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

//        $criteria->with = array('city');
        $criteria->together= true;

        $criteria->compare('t.id',$this->id);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.lastname',$this->lastname,true);
		$criteria->compare('t.password',$this->password);
		$criteria->compare('t.email',$this->email);
		$criteria->compare('t.phone',$this->phone);
		$criteria->compare('t.company',$this->company,true);
		$criteria->compare('t.city_id',$this->city_id);
		$criteria->compare('t.role',$this->role);
		$criteria->compare('t.state',$this->state);

        if($scope){
            $criteria->scopes = $scope;
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => 't.role',
                'attributes' => array(
//                    'city_name' => array(
//                        'asc' => 'city.name',
//                        'desc' => 'city.name DESC'
//                    ),
                    '*'
                )
            )
		));
	}

    /**
     * Deletes all user's data
     * @return bool
     */
    protected function beforeDelete(){

        // Удаляем все витрины юзера
        $userShowcases = ArUserTourShowcase::model()->findAllByAttributes(['user_id' => $this->id]);
        foreach($userShowcases as $userShowcase) {
            $userShowcase->delete();
        }

        // Удаляем поисковики юзера
        $userSearchers = ArUserSearcher::model()->findAllByAttributes(['user_id' => $this->id]);
        foreach($userSearchers as $userSearcher) {
            $userSearcher->delete();
        }

        // Удаляем домены для конструктров
        $userConstructDomains = ArUserConstructDomains::model()->findAllByAttributes(['user_id' => $this->id]);
        foreach($userConstructDomains as $userConstructDomain) {
            $userConstructDomain->delete();
        }

        // Удаляем заявки юзера
        $userToursRequests = ArClientsToursRequests::model()->findAllByAttributes(['agent_id' => $this->id]);
        foreach($userToursRequests as $userToursRequest) {
            $userToursRequest->delete();
        }

        // Удаляем инвойсы юзера
        $userInvoices = ArShopInvoices::model()->findAllByAttributes(['user_id' => $this->id]);
        foreach($userInvoices as $userInvoice) {
            $userInvoice->delete();
        }

        // Удаляем активные пакеты юзера
        $userPackages = ArShopUsersPackages::model()->findAllByAttributes(['user_id' => $this->id]);
        foreach($userPackages as $userPackage) {
            $userPackage->delete();
        }

        // Удаляем черновые пакеты юзера
        $userDraftPackages = ArShopUsersDraftPackages::model()->findAllByAttributes(['user_id' => $this->id]);
        foreach($userDraftPackages as $userDraftPackage) {
            $userDraftPackage->delete();
        }

        return parent::beforeDelete();
    }

    /**
     * Returns user's full name
     * @return string
     */
    public function userName(){
        $name = null;
        if( !$this->getIsNewRecord() ){
            $name = $this->name . ' ' . $this->lastname;
        }

        return $name;
    }

    /**
     * Returns simple list of users
     * @param array $users
     * @return array
     */
    public static function simpleUsersList($users) {
        $list = [];

        foreach( $users as $user ) {
            $list[$user->id] = $user->userName();
        }

        return $list;
    }

    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArUsers the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    /**
     * Returns user's role name
     * @param string $role
     * @param bool $ucFirst
     * @return string
     */
    public static function roleName($role, $ucFirst=false){
        $roleName = isset(ArUsers::$roleNames[$role]) ? ArUsers::$roleNames[$role] : '';
        return $ucFirst ? $roleName : mb_strtolower($roleName, 'utf8');
    }



    /***************************  Passwords manipulations  ***************************/


    public function validatePassword($password) {
        return CPasswordHelper::verifyPassword($password, $this->password);
    }
}
