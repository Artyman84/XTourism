<?php

/**
 * This is the model class for table "{{clients_tours_requests}}".
 *
 * The followings are the available columns in table '{{clients_tours_requests}}':
 * @property string $id
 * @property integer $agent_id
 * @property integer $created_at
 * @property string $client_name
 * @property string $client_phone
 * @property string $client_email
 * @property string $client_comment
 * @property string $client_IP
 * @property integer $product_type
 * @property string $tour_id
 * @property integer $operator_id
 * @property integer $hotel_id
 * @property integer $dep_city_id
 * @property integer $meal_id
 * @property integer $start_date
 * @property integer $nights
 * @property string $room
 * @property integer $price
 * @property string $currency
 * @property integer $adults
 * @property integer $kids
 * @property integer $state
 */
class ArClientsToursRequests extends CActiveRecord {

	/**
	 * @var string
	 */
	public $agent_name;

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{clients_tours_requests}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			array('agent_id, created_at, client_name, client_phone, client_email, client_comment, client_IP, product_type, tour_id, operator_id, hotel_id, dep_city_id, meal_id, start_date, nights, room, price, currency, adults, kids, state', 'required'),
			array('agent_id, product_type, operator_id, hotel_id, dep_city_id, meal_id, start_date, nights, price, adults, kids, state', 'numerical', 'integerOnly'=>true),
			array('client_name, client_phone, client_email, client_IP, tour_id, room', 'length', 'max'=>255),
            array('created_at', 'date', 'format' => 'd.MM.yyyy', 'timestampAttribute' => 'created_at'),
			array('currency', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, agent_id, created_at, client_name, client_phone, client_email, client_comment, client_IP, product_type, tour_id, operator_id, hotel_id, dep_city_id, meal_id, start_date, nights, room, price, currency, adults, kids, state', 'safe', 'on'=>'search'),
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
			'agent' => [self::BELONGS_TO, 'ArUsers', 'agent_id']
        ];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => '№',
			'agent_id' => 'Турагент',
			'agent_name' => 'Турагент',
			'created_at' => 'Дата',
			'client_name' => 'Имя',
			'client_phone' => 'Телефон',
			'client_email' => 'Емейл',
			'client_comment' => 'Комментарий к заявке',
			'client_IP' => 'IP адрес',
			'product_type' => 'Продукт',
			'tour_id' => 'ID тура',
			'operator_id' => 'Оператор',
			'hotel_id' => 'ID отеля',
			'dep_city_id' => 'ID города вылета',
			'meal_id' => 'ID типа питания',
			'start_date' => 'Дата начала',
			'nights' => 'Ночей',
			'room' => 'Тип комнаты',
			'price' => 'Цена',
			'currency' => 'Валюта',
			'adults' => 'Взрослых',
			'kids' => 'Детей',
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
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = ['agent'];

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.agent_id',$this->agent_id);

		if( $this->created_at ) {
			$created_at = (int)CDateTimeParser::parse($this->created_at, 'dd.MM.yyyy');
			$criteria->addBetweenCondition('t.created_at', $created_at, $created_at + 24 * 3600 - 1);
		}

		$criteria->compare('t.client_name',$this->client_name,true);
		$criteria->compare('t.client_phone',$this->client_phone);
		$criteria->compare('t.client_email',$this->client_email,true);
		$criteria->compare('t.client_comment',$this->client_comment,true);
		$criteria->compare('t.client_IP',$this->client_IP,true);
		$criteria->compare('t.product_type',$this->product_type);
		$criteria->compare('t.tour_id',$this->tour_id,true);
		$criteria->compare('t.operator_id',$this->operator_id);
		$criteria->compare('t.hotel_id',$this->hotel_id);
		$criteria->compare('t.dep_city_id',$this->dep_city_id);
		$criteria->compare('t.meal_id',$this->meal_id);
		$criteria->compare('t.start_date',$this->start_date);
		$criteria->compare('t.nights',$this->nights);
		$criteria->compare('t.room',$this->room,true);
		$criteria->compare('t.price',$this->price);
		$criteria->compare('t.currency',$this->currency);
		$criteria->compare('t.adults',$this->adults);
		$criteria->compare('t.kids',$this->kids);
		$criteria->compare('t.state',$this->state);

		return new CActiveDataProvider($this, [
			'criteria'=>$criteria,
			'sort' => [
				'defaultOrder' => 't.state DESC, t.created_at DESC',
				'attributes' => [
					'agent_name' => [
						'asc' => 'agent.name, agent.lastname',
						'desc' => 'agent.name DESC, agent.lastname DESC'
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
	 * @return ArClientsToursRequests the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}
