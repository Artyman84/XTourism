<?php

/**
 * This is the model class for table "{{user_tour_showcase}}".
 *
 * The followings are the available columns in table '{{user_tour_showcase}}':
 * @property string $id
 * @property integer $user_id
 * @property integer $dc_dir_id
 * @property string $type
 * @property string $settings
 */
class ArUserTourShowcase extends CActiveRecord {

    /**
     * Standard showcase
     */
    const SHOWCASE_STANDARD = 'standard';

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
     * Settings for user's showcase for different types
     * @var array
     */
    private static $default_settings = [
        self::SHOWCASE_STANDARD => [
            'bg_color' => '#FFFFFF',
            'bg_block_color' => '#FFFFFF',
            'tour_link_color' => '#31708f',
            'price_color' => '#333333',
            'price_label_color' => '#FBF4C7',
            'icon_color' => '#31708f',
            'pagination_color' => '#FFFFFF',
            'rounding' => 0,
            'open_tour_target' => '_blank',
            'currency' => 0,
            'country' => 0,
            'resort' => 0,
            'category' => 0,
            'operators' => [],
            'countries' => [],
            'rows' => 2,
//            'per_row' => '0',
        ]
    ];

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{user_tour_showcase}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['user_id, dc_dir_id, type', 'required'],
			['user_id, dc_dir_id', 'length', 'max' => 11],
            ['settings', 'safe'],

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			['id, user_id, dc_dir_id, type, settings', 'safe', 'on'=>'search'],
		];
	}

    /**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
            'user' => [self::BELONGS_TO, 'ArUsers', 'user_id'],
            'tours' => [self::HAS_MANY, 'ArTourShowcaseTours', 'user_showcase_id']
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'user_id' => 'Турагент',
			'dc_dir_id' => 'Город вылета',
            'user_name' => 'Турагент',
			'type' => 'Тип витрины',
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
     * @return null|TourShowcaseStandardSettings
     */
    public function showcaseSettings(){
        $Settings = null;

        if( !$this->isNewRecord ) {
            $settings = array_merge(self::defaultSettings($this->type), $this->settings);

            $modelName = self::settingsModelName($this->type);
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
        $criteria->with = [
            'user' => [
                'with' => [
                    'package'// =>
//                        [
//                        'with' => 'products'
//                    ]
                ]
            ]
        ];
        $criteria->together= true;

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.dc_dir_id',$this->dc_dir_id);
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
	 * @return ArUserTourShowcase the static model class
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
     * Returns name of showcase's settings class
     * @param string $type
     * @return string
     */
    public static function settingsModelName($type){
        return 'TourShowcase' . ucfirst($type) . 'Settings';
    }

}

