<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 26.11.14
 * Time: 19:21
 * To change this template use File | Settings | File Templates.
 */

namespace TSearch;

use PDO;
use Yii;
use CHtml;
use CJSON;

class Searcher {

    /**
     * @var array
     */
    private $operators;

    /**
     * @var array
     */
    private $dep_cities;

    /**
     * @var array
     */
    private $countries;


    /**
     * Constructor
     * @param mixed $filters
     */
    public function __construct($filters=null){
        $this->operators = empty($filters['operators']) ? array_keys(TOperator::operatorsInfo()) : (array)$filters['operators'];
        $this->countries = empty($filters['countries']) ? null : (array)$filters['countries'];
        $this->dep_cities = empty($filters['dep_cities']) ? null : (array)$filters['dep_cities'];
    }


    /**
     * Returns list of departure cities
     * @param bool $list
     * @return array
     */
    public function depCities($list=true){
        $params = ['disabled' => 0, 'operators' => $this->operators, 'f_deleted' => 0];

        if( $this->dep_cities ) {
            $params['id'] = $this->dep_cities;
        }

        $depCities = tbl\Directory::loadData('dep_cities', $params, false);
        return $list ? CHtml::listData($depCities, 'id', 'name') : $depCities;
    }

    /**
     * Returns list of departure cities
     * @param integer $dep_city
     * @param bool $list
     * @return array
     */
    public function countries($dep_city, $list=true){
        $where = ['AND', 'odc.directory_id = :dep_city', 'dc.disabled = 0', 'oc.f_deleted = 0', 'odc.f_deleted = 0', ['IN', 'odc.operator_id', $this->operators ]];

        if( $this->countries ){
            $where[] = ['IN', 'dc.id', $this->countries];
        }

        $countries = Yii::app()->db->createCommand()
            ->select('dc.id, dc.name')
            ->from('{{operator_dep_cities}} AS odc')
            ->join('{{operator_relations_dep_cities_countries}} AS rcc', 'rcc.dep_city = odc.element_id AND rcc.operator_id = odc.operator_id')
            ->join('{{operator_countries}} AS oc', 'oc.element_id = rcc.country AND oc.operator_id = rcc.operator_id')
            ->join('{{directory_countries}} AS dc', 'dc.id = oc.directory_id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->where($where, [':dep_city' => $dep_city])
            ->group('dc.id')
            ->order('dc.position, dc.name')
            ->queryAll();

        return $list ? CHtml::listData( $countries, 'id', 'name' ) : $countries;

    }

    /**
     * Returns list of relations between countries and departure cities
     * @param bool $json
     * @return array|string
     */
    public function cdcRelations($json=true){
        $where = ['AND', 'dc.disabled = 0', 'odc.f_deleted = 0', 'oc.f_deleted = 0', ['IN', 'odc.operator_id', $this->operators ]];

        if( $this->dep_cities ) {
            $where[] = ['IN', 'odc.directory_id', $this->dep_cities];
        }

        if( $this->countries ) {
            $where[] = ['IN', 'dc.id', $this->countries];
        }

        $relations = Yii::app()->db->createCommand()
            ->select('dc.id, dc.name, CONCAT(",", GROUP_CONCAT(DISTINCT odc.directory_id ORDER BY odc.directory_id SEPARATOR ","), ",") AS cities')
            ->from('{{operator_dep_cities}} AS odc')
            ->join('{{operator_relations_dep_cities_countries}} AS rcc', 'rcc.dep_city = odc.element_id AND rcc.operator_id = odc.operator_id')
            ->join('{{operator_countries}} AS oc', 'oc.element_id = rcc.country AND oc.operator_id = rcc.operator_id')
            ->join('{{directory_countries}} AS dc', 'dc.id = oc.directory_id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->where($where)
            ->group('dc.id')
            ->order('dc.position, dc.name')
            ->queryAll();

        return $json ? CJSON::encode($relations) : $relations;

    }

    /**
     * Returns list of meals
     * @param bool $list
     * @return array
     */
    public function meals($list=true){
        $meals = tbl\Directory::loadData('meals', ['disabled' => 0, 'operators' => $this->operators]);

        if( $list ) {
            $meals_list = [];
            foreach ($meals as $meal) {
                $spaces = strlen($meal->name) == 3 ? "&nbsp;&nbsp;" : "&nbsp;&nbsp;&nbsp;";
                $meals_list[$meal->id] = $meal->name . $spaces . ' ' . $meal->description . '';
            }

            return $meals_list;
        }

        return $meals;
    }

    /**
     * Returns list of resorts
     * @param integer $country_id
     * @param bool $list
     * @return array
     */
    public function resorts($country_id, $list=true){
        $resorts = tbl\Directory::loadData('resorts', ['disabled' => 0, 'dir_country_id' => $country_id, 'operators' => $this->operators], false);
        return $list ? CHtml::listData($resorts, 'id', 'name') : $resorts;
    }

    /**
     * Returns list of hotel categories
     * @param bool $list
     * @return array
     */
    public function hotelCategories($list=true){
        $categories = tbl\Directory::loadData('hotel_categories', ['disabled' => 0, 'operators' => $this->operators]);
        return $list ? CHtml::listData( $categories, 'id', 'name' ) : $categories;
    }

    /**
     * Collects and returns resorts, hotels and operators
     * @param integer $dep_city
     * @param integer $country
     * @return array
     */
    public function RHO($dep_city, $country) {
        $db = Yii::app()->db;
        $where = ['AND', 'odc.directory_id = :dep_city', 'o.blocked = 0', 'oc.f_deleted = 0', 'odc.f_deleted = 0', 'oc.directory_id = :country', ['IN', 'o.id', $this->operators ]];

        // Собираем список операторов, у которых есть перелеты из $dep_city_id в $country_id
        $o = $db->createCommand()
            ->select('o.id, o.name')
            ->from('{{operators}} AS o')
            ->join('{{operator_dep_cities}} AS odc', 'odc.operator_id = o.id')
            ->join('{{operator_relations_dep_cities_countries}} AS rcc', 'rcc.dep_city = odc.element_id AND rcc.operator_id = odc.operator_id')
            ->join('{{operator_countries}} AS oc', 'oc.element_id = rcc.country AND oc.operator_id = rcc.operator_id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->where( $where, [ ':dep_city' => $dep_city, ':country' => $country ] )
            ->group('o.id')
            ->order('o.position, o.name')
            ->queryAll();

        $valid_operators = [];
        foreach( $o as $operator ) {
            $valid_operators[] = $operator->id;
        }

        // Вытаскиваем курорты
        $r = $db->createCommand()
            ->select('dr.id, dr.name, dr.parent_id, dr.is_combined')
            ->from('{{directory_resorts}} AS dr')
            ->join('{{operator_resorts}} AS or', 'or.directory_id = dr.id')
            ->where(
                ['AND', 'dr.dir_country_id = :country', 'or.f_deleted = 0', 'dr.disabled = 0', ['IN', 'or.operator_id', $valid_operators] ],
                [':country' => $country])
            ->group('dr.id')
            ->order('dr.name')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        // Собираем комбинированные курорты
        $combined = [];
        $all = [];
        foreach ($r as $_r) {

            if( $_r->is_combined ) {
                $combined[$_r->id] = 1;
            }

            if( $_r->parent_id ) {
                $combined[$_r->parent_id] = 1;
            }

            $all[$_r->id] = $_r;
        }

        // Вытаскиваем дочерние курорты
        if( !empty( $combined ) ) {

            // Проверяем, если есть нескрещенные родители с скрещенными детьми
            $not_related = array_diff_key($combined, $all);

            if( !empty($not_related) ){

                // Получаем список весь список:
                // все скрещенные курорты и нескрещенные родители у которых есть скрещенные дети.
                // Вытаскиваем еще раз $all для того что бы все курорты правильно отсортировались по имени: ->order('dr.name')

                $temp_all = $db->createCommand()
                    ->select('dr.id, dr.name, dr.parent_id, dr.is_combined')
                    ->from('{{directory_resorts}} AS dr')
                    ->where(['OR', ['IN', 'id', array_keys($all)], ['IN', 'id', array_keys($not_related)]])
                    ->order('dr.name')
                    ->setFetchMode(PDO::FETCH_OBJ)
                    ->queryAll();

                $all = [];
                foreach ($temp_all as $temp_resort){
                    $all[$temp_resort->id] = $temp_resort;
                }
            }

            // Вытаскиваем всех детей для комбинированных курортов
            $children = $db->createCommand()
                ->select('id, parent_id')
                ->from('{{directory_resorts}}')
                ->where(['IN', 'parent_id', array_keys($combined)])
                ->group('id')
                ->order('name')
                ->setFetchMode(PDO::FETCH_OBJ)
                ->queryAll();

            // Для комбинированных курортов создаем массив из списка ID детей
            foreach ($children as $child) {
                if( isset($all[$child->parent_id]) ) {
                    $all[$child->parent_id]->children[] = $child->id;
                }
            }

            // Приводим новые данные к стандартному виду(без ключей, что бы можно было использовать как js-массив)
            $r = array_values($all);
        }


        // ",3," AS operators
        // Вытаскиваем отели
        $h = $db->createCommand()
            ->select('
                    dh.id,
                    dh.dir_resort_id AS resort_id,
                    CONCAT(dh.name, " ", dhc.name) AS name,
                    dhc.id AS category_id,
                    CONCAT(",", GROUP_CONCAT(oh.operator_id), ",") AS operators
                ')
            ->from('{{directory_hotels}} AS dh')
            ->leftJoin('{{operator_hotels}} AS oh', 'oh.directory_id = dh.id')
            ->join('{{directory_hotel_categories}} AS dhc', 'dhc.id = dh.dir_category_id')
            ->where(
                ['AND', 'dh.disabled = 0', 'dhc.disabled = 0', 'oh.f_deleted = 0', 'dh.dir_country_id = :country', ['IN', 'oh.operator_id', $valid_operators] ],
                [':country' => $country])
            ->order('dh.name')
            ->group('dh.id')
            //->limit(15000)
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();


        return [$r, $h, $o];
    }

}