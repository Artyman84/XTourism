<?php

/**
 * This is the model class for table "{{user_searcher}}".
 *
 * The followings are the available columns in table '{{user_searcher}}':
 * @property string $id
 * @property integer $user_id
 * @property string $type
 * @property string $settings
 */
class ArUserSearcher extends CActiveRecord {

    /**
     * Full searcher
     */
    const SEARCHER_STANDARD = 'standard';

    /**
     * @var string
     */
    public $user_name;

    /**
     * @var integer
     */
    private $old_type;

    /**
     * @var array
     */
    private $old_settings;

    /**
     * Settings for user's searcher for different types
     * @var array
     */
    private static $default_settings = array(
        self::SEARCHER_STANDARD => array(
            'bg_color_class' => 'orange',
            'spinner' => 0,
            'rounding' => 0,
            'depCity' => 0,
            'country' => 0,
            'hotelCategoryMore' => 1,
            'hotelCategory' => 1,
            'nightFrom' => 7,
            'nightTo' => 14,
            'minPrice' => 0,
            'maxPrice' => 1000000,
            'currency' => 0,
            'adults' => 2,
            'children' => 0,
            'child1' => 1,
            'child2' => 1,
            'child3' => 1,
            'mealsMore' => 1,
            'meals' => 0,
            'operators' => [],
            'countries' => [],
            'dep_cities' => [],
        )
    );

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_searcher}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, type', 'required'),
            array('user_id', 'length', 'max' => 11),
            array('settings', 'safe'),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, type, settings', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'user' => array(self::BELONGS_TO, 'ArUsers', 'user_id')
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'Турагент',
            'user_name' => 'Турагент',
            'type' => 'Тип поисковика',
            'settings' => 'Настройки'
        );
    }

    /**
     * "After Find" event
     */
    protected function afterFind(){
        if( $this->type && $this->settings ) {
            $this->settings = array_merge(self::$default_settings[$this->type], json_decode($this->settings, true));

            $this->old_settings = $this->settings;
            $this->old_type = $this->type;
        }

        return parent::afterFind();
    }

    /**
     * "Before Save" event
     * @return bool
     */
    protected function beforeSave(){

        if( $this->getIsNewRecord() || $this->type != $this->old_type ){
            $this->settings = self::$default_settings[$this->type];
        } else {
            $this->settings = array_merge($this->old_settings, $this->settings);
        }

        $this->settings = CJavaScript::jsonEncode($this->settings);
        return parent::beforeSave();
    }

    /**
     * @return null|SearcherStandardSettings
     */
    public function searcherSettings(){
        $Settings = null;

        if( !$this->isNewRecord ) {
            $settings = array_merge(ArUserSearcher::defaultSettings($this->type), $this->settings);

            $modelName = ArUserSearcher::settingsModelName($this->type);
            $Settings = new $modelName();
            $Settings->attributes = $settings;
        }

        return $Settings;
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
        $criteria->with = ['user'];

        $criteria->compare('t.id',$this->id);
        $criteria->compare('t.user_id',$this->user_id);
        $criteria->compare('t.type',$this->type);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort' => [
                'defaultOrder' => 'user.name ASC, user.lastname ASC',
                'attributes' => [
                    'user_name' => [
                        'asc' => 'user.name, user.lastname',
                        'desc' => 'user.name DESC, user.lastname DESC'
                    ],
                    '*'
                ]
            ],
            'pagination' => ['pageSize'=> 30]
        ));
    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ArUserSearcher the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * Returns default settings
     * @return array
     */
    public static function defaultSettings($type){
        return self::$default_settings[$type];
    }

    /**
     * Returns name of searcher's settings class
     * @param string $type
     * @return string
     */
    public static function settingsModelName($type){
        return 'Searcher' . ucfirst($type) . 'Settings';
    }

}

