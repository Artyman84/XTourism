<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 18.01.2016
 * Time: 16:23
 */

namespace TSearch\tbl\operators;
use Yii;
use TSearch\TOperator;

class Resorts extends \TSearch\tbl\Operator {

    protected function __construct(){
        parent::__construct('resorts');
    }

    /**
     * Updates data table of operators
     * @param mixed $operators
     */
    public function updateData($operators){
        parent::updateData($operators);

        $operators = array_keys(TOperator::operatorsInfo($operators));
        $db = Yii::app()->db;

        // Собираем информацию о ненужных временных курортах, которые не скрещены, удалены и просмотрены
        $unnecessary_resorts = $db->createCommand()
            ->select('id, element_id, operator_id')
            ->from('{{operator_resorts}}')
            ->where(['AND', ['IN', 'operator_id', $operators], 'f_deleted = 1', 'unread = 0', 'directory_id = 0'])
            ->setFetchMode(\PDO::FETCH_OBJ)
            ->queryAll();

        // Удаляем все отели ненужных курортов
        $unnecessary_resorts_ids = [];
        foreach ($unnecessary_resorts as $resort){
            $db->createCommand()->delete('{{operator_hotels}}', ['AND', 'operator_id = :oid', 'resort = :resort'], [':oid' => $resort->operator_id, ':resort' => $resort->element_id]);
            $unnecessary_resorts_ids[] = $resort->id;
        }

        // Удаляем ненужные курорты
        $db->createCommand()->delete('{{operator_resorts}}', ['IN', 'id', $unnecessary_resorts_ids]);
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

        return $fields;
    }

    /**
     * Sets "f_deleted = 1" for nonexistent resorts
     * @param int $oid
     * @param array $elements
     */
    protected function deleteAllNotInElements($oid, $elements){
        $db = Yii::app()->db;

//        // Deletes nonexistent resorts with directory_id = 0
//        $db->createCommand()->delete(
//            '{{operator_resorts}}',
//            ['AND', 'operator_id = :oid', 'directory_id = 0', ['NOT IN', 'element_id', $elements]],
//            [':oid' => $oid]
//        );

        // Updates nonexistent resorts with directory_id = 0
        $db->createCommand()->update(
            '{{operator_resorts}}',
            ['f_deleted' => 1],
            ['AND', 'operator_id = :oid', ['NOT IN', 'element_id', $elements]],
            [':oid' => $oid]
        );
    }

}