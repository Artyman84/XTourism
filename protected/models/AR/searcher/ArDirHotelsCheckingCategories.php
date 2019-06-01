<?php

/**
 * This is the model class for table "{{directory_hotels_checking_categories}}".
 *
 * The followings are the available columns in table '{{directory_hotels_checking_categories}}':
 * @property integer $dir_hotel_id
 * @property integer $checked
 */
class ArDirHotelsCheckingCategories extends CActiveRecord {

    /**
     * Hotel name
     * @var string
     */
    public $hotel_name;

    /**
     * Hotel category name
     * @var string
     */
    private $category_name;

    /**
     * Hotel category id
     * @var string
     */
    public $category_id;


    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{directory_hotels_checking_categories}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('dir_hotel_id, checked, hotel_name, category_name, category_id', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'hotel' => array(self::BELONGS_TO, 'ArDirHotels', 'dir_hotel_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'dir_hotel_id' => 'ID',
            'checked' => 'Статус',
            'hotel_name' => 'Отель',
            'category_name' => 'Категория',
            'category_id' => 'Категория',
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

        $criteria->with = ['hotel' => ['with' => 'category']];
        $criteria->together= true;

        $criteria->compare('dir_hotel_id',$this->dir_hotel_id);
        $criteria->compare('checked',$this->checked);
        $criteria->compare('category.id',$this->category_id);
        $criteria->compare('LOWER(hotel.name)', mb_strtolower($this->hotel_name, 'utf8'), true);

        return new CActiveDataProvider($this, [
            'criteria'=>$criteria,
            'pagination' => [
                'pageSize' => 30
            ],
            'sort' => [
                'defaultOrder' => 'hotel.name ASC',
                'attributes' => [
                    'hotel_name' => [
                        'asc' => 'hotel.name',
                        'desc' => 'hotel.name DESC',
                    ],
                    'category_name' => [
                        'asc' => 'category.name',
                        'desc' => 'category.name DESC',
                    ],
                    '*',
                ],
            ]
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ArDirHotelsCheckingCategories
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
}