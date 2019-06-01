<?php

/**
 * This is the model class for table "{{currency_cards}}".
 *
 * The followings are the available columns in table '{{directory_hotels}}':
 * @property integer  $id
 * @property string   $name
 * @property string   $description
 */
class ArCurrencyCards extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{currency_cards}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, description', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'hotels' => array( self::MANY_MANY, 'ArDirHotels', 'xt_hotel_cards(card_id, dir_hotel_id)' )
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Название Карточки',
            'description' => 'Описание',
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
        $criteria->compare('name',$this->name,true);
        $criteria->compare('description',$this->description,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns list of cards
     * @return array
     */
    public static function cards(){
        $cards = Yii::app()->db->createCommand()
            ->select('id, name, description')
            ->from('{{currency_cards}}')
            ->where('description != ""')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        return $cards;
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