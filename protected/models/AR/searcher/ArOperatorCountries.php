<?php

/**
 * This is the model class for table "{{operator_countries}}".
 *
 * The followings are the available columns in table '{{operator_countries}}':
 * @property string $id
 * @property integer $operator_id
 * @property string $element_id
 * @property string $directory_id
 * @property string $name
 * @property integer $position
 * @property integer $unread
 * @property integer $f_deleted
 */
class ArOperatorCountries extends CActiveRecord {
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{operator_countries}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('operator_id, element_id, directory_id, name, position, unread', 'required'),
			array('operator_id, position, unread, f_deleted', 'numerical', 'integerOnly'=>true),
			array('element_id', 'length', 'max'=>20),
			array('directory_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, operator_id, element_id, directory_id, name, position, unread, f_deleted', 'safe', 'on'=>'search'),
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
			'operator_id' => 'Operator',
			'element_id' => 'Element',
			'directory_id' => 'Directory',
			'name' => 'Name',
			'position' => 'Position',
			'unread' => 'Unread',
			'f_deleted' => 'F Deleted',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('operator_id',$this->operator_id);
		$criteria->compare('element_id',$this->element_id,true);
		$criteria->compare('directory_id',$this->directory_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('position',$this->position);
		$criteria->compare('unread',$this->unread);
		$criteria->compare('f_deleted',$this->f_deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArOperatorCountries the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}
