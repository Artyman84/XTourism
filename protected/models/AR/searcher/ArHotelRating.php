<?php

/**
 * This is the model class for table "{{hotel_ratings}}".
 *
 * The followings are the available columns in table '{{hotel_ratings}}':
 * @property integer  $dir_hotel_id
 * @property float   $rating
 * @property integer   $voices
 * @property string   $scores
 */
class ArHotelRating extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{hotel_ratings}}';
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
            array('dir_hotel_id, rating, voices, scores', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'hotels' => array( self::HAS_ONE, 'ArDirHotels', 'id' )
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'dir_hotel_id' => 'ID отеля',
            'rating' => 'Рейтинг',
            'voices' => 'Количество голосов',
            'scores' => 'Пункты',
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

        $criteria->compare('dir_hotel_id',$this->dir_hotel_id);
        $criteria->compare('rating',$this->rating,true);
        $criteria->compare('voices',$this->voices,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
}