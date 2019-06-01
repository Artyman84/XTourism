<?php

/**
 * This is the model class for table "{{hotel_complaints}}".
 *
 * The followings are the available columns in table '{{hotel_complaints}}':
 * @property integer $id
 * @property integer $dir_hotel_id
 * @property integer $name_not_valid
 * @property integer $category_not_valid
 * @property integer $photos_not_valid
 * @property string $comment
 * @property string $ip
 * @property integer $user_id
 * @property integer $time
 *
 */
class ArHotelComplaints extends CActiveRecord {
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{hotel_complaints}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['dir_hotel_id, ip, time', 'required'],
			['dir_hotel_id, name_not_valid, category_not_valid, photos_not_valid, time, user_id', 'numerical', 'integerOnly'=>true],
			['comment, ip', 'safe'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			['id, dir_hotel_id, name_not_valid, category_not_valid, photos_not_valid, comment, ip, user_id, time', 'safe', 'on'=>'search'],
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'dir_hotel_id' => 'Dir Hotel',
			'name_not_valid' => 'Name Not Valid',
			'category_not_valid' => 'Category Not Valid',
			'photos_not_valid' => 'Photos Not Valid',
			'comment' => 'Comment',
			'ip' => 'IP',
			'user_id' => 'User Id',
			'time' => 'Time',
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
		$criteria->compare('dir_hotel_id',$this->dir_hotel_id);
		$criteria->compare('name_not_valid',$this->name_not_valid);
		$criteria->compare('category_not_valid',$this->category_not_valid);
		$criteria->compare('photos_not_valid',$this->photos_not_valid);
		$criteria->compare('comment',$this->comment,true);
        $criteria->compare('ip',$this->ip);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('time',$this->time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArHotelComplaints the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}
