<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 18.01.2016
 * Time: 16:23
 */

namespace TSearch\tbl\operators;
use TSearch\tbl\Operator;
use TSearch\TOperator;
use TSearch\MultiCURL;
use Yii;
use TUtil;

class DepCities extends \TSearch\tbl\Operator {

    protected function __construct(){
        parent::__construct('dep_cities');
    }

    /**
     * Updates data table of operators
     * @param mixed $operators
     */
    public function updateData($operators){
        parent::updateData($operators);

        $operators = array_chunk(array_keys(TOperator::operatorsInfo($operators)), self::MAX_OPERATOR_PROCESSING);

        // Updates relations between countries and departure cities
        foreach( $operators as $oIds ) {

            $relations = [];
            $data = $this->loadCountriesByCities($oIds);

            foreach ($data as $oid => $oCountries) {
                foreach ($oCountries as $dep_city_id => $countries) {

                    if (!empty($countries)) {
                        foreach ($countries as $country) {
                            $relations[] = [
                                'operator_id' => $oid,
                                'dep_city' => $dep_city_id,
                                'country' => $country->element_id
                            ];
                        }
                    }
                }
            }

            Yii::app()->db->createCommand()->delete('{{operator_relations_dep_cities_countries}}', ['IN', 'operator_id', $oIds]);
            TUtil::multipleInsertData('operator_relations_dep_cities_countries', $relations);
        }
    }


    /**
     * Loads countries by cities
     * @param array $operators
     * @return array
     */
    private function loadCountriesByCities($operators){
        $depCities = Operator::table('dep_cities')->loadData($operators, ['f_deleted' => 0], true);
        $operators = TOperator::newOperators($operators);
        $countries = [];

        foreach($depCities as $oid => $oDepCities){
            foreach( $oDepCities as $depCity ){
                $countries[$oid][$depCity->element_id] = $operators[$oid]->buildUrls('countries', ['depCity' => $depCity->element_id])[0];
            }
        }

        $cURL = new MultiCURL();
        $data = [];

        foreach( $countries as $oid => $_urls ) {
            foreach($_urls as $dep_city_id => $url) {

                $o = $operators[$oid];
                $data[$oid][$dep_city_id] = [];

                $cURL->add($url, function($content, $info) use($o, &$data, $dep_city_id){
                    $_data = $o->getNormalizedData('countries', $content, $info);

                    TUtil::fastArrayMerge(
                        $data[$o->operatorId()][$dep_city_id],
                        $_data === null ? [] : $_data
                    );
                });
            }
        }

        $cURL->request();
        return $data;
    }

    /**
     * Sets "f_deleted = 1" for nonexistent departure cities
     * @param int $oid
     * @param array $elements
     */
    protected function deleteAllNotInElements($oid, $elements){
        $db = Yii::app()->db;

//        // Deletes nonexistent departure cities with directory_id = 0
//        $db->createCommand()->delete(
//            '{{operator_dep_cities}}',
//            ['AND', 'operator_id = :oid', 'directory_id = 0', ['NOT IN', 'element_id', $elements]],
//            [':oid' => $oid]
//        );

        // Updates nonexistent departure cities with directory_id = 0
        $db->createCommand()->update(
            '{{operator_dep_cities}}',
            ['f_deleted' => 1],
            ['AND', 'operator_id = :oid', ['NOT IN', 'element_id', $elements]],
            [':oid' => $oid]
        );
    }

}