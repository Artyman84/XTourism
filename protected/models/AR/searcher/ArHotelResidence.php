<?php

/**
 * This is the model class for table "{{hotel_residence}}".
 *
 * The followings are the available columns in table '{{hotel_residence}}':
 * @property integer  $id
 * @property integer  $dir_hotel_id
 * @property string   $name
 * @property string   $value
 */
class ArHotelResidence extends CActiveRecord {

    /**
     * Icons
     * @var array
     */
    private static $icons = [
        'группы' => 'group',
        'заезд' => 'sign-in',
        'отъезд' => 'sign-out',
        'размещение детей и предоставление дополнительных кроватей' => 'bed',
        'домашние животные' => 'paw'
    ];

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{hotel_residence}}';
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
            array('id, dir_hotel_id, name, value', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'hotels' => array( self::BELONGS_TO, 'ArDirHotels', 'dir_hotel_id' )
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'dir_hotel_id' => 'ID отеля',
            'name' => 'Название',
            'value' => 'Значение',
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
        $criteria->compare('dir_hotel_id',$this->dir_hotel_id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('value',$this->value,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns icons for properties
     * @return array
     */
    public static function icons(){
       return self::$icons;
    }

    /**
     * @param array $residences
     * @return array
     */
    public static function propertiesList($residences){
        $ret = [];
        foreach($residences as $residence){
            $ret[mb_strtolower($residence->name, 'utf8')] = $residence->value;
        }

        return $ret;
    }

    /**
     * Saves hotel's residences
     * @param integer $h_id
     * @param array $data
     */
    public static function saveResidences($h_id, $data){
        $db = Yii::app()->db;
        $db->createCommand()->delete('{{hotel_residence}}', 'dir_hotel_id = :h_id', [':h_id' => $h_id]);

        $properties = array_keys(self::icons());
        foreach( $data as $i => $value ){
            if( isset($properties[$i]) && trim($value) ){
                $db->createCommand()->insert('{{hotel_residence}}', ['dir_hotel_id' => $h_id, 'name' => TUtil::mb_ucfirst($properties[$i]), 'value' => $value]);
            }
        }
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