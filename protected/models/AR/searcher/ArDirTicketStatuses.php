<?php

/**
 * This is the model class for table "{{directory_ticket_statuses}}".
 *
 * The followings are the available columns in table '{{directory_ticket_statuses}}':
 * @property integer $id
 * @property string  $name
 * @property string  $description
 * @property string  $position
 * @property integer $rating
 * @property string  $disabled
 */
class ArDirTicketStatuses extends ArDirectorySearch {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{directory_ticket_statuses}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'nameUnique'),
            array('name', 'length', 'max'=>255),
            ['position, rating, disabled', 'numerical', 'integerOnly'=>true],
            array('description', 'safe'),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, description, position, rating, disabled', 'safe', 'on'=>'search'),
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
            'name' => 'Статус билета',
            'description' => 'Описание',
            'position' => 'Позиция',
            'rating' => 'Рейтинг',
            'disabled' => 'Заблокирован',
        );
    }

    public function nameUnique($attribute){
        $model = self::model()->find('LOWER(name) = :name', [':name' => mb_strtolower($this->$attribute, 'utf8')]);

        if( $model !== null ){
            if( $model->id != $this->id ){
                $this->addError($attribute, 'Статус билета с таким названием уже существует.');
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

        $criteria->compare('id',$this->id);
        $criteria->compare('LOWER(t.name)', mb_strtolower($this->name, 'utf8'), true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('position',$this->position);
        $criteria->compare('rating',$this->rating);
        $criteria->compare('disabled',$this->disabled);


        return new CActiveDataProvider($this, [
            'criteria'=>$criteria,
            'pagination' => [
                'pageSize' => 30
            ],
            'sort' => [
                'defaultOrder' => 'name ASC'
            ]
        ]);
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