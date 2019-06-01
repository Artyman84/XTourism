<?php

/**
 * This is the model class for table "{{shop_packages}}".
 *
 * The followings are the available columns in table '{{shop_packages}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $period
 * @property integer $price_uah
 * @property integer $price_rub
 */
class ArShopPackages extends CActiveRecord {

    const PERIOD_1_MONTH = 1;
    const PERIOD_3_MONTH = 3;
    const PERIOD_6_MONTH = 6;
    const PERIOD_12_MONTH = 12;

    /**
     * @var array
     */
    private static $periods = [
        self::PERIOD_1_MONTH => '1 Месяц',
        self::PERIOD_3_MONTH => '3 Месяца',
        self::PERIOD_6_MONTH => '6 Месяцев',
        self::PERIOD_12_MONTH => '12 Месяцев',
    ];

    /**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{shop_packages}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, period', 'required'),
			array('period, price_uah, price_rub', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
            array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, period, price_uah, price_rub', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
            'products' => array( self::MANY_MANY, 'ArShopProducts', 'xt_shop_products_to_packages(package_id, product_id)' )
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Название пакета',
			'description' => 'Описание',
			'period' => 'Период',
			'price_uah' => 'Цена в гривнах',
			'price_rub' => 'Цена в рублях',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('period',$this->period);
		$criteria->compare('price_uah',$this->price_uah);
		$criteria->compare('price_rub',$this->price_rub);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => ['pageSize'=> 30]
		));
	}

    /**
     * Before package delete
     * @return bool
     */
    protected function beforeDelete(){

        // delete products of removing package
        Yii::app()->db->createCommand()->delete('{{shop_products_to_packages}}', 'package_id = :pid', [':pid' => $this->id]);
        return parent::beforeDelete();
    }

    /**
     * Returns period's name
     * @param integer $period
     * @return string
     */
    public static function periodName($period){
        return isset(self::$periods[$period]) ? self::$periods[$period] : '';
    }

    /**
     * Returns period's name
     * @return array
     */
    public static function periods() {
        return self::$periods;
    }

    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArShopPackages the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}
