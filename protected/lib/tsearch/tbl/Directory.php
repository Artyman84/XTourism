<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 15.01.2016
 * Time: 18:18
 */

namespace TSearch\tbl;
use Yii;
use TUtil;
use TSearch\TOperator;

class Directory {

    /**
     * @param string $table
     * @param null|array $params
     * @param bool $assocById
     * @return array
     */
    public static function loadData($table, $params=null, $assocById=true) {
        $elements = self::buildQuery($table, $params)->queryAll();
        return $assocById ? TUtil::listKey($elements) : $elements;
    }

    /**
     * Loads ids of data
     * @param string $table
     * @param null|array $params
     * @return array
     */
    public static function loadIds($table, $params=null) {
        return array_keys(self::loadData($table, $params, true));
    }

    /**
     * @param string $table
     * @param null|array $params
     * @return CDbCommand
     */
    private static function buildQuery($table, $params=null){
        $directory = Yii::app()->db->createCommand();
        $directory->select('directory.*');
        $directory->from('{{directory_' . $table . '}} AS directory');
        $directory->order('directory.position, directory.name');
        $directory->group('directory.id');
        $directory->setFetchMode(\PDO::FETCH_OBJ);

        $condition = ['AND'];
        $placeholders = [];

        if( isset($params['operators']) ){
            $operators = array_keys(TOperator::operatorsInfo($params['operators']));
            unset($params['operators']);

            $directory->join('{{operator_' . $table . '}} AS operator', 'operator.directory_id = directory.id');
            $condition[] = ['IN', 'operator.operator_id', $operators];

            if( isset($params['f_deleted']) ) {
                $condition[] = 'operator.f_deleted = :f_d';
                $placeholders[':f_d'] = $params['f_deleted'];
                unset($params['f_deleted']);
            }
        }

        if( is_array($params) && !empty($params) ){
            foreach( $params as $key => $value ){

                if( $key == 'name' || $key == 'description' ){
                    $condition[] = ['LIKE', 'directory.' . $key, '%' . $value . '%'];

                } elseif( is_array($value) ){
                    $condition[] = ['IN', 'directory.' . $key, $value];

                } else {
                    $condition[] = 'directory.' . $key . '=:' . $key;
                    $placeholders[':' . $key] = $value;
                }
            }
        }

        $directory->where($condition, $placeholders);
        return $directory;
    }

}