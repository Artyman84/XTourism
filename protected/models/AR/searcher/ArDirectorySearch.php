<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 12.10.2015
 * Time: 11:50
 */

abstract class ArDirectorySearch extends CActiveRecord {

    /**
     * @var string
     */
    protected $old_name;

    /**
     * Dir's AR names of models
     * @var array
     */
    private static $arModelsDir = [
        'countries' => 'ArDirCountries',
        'cities' => 'ArDirCities',
        'resorts' => 'ArDirResorts',
        'hotels' => 'ArDirHotels',
        'dep_cities' => 'ArDirDepCities',
        'hotel_categories' => 'ArDirHotelCategories',
        'meals' => 'ArDirMeals',
        'hotel_statuses' => 'ArDirHotelStatuses',
        'ticket_statuses' => 'ArDirTicketStatuses',
    ];

    /**
     * Db's table's names
     * @var array
     */
    private static $tableNames = [
        'countries' => 'Страны',
        'cities' => 'Города',
        'resorts' => 'Курорты',
        'hotels' => 'Отели',
        'dep_cities' => 'Города вылета',
        'hotel_categories' => 'Категории отелей',
        'meals' => 'Типы питания',
        'hotel_statuses' => 'Статусы отелей',
        'ticket_statuses' => 'Статусы билетов',
    ];


    /**
     * Deletes country
     * @return bool
     */
    protected function beforeDelete(){
        self::unbindDirectories( $this->id, str_replace(['{{', 'directory_', '}}'], '', $this->tableName()) );
        return parent::beforeDelete();
    }

    /**
     * After Find Event
     */
    protected function afterFind(){
        $this->old_name = $this->name;
        return parent::afterFind();
    }

    /**
     * After Save Event
     */
    protected function afterSave(){
        parent::afterSave();

        // If Name has be changed then re bind
        if( $this->old_name != $this->name ) {
            $this->bindDirectories();
        }
    }

    /**
     * Collects and returns grouped by operators elements
     * @param string $table
     * @param array|integer $ids
     * @return array
     */
    private static function collectElements($table, $ids){
        $ids = (array)$ids;

        // Получаем все связанные элементы операторов по директориям
        $elements = Yii::app()->db->createCommand()->select('element_id, operator_id')
            ->from('{{operator_' . $table . '}}')
            ->where(['IN', 'directory_id', $ids])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        // Группируем полученные элементы по операторам
        $grouped_elements = [];
        foreach ($elements as $element) {
            $grouped_elements[$element->operator_id][] = $element->element_id;
        }

        return $grouped_elements;
    }

    /**
     * Unbinds directories elements
     * @param array $ids
     * @param string $table
     */
    public static function unbindDirectories($ids, $table) {

        $grouped_elements = self::collectElements($table, $ids);

        // Очищаем все связанные элементы операторов
        foreach ($grouped_elements as $oid => $elements) {
            TSearch\BindData::inst($oid)->unbind($table, $elements);
        }
    }

    /**
     * Binds directories elements
     */
    public function bindDirectories(){

        $table = str_replace(['{{', 'directory_', '}}'], '', $this->tableName());

        switch($table){
            case 'hotels':
                $parents = self::collectElements('resorts', $this->dir_resort_id);
                $operators = array_keys($parents);
                break;

            case 'resorts':
                $parents = self::collectElements('countries', $this->dir_country_id);
                $operators = array_keys($parents);
                break;

            default:
                $operators = array_keys(TSearch\TOperator::operatorsInfo());
                $parents = [];
                break;
        }

        foreach ($operators as $oid) {
            TSearch\BindData::inst($oid)->bindComparing($table, isset($parents[$oid]) ? $parents[$oid] : null);
        }
    }

    /**
     * Returns dir's AR model name
     * @param $table
     * @return string
     */
    public static function arDirModelName($table){
        return isset(self::$arModelsDir[$table]) ? self::$arModelsDir[$table] : '';
    }

    /**
     * Returns db's table's name
     * @param string $table
     * @return string
     */
    public static function getTableName($table){
        return isset(self::$tableNames[$table]) ? self::$tableNames[$table] : '';
    }

    /**
     * Returns db's table's names
     * @return array
     */
    public static function getTableNames(){
        return self::$tableNames;
    }

}