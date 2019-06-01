<?php

/**
 * This is the model class for table "{{hotel_services}}".
 *
 * The followings are the available columns in table '{{hotel_services}}':
 * @property integer  $id
 * @property integer  $dir_hotel_id
 * @property string   $name
 * @property string   $value
 */
class ArHotelServices extends CActiveRecord {

    /**
     * Icons
     * @var array
     */
    private static $icons = [
        'на свежем воздухе' => 'tree',
        'спорт и отдых' => 'bicycle',
        'интернет' => 'wifi',
        'парковка' => 'car',
        'общие' => 'info-circle',
        'магазины' => 'shopping-cart',
        'разное' => 'asterisk',
        'питание и напитки' => 'glass',
        'кухня' => 'cutlery',
        'медиа и технологии' => 'tv',
        'медиа' => 'tv',
        'удобства в номере' => 'smile-o',
        'транспорт' => 'bus',
        'спальня' => 'bed',
        'ванная комната' => 'bath',
        'гостиная зона' => 'group',
        'доступность' => 'clock-o',
        'зоны общественного пользования' => 'group',
        'развлечения и семейные услуги' => 'film',
        'бассейн и оздоровительные услуги' => 'heartbeat',
        'характеристики здания' => 'building',
        'вне помещения и вид' => 'street-view',
        'стойка регистрации' => 'pencil-square-o',
        'услуги уборки' => 'paint-brush',
        'услуги бизнес-центра' => 'handshake-o',
        'персонал говорит' => 'language',
        'домашние животные' => 'paw',
        'услуги и дополнения' => 'plus-circle',
        'лыжи' => 'snowflake-o',
        'сервисы' => 'cogs',
    ];


    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{hotel_services}}';
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
     * Returns hotel's services list of properties
     * @param array $services
     * @return array
     */
    public static function propertiesList($services){
        $ret = [];
        foreach($services as $service){
            $ret[mb_strtolower($service->name, 'utf8')] = $service->value;
        }

        return $ret;
    }

    /**
     * Saves hotel's services
     * @param integer $h_id
     * @param array $data
     */
    public static function saveServices($h_id, $data){
        $db = Yii::app()->db;
        $db->createCommand()->delete('{{hotel_services}}', 'dir_hotel_id = :h_id', [':h_id' => $h_id]);

        $properties = array_keys(self::icons());
        foreach( $data as $i => $value ) {
            if( isset($properties[$i]) && trim($value) ) {
                $db->createCommand()->insert('{{hotel_services}}', ['dir_hotel_id' => $h_id, 'name' => TUtil::mb_ucfirst($properties[$i]), 'value' => $value]);
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