<?php

/**
 * This is the model class for table "{{directory_resorts}}".
 *
 * The followings are the available columns in table '{{directory_resorts}}':
 * @property integer  $id
 * @property integer  parent_id
 * @property string   $name
 * @property string   $description
 * @property integer  $dir_country_id
 * @property integer  $is_combined
 * @property integer  $position
 * @property integer  $rating
 * @property integer  $disabled
 */
class ArDirResorts extends ArDirectorySearch {

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
        return '{{directory_resorts}}';
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
            ['position, rating, disabled, parent_id', 'numerical', 'integerOnly'=>true],
            ['description, is_combined, district, free_region, province, island', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, parent_id, description, position, is_combined, rating, disabled, dir_region_id', 'safe', 'on'=>'search'],
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
            'region' => [self::MANY_MANY, 'ArDirRegions', 'xt_directory_resorts_to_regions(dir_resort_id, dir_region_id)'],
            'children' => [self::HAS_MANY, 'ArDirResorts', 'parent_id', 'order' => 'children.name ASC'],
            'parent' => [self::BELONGS_TO, 'ArDirResorts', 'parent_id'],
            'cities' => [self::MANY_MANY, 'ArDirCities', 'xt_directory_hotels(dir_resort_id, dir_city_id)', 'order' => 'cities.name ASC']
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'parent_id' => 'ID Родителя',
            'name' => 'Курорт',
            'description' => 'Описание',
            'dir_country_id' => 'Страна',
            'dir_region_id' => 'Регион',
            'country_name' => 'Страна',
            'district' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_DISTRICT),
            'free_region' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_REGION),
            'province' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_PROVINCE),
            'island' => ArDirRegions::regionTypeName(ArDirRegions::REG_TYPE_ISLAND),
            'position' => 'Позиция',
            'rating' => 'Рейтинг',
            'disabled' => 'Заблокирован',
            'is_combined' => 'Комбинированный',
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
                $this->addError($attribute, 'Курорт с таким названием уже существует.');
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
        // При together = true - вытаскивает разное количество записей ( < 30 )
        //$criteria->together= true;

        $criteria->compare('t.id',$this->id);
        $criteria->compare('t.parent_id',$this->parent_id);
        $criteria->compare('LOWER(t.name)', mb_strtolower($this->name, 'utf8'), true);
        $criteria->compare('t.description',$this->description,true);
        $criteria->compare('t.dir_country_id',$this->dir_country_id);
        $criteria->compare('t.is_combined',$this->is_combined);
        $criteria->compare('t.position',$this->position);
        $criteria->compare('t.rating',$this->rating);
        $criteria->compare('t.disabled',$this->disabled);

        if( !empty($this->dir_region_id) ) {
            $criteria->together = true;
            $criteria->compare('region.id', $this->dir_region_id);
        }

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
     * Deletes resort
     * @return bool
     */
    protected function beforeDelete(){

        $db = Yii::app()->db;
        $hotels = $db->createCommand()->select('id')->from('{{directory_hotels}}')->where('dir_resort_id = :resort', [':resort' => $this->id])->group('dir_resort_id')->queryScalar();

        if( !empty($hotels) ) {
            return false;
        }

        // Удалить связи с регионами
        $db->createCommand()->delete('{{directory_resorts_to_regions}}', ['IN', 'dir_resort_id', [$this->id]]);

        if( $this->is_combined ) {
            // Обнуляем ID-родителя дочерних курортов
            $db->createCommand()->update('{{directory_resorts}}', ['parent_id' => 0], 'parent_id = :p_id', [':p_id' => $this->id]);
        }

        return parent::beforeDelete();
    }

    /**
     * Returns uncombined resorts
     * @param integer $country_id
     * @return array|static[]
     */
    public static function uncombinedResorts($country_id){
        $resorts = ArDirResorts::model()->with('region')->findAllByAttributes(['dir_country_id' => $country_id, 'is_combined' => 0]);
        return $resorts;
    }

    /**
     * Returns combined cities list
     * @return array
     */
    public function childrenResortsList(){
        $resorts = [];
        if( !$this->getIsNewRecord() && $this->is_combined ){
            $resorts = CHtml::listData($this->children, 'id', 'name');
        }

        return $resorts;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ArDirResorts
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
}