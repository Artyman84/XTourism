<?php

/**
 * This is the model class for table "{{tour_showcase_tours_}}".
 *
 * The followings are the available columns in table '{{tour_showcase_tours_}}':
 * @property integer $id
 * @property integer $o_id
 * @property integer $h_dir_id
 * @property string $t_id
 * @property integer $m_dir_id
 * @property integer $dc_dir_id
 * @property integer $start_date
 * @property integer $end_date
 * @property integer $nights
 * @property integer $adults
 * @property integer $kids
 * @property string $room
 * @property integer $price_rur
 * @property integer $price
 * @property string $currency
 */
class ArTourShowcaseTours extends CActiveRecord {
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{' . \TSearch\ShowcaseTour::table() . '}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['o_id, h_dir_id, t_id, m_dir_id, dc_dir_id, start_date, end_date, nights, adults, kids, room, price_rur, price, currency', 'required'],
			['start_date, end_date, nights, adults, kids', 'numerical', 'integerOnly'=>true],
			['o_id, h_dir_id, m_dir_id, dc_dir_id, price_rur, price', 'length', 'max'=>11],
			['t_id, room', 'length', 'max'=>255],
			['currency', 'length', 'max'=>20],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, o_id, h_dir_id, t_id, m_dir_id, dc_dir_id, start_date, end_date, nights, adults, kids, room, price_rur, price, currency', 'safe', 'on'=>'search'),
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
            'operator' => [self::BELONGS_TO, 'ArOperators', 'o_id'],
            'city' => [self::BELONGS_TO, 'ArDirDepCities', 'dc_dir_id'],
            'meal' => [self::BELONGS_TO, 'ArDirMeals', 'm_dir_id'],
            'hotel' => [self::BELONGS_TO, 'ArDirHotels', 'h_dir_id'],
        ];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'o_id' => 'Оператор',
			'h_dir_id' => 'Отель',
			't_id' => 'ID Тура',
			'm_dir_id' => 'Питание',
			'dc_dir_id' => 'Город вылета',
			'start_date' => 'Дата начала',
			'end_date' => 'Дата конца',
			'nights' => 'Ночей',
			'adults' => 'Взрослых',
			'kids' => 'Детей',
			'room' => 'Гостиничный номер',
			'price_rur' => 'Цена в рублях',
			'price' => 'Цена',
			'currency' => 'Валюта',
		];
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
		$criteria->compare('o_id',$this->o_id);
		$criteria->compare('h_dir_id',$this->h_dir_id);
		$criteria->compare('t_id',$this->t_id);
		$criteria->compare('m_dir_id',$this->m_dir_id);
		$criteria->compare('dc_dir_id',$this->dc_dir_id);
		$criteria->compare('start_date',$this->start_date);
		$criteria->compare('end_date',$this->end_date);
		$criteria->compare('nights',$this->nights);
		$criteria->compare('adults',$this->adults);
		$criteria->compare('kids',$this->kids);
		$criteria->compare('room',$this->room);
		$criteria->compare('price_rur',$this->price_rur);
		$criteria->compare('price',$this->price);
		$criteria->compare('currency',$this->currency);

		return new CActiveDataProvider($this, [
			'criteria'=>$criteria,
		]);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArTourShowcaseTours the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}
