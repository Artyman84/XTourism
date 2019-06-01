<?php

/**
 * This is the model class for table "{{user_lpbuilder_domains}}".
 *
 * The followings are the available columns in table '{{user_lpbuilder_domains}}':
 * @property integer $user_id
 * @property string $domain_name
 * @property integer $is_purchased
 * @property integer $is_active
 */
class ArUserConstructDomains extends CActiveRecord {

    /**
     * @var string
     */
    public $user_name;

    /**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{user_construct_domains}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, domain_name, is_purchased, is_active', 'required'),
			array('user_id, is_purchased, is_active', 'numerical', 'integerOnly'=>true),
			array('domain_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, domain_name, is_purchased, is_active', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'user' => array(self::BELONGS_TO, 'ArUsers', 'user_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
            'user_id' => 'Турагент',
            'user_name' => 'Турагент',
            'domain_name' => 'Домен',
			'is_purchased' => 'Оплачен',
			'is_active' => 'Активен',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('domain_name',$this->domain_name,true);
		$criteria->compare('is_purchased',$this->is_purchased);
		$criteria->compare('is_active',$this->is_active);

		return new CActiveDataProvider($this, [
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
        ]);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArUserConstructDomains the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}
