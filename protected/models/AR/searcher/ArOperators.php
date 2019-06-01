<?php

/**
 * This is the model class for table "{{operators}}".
 *
 * The followings are the available columns in table '{{operators}}':
 * @property integer $id
 * @property string  $name
 * @property string  $class
 * @property string  $url
 * @property integer $position
 * @property string  $countries_hash
 * @property string  $dep_cities_hash
 * @property string  $resorts_hash
 * @property string  $hotels_hash
 * @property int  $blocked
 */
class ArOperators extends CActiveRecord {

//    /**
//     * @var string
//     */
//    public $countries_count;
//
//    /**
//     * @var string
//     */
//    public $resorts_count;


    private static $freeElements;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{operators}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, class', 'required'),
            array('name, class, url, countries_hash, dep_cities_hash, resorts_hash, hotels_hash', 'length', 'max'=>255),
            array('blocked', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, class, url, position, countries_hash, dep_cities_hash, resorts_hash, hotels_hash, blocked', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'countries' => array(self::HAS_MANY, 'ArOperatorCountries', 'operator_id'),
            'dep_cities' => array(self::HAS_MANY, 'ArOperatorDepCities', 'operator_id'),
            'hotel_categories' => array(self::HAS_MANY, 'ArOperatorHotelCategories', 'operator_id'),
            'hotels' => array(self::HAS_MANY, 'ArOperatorHotels', 'operator_id'),
            'hotel_statuses' => array(self::HAS_MANY, 'ArOperatorHotelStatuses', 'operator_id'),
            'meals' => array(self::HAS_MANY, 'ArOperatorMeals', 'operator_id'),
            'resorts' => array(self::HAS_MANY, 'ArOperatorResorts', 'operator_id'),
            'ticket_statuses' => array(self::HAS_MANY, 'ArOperatorTicketStatuses', 'operator_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
//            'countries_count' => 'Cnt',
//            'resorts_count' => 'Res',
            'id' => 'ID',
            'name' => 'Оператор',
            'class' => 'Класс',
            'url' => 'Урл',
            'position' => 'Позиция',
            'countries_hash' => 'Хеш стран',
            'dep_cities_hash' => 'Хеш городов вылета',
            'resorts_hash' => 'Хеш курортов',
            'hotels_hash' => 'Хеш отелей',
            'blocked' => 'Заблокириован',
        );
    }


    /**
     * beforeDelete
     * @return bool
     */
    protected function beforeDelete(){

        // Чистим таблицы с удаляемым туроператором
        $db = Yii::app()->db;

        // Deletes operator's countries
        $db->createCommand()->delete('{{operator_countries}}', 'operator_id = :oid', array(':oid' => $this->id));

        // Deletes operator's departure cities
        $db->createCommand()->delete('{{operator_dep_cities}}', 'operator_id = :oid', array(':oid' => $this->id));

        // Deletes operator's hotels
        $db->createCommand()->delete('{{operator_hotels}}', 'operator_id = :oid', array(':oid' => $this->id));

        // Deletes operator's hotel categories
        $db->createCommand()->delete('{{operator_hotel_categories}}', 'operator_id = :oid', array(':oid' => $this->id));

        // Deletes operator's hotel statuses
        $db->createCommand()->delete('{{operator_hotel_statuses}}', 'operator_id = :oid', array(':oid' => $this->id));

        // Deletes operator's meals
        $db->createCommand()->delete('{{operator_meals}}', 'operator_id = :oid', array(':oid' => $this->id));

        // Deletes operator's relations between departure cities and countries
        $db->createCommand()->delete('{{operator_relations_dep_cities_countries}}', 'operator_id = :oid', array(':oid' => $this->id));

        // Deletes operator's resorts
        $db->createCommand()->delete('{{operator_resorts}}', 'operator_id = :oid', array(':oid' => $this->id));

        // Deletes operator's ticket statuses
        $db->createCommand()->delete('{{operator_ticket_statuses}}', 'operator_id = :oid', array(':oid' => $this->id));

        return parent::beforeDelete();
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
//        $criteria->with = ['countries'];
//        $criteria->together = true;

        $criteria->compare('t.id',$this->id);
        $criteria->compare('t.name',$this->name, true);
        $criteria->compare('t.class',$this->class);
        $criteria->compare('t.url',$this->url);
        $criteria->compare('t.position',$this->position);
        $criteria->compare('t.blocked',$this->blocked);

        //$criteria->group = 't.id';
        //$criteria->select = 'COUNT(countries.id) AS countries_count, COUNT(resorts.id) AS resorts_count';

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort' => [
                'defaultOrder' => 't.name ASC',
//                'attributes' => [
//                    'countries_count' => [
//                        'asc' => 'countries_count ASC',
//                        'desc' => 'countries_count DESC'
//                    ],
//                    '*'
//                ]
            ],
            'pagination' => ['pageSize'=> 30]
        ));
    }

    /**
     * Returns img path
     * @param string $oClass
     * @return string
     */
    public static function imgPath($oClass){
        $path = Yii::app()->baseUrl . '/images/operators/' . $oClass . '.png';
        return $path;
    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ArOperators
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }


    public static function operatorFreeElements(){
        if( !isset( ArOperators::$freeElements ) ){
            $elements = array();

            $depCities = ArOperators::getFreeElements('dep_cities');
            if( count($depCities) ){
                $elements['dep_cities'] = $depCities;
            }

            $countries = ArOperators::getFreeElements('countries');
            if( count($countries) ){
                $elements['countries'] = $countries;
            }

            $resorts = ArOperators::getFreeElements('resorts');
            if( count($resorts) ){
                $elements['resorts'] = $resorts;
            }

            $hotels = ArOperators::getFreeElements('hotels');
            if( count($hotels) ){
                $elements['hotels'] = $hotels;
            }

            ArOperators::$freeElements = $elements;
        }

        return ArOperators::$freeElements;
    }

    public static function isFreeOperator($id){
        $elements = ArOperators::operatorFreeElements();

        if( count($elements) ){

            if( isset( $elements['dep_cities'] ) && isset( $elements['dep_cities'][$id] ) ){
                return true;
            }

            if( isset( $elements['countries'] ) && isset( $elements['countries'][$id] ) ){
                return true;
            }

            if( isset( $elements['resorts'] ) && isset( $elements['resorts'][$id] ) ){
                return true;
            }

            if( isset( $elements['hotels'] ) && isset( $elements['hotels'][$id] ) ){
                return true;
            }

        }

        return false;
    }

    public static function isFreeTable($id, $table){
        $elements = ArOperators::operatorFreeElements();

        if( count($elements) ){
            if( isset( $elements[$table] ) && isset( $elements[$table][$id] ) ){
                return true;
            }
        }

        return false;
    }

    private static function getFreeElements($table){
        $elements = Yii::app()->db->createcommand()
                    ->select('*')
                    ->from('{{operator_' . $table . '}}')
                    ->where('directory_id = 0')
                    ->setFetchMode(PDO::FETCH_OBJ)
                    ->queryAll();

        $operators = array();
        foreach( $elements as $element ){
            $operatorId = $element->operator_id;

            if( !isset($operators[$operatorId]) ){
                $operators[$operatorId] = array();
            }

            $operators[$operatorId][] = $element;
        }

        return $operators;
    }
}