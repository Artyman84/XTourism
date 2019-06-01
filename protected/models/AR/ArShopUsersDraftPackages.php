<?php

/**
 * This is the model class for table "{{shop_users_draft_packages}}".
 *
 * The followings are the available columns in table '{{shop_users_draft_packages}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $comment
 * @property integer $start
 * @property integer $expired
 * @property integer $price_uah
 * @property integer $price_rub
 */
class ArShopUsersDraftPackages extends CActiveRecord {

    /**
     * @var string
     */
    public $user_name;

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{shop_users_draft_packages}}';
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
            array('user_id, price_uah, price_rub', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('comment', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, name, comment, start, expired, price_uah, price_rub', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'products' => array( self::MANY_MANY, 'ArShopProducts', 'xt_shop_users_draft_products_to_packages(user_draft_package_id, product_id)' ),
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
			'comment' => 'Комментарий',
			'start' => 'Дата начала',
			'expired' => 'Действителен до',
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
        $criteria->with = ['user'];

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.user_id', $this->user_id);
		$criteria->compare('t.name', $this->name,true);
		$criteria->compare('t.comment', $this->comment,true);
		$criteria->compare('t.start', CDateTimeParser::parse($this->start,'dd.MM.yyyy'));
		$criteria->compare('t.expired', CDateTimeParser::parse($this->expired,'dd.MM.yyyy'));
		$criteria->compare('t.price_uah', $this->price_uah);
		$criteria->compare('t.price_rub', $this->price_rub);

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
     * "Before Save" event
     * @return bool
     */
    protected function beforeSave() {
        if (parent::beforeSave()) {

            if ($this->getIsNewRecord() ) {
                $packageExists = Yii::app()->db->createCommand()->select('id')->from($this->tableName())->where('user_id = :uid', [':uid' => $this->user_id])->queryScalar();
                return !$packageExists;
            }

            return true;

        } else {
            return false;
        }
    }

    /**
     * "Before delete" event
     * @return bool
     */
    protected function beforeDelete(){
        Yii::app()->db->createCommand()->delete('{{shop_users_draft_products_to_packages}}', 'user_draft_package_id = :pid', [':pid' => $this->id]);
        return parent::beforeDelete();
    }

    /**
     * Activates the draft package
     */
    public function activatePackage() {

        $activePackage = ArShopUsersPackages::model()->findByAttributes(['user_id' => $this->user_id]);
        if($activePackage) {
            $activePackage->delete();
        }

        $activePackage = new ArShopUsersPackages();

        $activePackage->attributes = [
            'user_id' => $this->user_id,
            'name' => $this->name,
            'start' => $this->start,
            'expired' => $this->expired,
        ];

        $db = Yii::app()->db;
        if( $activePackage->save(false) ){

            $products = CHtml::listData($this->products, 'id', 'name');
            foreach( $products as $product_id => $product ){
                $db->createCommand()->insert(
                    '{{shop_users_products_to_packages}}',
                    ['user_package_id' => $activePackage->id, 'product_id' => $product_id]
                );
            }

            // Insert settings for products of package
            foreach( $activePackage->products as $product ){

                switch ($product->type_id){

                    case ArShopProductsTypes::PDT_SEARCHER:
                        if( !$db->createCommand()->select('id')->from('{{user_searcher}}')->where('user_id = :uid', [':uid' => $this->user_id])->queryScalar() ) {
                            $searcher = new ArUserSearcher();
                            $searcher->attributes = ['user_id' => $this->user_id, 'type' => ArUserSearcher::SEARCHER_STANDARD];
                            $searcher->save();
                        }
                        break;

                    case ArShopProductsTypes::PDT_TOUR_SHOWCASE:
                        if( !$db->createCommand()->select('id')->from('{{user_tour_showcase}}')->where('user_id = :uid', [':uid' => $this->user_id])->queryScalar() ) {
                            $searcher = new ArUserTourShowcase();
                            $searcher->attributes = ['user_id' => $this->user_id, 'dc_dir_id' => $this->user->city_id, 'type' => ArUserTourShowcase::SHOWCASE_STANDARD];
                            $searcher->save();
                        }
                        break;

                    case ArShopProductsTypes::PDT_HOTELS_SHOWCASE:
                        // TODO: add in future code for creating record with hotel showcase
                        break;
                }

            }

            $invoice = new ArShopInvoices();
            $invoice->attributes = [
                'user_id' => $this->user_id,
                'created_at' => time(),
                'invoice' => CJSON::encode([
                    'user_id' => $this->user_id,
                    'user_name' => ArUsers::model()->findByPk($this->user_id)->userName(),
                    'package_name' => $this->name,
                    'comment' => $this->comment,
                    'start' => $this->start,
                    'expired' => $this->expired,
                    'price_uah' => $this->price_uah,
                    'price_rub' => $this->price_rub,
                    'products' => array_values($products)
                ])
            ];

            if( $invoice->save(false) ){

                $this->start = null;
                $this->expired = null;
                $this->comment = '';

                if($this->save(false)) {
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
	 * @return ArShopUsersDraftPackages the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}
