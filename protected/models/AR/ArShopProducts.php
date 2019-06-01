<?php

/**
 * This is the model class for table "{{shop_products}}".
 *
 * The followings are the available columns in table '{{shop_products}}':
 * @property string $id
 * @property string $category_id
 * @property integer $type_id
 * @property integer $item_id
 * @property string $name
 * @property string $description
 * @property integer $price_uah
 * @property integer $price_rub
 * @property integer $published
 */
class ArShopProducts extends CActiveRecord {

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{shop_products}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id, type_id, name', 'required'),
			array('type_id, item_id, price_uah, price_rub, published', 'numerical', 'integerOnly'=>true),
			array('category_id', 'length', 'max'=>11),
			array('name', 'length', 'max'=>255),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, category_id, type_id, item_id, name, description, price_uah, price_rub, published', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'type' => array(self::BELONGS_TO, 'ArShopProductsTypes', 'type_id'),
            'category' => array(self::BELONGS_TO, 'ArShopCategories', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'category_id' => 'Категория',
			'category' => 'Категория',
			'type_id' => 'Тип продукта',
			'type' => 'Тип продукта',
			'item_id' => 'Продукт',
			'name' => 'Название продукта',
			'description' => 'Описание',
			'price_uah' => 'Цена в гривнах',
			'price_rub' => 'Цена в рублях',
            'published' => 'Опубликован'
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

        $criteria->with = ['category', 'type'];

		$criteria->compare('id',$this->id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price_uah',$this->price_uah);
		$criteria->compare('price_rub',$this->price_rub);
		$criteria->compare('published',$this->published);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => 't.id',
                'attributes' => array(
                    'type' => [
                        'asc' => 'type.name',
                        'desc' => 'type.name DESC',
                    ],
                    'category' => [
                        'asc' => 'category.name',
                        'desc' => 'category.name DESC',
                    ],
                    '*'
                )
            ),
            'pagination' => ['pageSize'=> 30]
        ));
    }


    /**
     * Deletes all user's data
     * @return bool
     */
    protected function beforeDelete(){

        // delete products
        $db = Yii::app()->db;
        $native = $db->createCommand()->select('COUNT(*) AS c')->from('{{shop_products_to_packages}}')->where('product_id = :pid', [':pid' => $this->id])->queryScalar();
        if( $native ) return false;

        $draft = $db->createCommand()->select('COUNT(*) AS c')->from('{{shop_users_draft_products_to_packages}}')->where('product_id = :pid', [':pid' => $this->id])->queryScalar();
        if( $draft ) return false;

        $active = $db->createCommand()->select('COUNT(*) AS c')->from('{{shop_users_products_to_packages}}')->where('product_id = :pid', [':pid' => $this->id])->queryScalar();
        if( $active ) return false;

        return parent::beforeDelete();
    }


    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArShopProducts the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}
