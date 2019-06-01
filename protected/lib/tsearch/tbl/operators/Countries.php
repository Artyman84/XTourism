<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 18.01.2016
 * Time: 16:23
 */

namespace TSearch\tbl\operators;
use Yii;

class Countries extends \TSearch\tbl\Operator {

    /**
     * Construct
     */
    protected function __construct(){
        parent::__construct('countries');
    }

    /**
     * Sets "f_deleted = 1" for nonexistent countries
     * @param int $oid
     * @param array $elements
     */
    protected function deleteAllNotInElements($oid, $elements){
        $db = Yii::app()->db;

//        // Deletes nonexistent countries with directory_id = 0
//        $db->createCommand()->delete(
//            '{{operator_countries}}',
//            ['AND', 'operator_id = :oid', 'directory_id = 0', ['NOT IN', 'element_id', $elements]],
//            [':oid' => $oid]
//        );

        // Updates nonexistent countries with directory_id != 0
        $db->createCommand()->update(
            '{{operator_countries}}',
            ['f_deleted' => 1],
            ['AND', 'operator_id = :oid', 'directory_id != 0', ['NOT IN', 'element_id', $elements]],
            [':oid' => $oid]
        );
    }

}