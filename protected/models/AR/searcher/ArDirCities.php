<?php

/**
 * This is the model class for table "{{directory_cities}}".
 *
 * The followings are the available columns in table '{{directory_cities}}':
 * @property integer  $id
 * @property string   $name
 * @property string   $description
 * @property integer  $dir_country_id
 */
class ArDirCities extends CActiveRecord {

    /**
     * District's region id
     * @var string
     */
    public $district;

    /**
     * Free Region's region id
     * @var string
     */
    public $free_region;

    /**
     * Province's region id
     * @var string
     */
    public $province;

    /**
     * Island's region id
     * @var string
     */
    public $island;


    /**
     * Resort's region id
     * @var string
     */
    public $dir_region_id;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{directory_cities}}';
    }

    /**
     * Attributes
     * @return array
     */
    public function attributes() {
        return parent::attibutes();
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name, dir_country_id', 'required'],
            ['dir_country_id, district, free_region, province, island', 'numerical', 'tooSmall' => '{attribute} должен быть больше 0', 'integerOnly' => true, 'min' => 1],
            ['name', 'nameUnique'],
            ['name', 'length', 'max'=>255],
            ['description, district, free_region, province, island', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, description, dir_region_id', 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'country' => [self::BELONGS_TO, 'ArDirCountries', 'dir_country_id'],
            'region' => [self::MANY_MANY, 'ArDirRegions', 'xt_directory_cities_to_regions(dir_city_id, dir_region_id)'],
            'resorts' => [self::MANY_MANY, 'ArDirResorts', 'xt_directory_hotels(dir_city_id, dir_resort_id)', 'order' => 'resorts.name ASC']
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Город',
            'description' => 'Описание',
            'dir_country_id' => 'Страна',
            'dir_region_id' => 'Регион',
            'country_name' => 'Страна',
            'district' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_DISTRICT),
            'free_region' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_REGION),
            'province' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_PROVINCE),
            'island' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_ISLAND),
        ];
    }

    /**
     * Validates name
     * @param string $attribute
     */
    public function nameUnique($attribute){
        $model = self::model()->find(
            'LOWER(name) = :name AND dir_country_id = :dir_country_id',
            [':name' => mb_strtolower($this->$attribute, 'utf8'), ':dir_country_id' => $this->dir_country_id]
        );

        if( $model !== null ){
            if( $model->id != $this->id ){
                $this->addError($attribute, 'Город с таким названием уже существует.');
            }
        }
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
        $criteria->with = array('country', 'region');
        $criteria->together= true;

        $criteria->compare('t.id',$this->id);
        $criteria->compare('LOWER(t.name)', mb_strtolower($this->name, 'utf8'), true);
        $criteria->compare('t.description',$this->description,true);
        $criteria->compare('t.dir_country_id',$this->dir_country_id);
        $criteria->compare('region.id',$this->dir_region_id);


        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 30
            ],
            'sort' => [
                'defaultOrder' => 't.name ASC',
            ]
        ]);
    }


    /**
     * Deletes city
     * @return bool
     */
    protected function beforeDelete(){
        // Удалять города пока категорически нельзя!!!
        return false;

        $db = Yii::app()->db;
        $hotels = $db->createCommand()->select('id')->from('{{directory_hotels}}')->where('dir_city_id = :city', [':city' => $this->id])->group('dir_city_id')->queryScalar();

        if( !empty($hotels) ) {
            return false;
        }

        $db->createCommand()->delete('{{directory_cities_to_regions}}', ['IN', 'dir_city_id', [$this->id]]);

        return parent::beforeDelete();
    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ArDirCities the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
}