<?php

/**
 * This is the model class for table "{{shop_products_types}}".
 *
 * The followings are the available columns in table '{{shop_products_types}}':
 * @property integer $id
 * @property string $name
 */
class ArShopProductsTypes extends CActiveRecord {

    /**
     * An External product
     */
    const PDT_EXTERNAL = -1;

    /**
     * Showcase of tours
     */
    const PDT_TOUR_SHOWCASE = 2;

    /**
     * Showcase of hotels
     */
    const PDT_HOTELS_SHOWCASE = 5;

    /**
     * Searcher
     */
    const PDT_SEARCHER = 3;

    /**
     * Landing page building
     */
    const PDT_LP_BUILDER = 4;


    /**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{shop_products_types}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Тип продукта',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => ['pageSize'=> 30]
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArShopProductsTypes the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}
