<?php

/**
 * This is the model class for table "{{directory_regions}}".
 *
 * The followings are the available columns in table '{{directory_regions}}':
 * @property integer  $id
 * @property string   $name
 * @property string   $type
 * @property string   $description
 * @property integer  $dir_country_id
 */
class ArDirRegions extends CActiveRecord {

    const REG_TYPE_DISTRICT = 'district';

    const REG_TYPE_REGION = 'free_region';

    const REG_TYPE_ISLAND = 'island';

    const REG_TYPE_PROVINCE = 'province';

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{directory_regions}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, dir_country_id', 'required'),
            array('dir_country_id', 'numerical', 'tooSmall' => '{attribute} должен быть больше 0', 'integerOnly' => true, 'min' => 1),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, typr, description, dir_country_id', 'safe', 'on'=>'search'),
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
            'name' => 'Регион',
            'type' => 'Тип региона',
            'description' => 'Описание',
            'dir_country_id' => 'Страна',
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
        $criteria->compare('LOWER(t.name)', mb_strtolower($this->name, 'utf8'), true);
        $criteria->compare('type',$this->type);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('dir_country_id',$this->dir_country_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns names of regions types
     * @return array
     */
    public static function regionTypeNames(){
        return [
            self::REG_TYPE_DISTRICT => 'Район',
            self::REG_TYPE_REGION => 'Регион',
            self::REG_TYPE_ISLAND => 'Остров',
            self::REG_TYPE_PROVINCE => 'Провинция',
        ];
    }

    /**
     * Returns name of region type
     * @param string $type
     * @return string
     */
    public static function regionTypeName($type){
        $types = self::regionTypeNames();
        return isset($types[$type]) ? $types[$type] : '';
    }

    /**
     * Returns Region types combo
     * @param array $models
     */
    public static function regionTypesCombo($models){
        $types = [];
        $names = self::regionTypeNames();
        $models = (array)$models;

        foreach( $models as $model ){
            $types[$model->type] = $names[$model->type];
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