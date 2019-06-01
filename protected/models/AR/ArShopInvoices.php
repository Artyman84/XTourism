<?php

/**
 * This is the model class for table "{{shop_invoices}}".
 *
 * The followings are the available columns in table '{{shop_invoices}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $created_at
 * @property string $invoice
 */
class ArShopInvoices extends CActiveRecord {

	/**
	 * @var string
	 */
	public $user_name;

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{shop_invoices}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, created_at, invoice', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
            array('created_at', 'date', 'format' => 'd.MM.yyyy', 'timestampAttribute' => 'start'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, created_at, invoice', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
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
			'created_at' => 'Создан',
			'invoice' => 'Инвойс',
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
	 * @param bool $active
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search($active) {

		$criteria=new CDbCriteria;
		$criteria->with = ['user'];

		if( $active ) {
			$ids = Yii::app()->db->createCommand()
				->select('MAX(id)')
				->from('{{shop_invoices}}')
				->group('user_id')
				->queryColumn();

			$criteria->addInCondition('t.id', $ids);
		}

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.user_id',$this->user_id);
        $criteria->compare('t.created_at', CDateTimeParser::parse($this->created_at,'dd.MM.yyyy'));
		$criteria->compare('t.invoice',$this->invoice,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => [
				'defaultOrder' => $active ? 'user.name ASC, user.lastname ASC' : 't.created_at DESC',
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
	 * Returns active invoice id
	 * @param integer $user_id
	 * @return integer|null
	 */
	public static function activeInvoiceId($user_id){
		return Yii::app()->db->createCommand()
			->select('MAX(id)')
			->from('{{shop_invoices}}')
			->where('user_id = :uid', [':uid' =>$user_id])
			->queryScalar();
	}

	/**
	 * Returns active invoice id
	 * @param integer $user_id
	 * @return integer|ArShopInvoices
	 */
	public static function activeInvoice($user_id){
        $invoice_id = self::activeInvoiceId($user_id);
		return ArShopInvoices::model()->findByPk($invoice_id);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArShopInvoices the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}
