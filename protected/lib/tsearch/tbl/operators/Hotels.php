<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 18.01.2016
 * Time: 16:23
 */

namespace TSearch\tbl\operators;
use Yii;

class Hotels extends \TSearch\tbl\Operator {

    protected function __construct(){
        parent::__construct('hotels');
    }

    /**
     * Populates table's fields
     * @param stdClass $element
     * @param integer $oid
     * @return array
     */
    protected function populateFields($element, $oid){
        $fields = parent::populateFields($element, $oid);
        $fields['country'] = isset( $element->country ) ? $element->country : null;
        $fields['resort'] = isset( $element->resort ) ? $element->resort : null;
        $fields['category'] = isset( $element->category ) ? $element->category : null;
        $fields['category_name'] = isset( $element->category_name ) ? $element->category_name : null;

        return $fields;
    }

    /**
     * Sets "f_deleted = 1" for nonexistent hotels
     * @param int $oid
     * @param array $elements
     */
    protected function deleteAllNotInElements($oid, $elements){
        $db = Yii::app()->db;

//        // Deletes nonexistent hotels with directory_id = 0
//        $db->createCommand()->delete(
//            '{{operator_hotels}}',
//            ['AND', 'operator_id = :oid', 'directory_id = 0', ['NOT IN', 'element_id', $elements]],
//            [':oid' => $oid]
//        );

        // Updates nonexistent hotels with directory_id = 0
        $db->createCommand()->update(
            '{{operator_hotels}}',
            ['f_deleted' => 1],
            ['AND', 'operator_id = :oid', ['NOT IN', 'element_id', $elements]],
            [':oid' => $oid]
        );
    }

}