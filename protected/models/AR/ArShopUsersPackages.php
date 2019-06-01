<?php

/**
 * This is the model class for table "{{shop_users_packages}}".
 *
 * The followings are the available columns in table '{{shop_users_packages}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $start
 * @property integer $expired
 */
class ArShopUsersPackages extends CActiveRecord {

    /**
     * @var string
     */
    public $user_name;

    /**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{shop_users_packages}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, name', 'required'),
            array('start', 'date', 'format' => 'd.MM.yyyy', 'timestampAttribute' => 'start'),
            array('expired', 'date', 'format' => 'd.MM.yyyy', 'timestampAttribute' => 'expired'),
            array('user_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, name, start, expired', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'products' => array( self::MANY_MANY, 'ArShopProducts', 'xt_shop_users_products_to_packages(user_package_id, product_id)' ),
            'user' => array( self::BELONGS_TO, 'ArUsers', 'user_id' ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'user_id' => 'Турагент',
            'user_name' => 'Турагент',
			'name' => 'Название пакета',
			'start' => 'Дата начала',
			'expired' => 'Действителен до'
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
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
        $criteria->with = ['user'];

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.name',$this->name,true);
        $criteria->compare('t.start', CDateTimeParser::parse($this->start,'dd.MM.yyyy'));
        $criteria->compare('t.expired', CDateTimeParser::parse($this->expired,'dd.MM.yyyy'));

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort' => [
                'defaultOrder' => 'user.name ASC, user.lastname ASC',
                'attributes' => [
                    'user_name' => [
                        'asc' => 'user.name, user.lastname',
                        'desc' => 'user.name DESC, user.lastname DESC'
                    ],
                    '*'
                ]
            ],
            'pagination' => ['pageSize'=> 30]
        ));
	}

	/**
	 * "Before delete" event
	 * @return bool
	 */
	protected function beforeDelete(){
		Yii::app()->db->createCommand()->delete('{{shop_users_products_to_packages}}', 'user_package_id = :pid', [':pid' => $this->id]);
		return parent::beforeDelete();
	}

    /**
     * Checks if the package is valid
     * @return bool
     */
    public function isValid(){
        $today = strtotime('midnight');
        $start = strtotime('midnight', $this->start);
        $expired = strtotime('midnight', $this->expired);

        return !$this->getIsNewRecord() && $expired >= $today && $start <= $today;
    }

    /**
     * Checks if package has valid product
     * @param int $product_type
     * @return bool
     */
    public function hasProduct($product_type){

        if( ($products = $this->products) ) {
            foreach( $products as $product ) {
                if( $product->type_id == $product_type){
                    return true;
                }
            }
        }

        return false;
    }



	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArShopUsersPackages the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}
