<?php

/**
 * This is the model class for table "{{directory_hotels}}".
 *
 * The followings are the available columns in table '{{directory_hotels}}':
 * @property integer  $id
 * @property string   $name
 * @property string   $description
 * @property integer  $dir_country_id
 * @property integer  $dir_city_id
 * @property integer  $dir_resort_id
 * @property integer  $dir_category_id
 * @property string  $address
 * @property string  $coords
 * @property float  $rating
 * @property integer  $position
 * @property integer  $disabled
 * @property string  $url
 */
class ArDirHotels extends ArDirectorySearch {


    /**
     * Hotel's resort name
     * @var string
     */
    private $resort_name;

    /**
     * Hotel's city name
     * @var string
     */
    private $city_name;

    /**
     * Hotel's category name
     * @var string
     */
    private $category_name;

    /**
     * @var integer
     */
    private $old_resort_id;

    /**
     * @var integer
     */
    private $dir_region_id;


    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{directory_hotels}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, dir_country_id, dir_city_id, dir_resort_id, dir_category_id', 'required'),
            array('dir_country_id, dir_city_id, dir_resort_id, dir_category_id', 'numerical', 'tooSmall' => '{attribute} должен быть больше 0', 'integerOnly' => true, 'min' => 1),
            ['position, rating, disabled', 'numerical', 'integerOnly'=>true],
            array('name, address, coords', 'length', 'max'=>255),
            array('description', 'safe'),
            array('name', 'nameUnique'),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, description, dir_country_id, dir_city_id, dir_resort_id, dir_category_id, address, coords, position, rating, disabled, resort_name, city_name, category_name, url', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'cards' => array(self::MANY_MANY, 'ArCurrencyCards', 'xt_hotel_cards(dir_hotel_id, card_id)'),
            'ratings' => array(self::HAS_ONE, 'ArHotelRating', 'dir_hotel_id'),
            'residence' => array(self::HAS_MANY, 'ArHotelResidence', 'dir_hotel_id'),
            'services' => array(self::HAS_MANY, 'ArHotelServices', 'dir_hotel_id'),
            'category' => array(self::BELONGS_TO, 'ArDirHotelCategories', 'dir_category_id'),
            'country' => array(self::BELONGS_TO, 'ArDirCountries', 'dir_country_id'),
            'city' => array(self::BELONGS_TO, 'ArDirCities', 'dir_city_id'),
            'resort' => array(self::BELONGS_TO, 'ArDirResorts', 'dir_resort_id'),
            'photos' => array(self::HAS_ONE, 'ArHotelPhotos', 'dir_hotel_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
            'dir_country_id' => 'Страна',
            'dir_city_id' => 'Город',
            'dir_resort_id' => 'Курорт',
            'dir_category_id' => 'Категория',
            'resort_name' => 'Курорт',
            'city_name' => 'Город',
            'category_name' => 'Категория',
            'position' => 'Позиция',
            'rating' => 'Рейтинг',
            'disabled' => 'Заблокирован',
        );
    }

    /**
     * Validates name
     * @param string $attribute
     */
    public function nameUnique($attribute){

        $model = self::model()->find(
            'LOWER(name) = :name AND dir_city_id = :dir_city_id AND dir_country_id = :country',
            [':name' => mb_strtolower($this->$attribute, 'utf8'), ':dir_city_id' => $this->dir_city_id, ':country' => $this->dir_country_id]
        );

        if( $model !== null && $model->id != $this->id ){
            $this->addError($attribute, 'Отель с таким названием уже существует в городе "' . $this->city->name . '".');
        }

        $model = self::model()->find(
            'name = :name AND dir_resort_id = :dir_resort_id AND dir_country_id = :country',
            [':name' => $this->$attribute, ':dir_resort_id' => $this->dir_resort_id, ':country' => $this->dir_country_id]
        );

        if( $model !== null && $model->id != $this->id ){
            $this->addError($attribute, 'Отель с таким названием уже существует в курорте "' . $this->resort->name . '".');
        }
    }

    /**
     * Returns hotel's cards
     * @return array
     */
    public function cards(){
        $cards = [];
        if( !$this->isNewRecord ){
            foreach($this->cards as $card){
                $cards[$card->id] = $card;
            }
        }

        return $cards;
    }


    /**
     * Turns coords to google url
     * @return string
     */
    public function coords2googleUrl(){

        if( $this->coords ) {

            $coords = explode(',', $this->coords);

            if (!empty($coords)) {
                if (count($coords) == 4) {

                    $lat_bc = bcadd($coords[1], $coords[3], 13);
                    $lat_bc = bcdiv($lat_bc, 2, 13);

                    $lon_bc = bcadd($coords[0], $coords[2], 13);
                    $lon_bc = bcdiv($lon_bc, 2, 13);

                } elseif (count($coords) == 2) {
                    $lat_bc = $coords[0];
                    $lon_bc = $coords[1];
                }
            }
        }

        $url = '';
        if( isset($lat_bc) && isset($lon_bc) ){
            $url = TUtil::googleUrl($lat_bc, $lon_bc);
        }

        return $url;
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
        $criteria->with = ['resort', 'city', 'category'];
        $criteria->together= true;

        $criteria->compare('t.id',$this->id);
        $criteria->compare('LOWER(t.name)', mb_strtolower($this->name, 'utf8'), true);
        $criteria->compare('t.description',$this->description,true);
        $criteria->compare('t.dir_country_id',$this->dir_country_id);
        $criteria->compare('t.dir_city_id',$this->dir_city_id);
        $criteria->compare('t.dir_category_id',$this->dir_category_id);
        $criteria->compare('t.position',$this->position);
        $criteria->compare('t.rating',$this->rating);
        $criteria->compare('t.disabled',$this->disabled);

        if( is_array($this->dir_resort_id) ){
            $criteria->addInCondition('t.dir_resort_id', $this->dir_resort_id);
        } else {
            $criteria->compare('t.dir_resort_id',$this->dir_resort_id);
        }


        return new CActiveDataProvider($this, [
            'criteria'=>$criteria,
            'pagination' => [
                'pageSize' => 30
            ],
            'sort' => [
                'defaultOrder' => 't.name ASC',
                'attributes' => [
                    'resort_name' => [
                        'asc' => 'resort.name',
                        'desc' => 'resort.name DESC',
                    ],
                    'city_name' => [
                        'asc' => 'city.name',
                        'desc' => 'city.name DESC',
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
     * After Find Event
     */
    protected function afterFind(){
        $this->old_resort_id = $this->dir_resort_id;
        return parent::afterFind();
    }


    /**
     * Before Save Event
     */
    protected function afterSave(){

        // if resort ID has be changed then un bind hotel
        if( $this->old_resort_id != $this->dir_resort_id ) {
            parent::unbindDirectories($this->id, 'hotels');
        }

        if($this->old_resort_id != $this->dir_resort_id){
            $this->bindDirectories();
        } else {
            parent::afterSave();
        }
    }

    /**
     * Deletes hotel
     * @return bool
     */
    protected function beforeDelete(){

        self::clearHotelsExtraData($this->id);
        return parent::beforeDelete();
    }

    /**
     * Clears hotels extra data
     * @param array $ids
     */
    public static function clearHotelsExtraData($ids){
        $tables = [
            'hotel_cards',
            'hotel_photos',
            'hotel_ratings',
            'hotel_residence',
            'hotel_services'
        ];

        $db = Yii::app()->db;
        foreach( $tables as $table ){
            $db->createCommand()->delete('{{' . $table . '}}', ['IN', 'dir_hotel_id', $ids]);
        }

        ArHotelPhotos::deletePhotos($ids);
    }

    /**
     * Returns hotel's images
     * @param bool $url
     * @return array
     */
    public function images($url=true){
        return !$this->isNewRecord ? $this->photos->images($this->dir_country_id, $this->dir_city_id, $url) : [];
    }

    /**
     * Returns hotel's image
     * @param int $nr
     * @param bool $url
     * @return string|null
     */
    public function image($nr, $url=true){
        return !$this->isNewRecord ? $this->photos->image($nr, $this->dir_country_id, $this->dir_city_id, $url) : null;
    }


    /**
     * Returns hotel's location path
     * @return string
     */
    public function locationPath(){
        $location = '';
        if( !$this->isNewRecord && ($resort = $this->resort) && ($country = $this->country) ) {
            $separator = '&nbsp;&raquo;&nbsp;';
            $location = CHtml::encode($country->name) . $separator . ($resort && $resort->parent_id ? CHtml::encode($resort->parent->name) . $separator : '') . CHtml::encode($resort->name);
        }

        return $location;
    }


    /**
     * Saves hotel's credit cards
     * @param integer $h_id
     * @param array $cards
     */
    public static function saveCreditCards($h_id, $cards){
        $db = Yii::app()->db;
        $db->createCommand()->delete('{{hotel_cards}}', 'dir_hotel_id = :h_id', [':h_id' => $h_id]);

        foreach( $cards as $card ){
            $db->createCommand()->insert('{{hotel_cards}}', ['dir_hotel_id' => $h_id, 'card_id' => $card]);
        }
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ArDirHotels the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
}