<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 04.07.14
 * Time: 12:21
 * To change this template use File | Settings | File Templates.
 */

namespace TSearch;

use CComponent;
use Yii;
use ArShopUsersDraftPackages;

set_time_limit(0);
ini_set('memory_limit', '-1');


class Cron extends CComponent{

    /**
     * __construct
     */
    public function __construct(){
    }

    /**
     * Updates operator's tables
     * @param array $operators
     * @param bool $reset
     */
    public function updateOperatorsData($operators=null, $reset=false){

        $operators = array_keys(TOperator::operatorsInfo(isset($operators) ? (array)$operators : null));
        if( !empty($operators) ) {

            $tables = [
                'countries', 'resorts', 'hotels', 'dep_cities',
                'hotel_categories', 'hotel_statuses', 'meals', 'ticket_statuses'
            ];

            if ($reset) {
                $hashes = [];
                foreach ($tables as $table) {
                    $hashes[$table . '_hash'] = null;
                }

                Yii::app()->db->createCommand()->update('{{operators}}', $hashes, ['IN', 'id', $operators]);
            };

            foreach ($tables as $table) {
                tbl\Operator::table($table)->updateData($operators);
            }
        }
    }

    /**
     * Activates packages for agents
     */
    public function activatePackages() {
        $today = strtotime('midnight');
        $packages = Yii::app()->db->createCommand()
            ->select('id')
            ->from('{{shop_users_draft_packages}}')
            ->where('start <= :today', [':today' => $today])
            ->queryColumn();

        foreach( $packages as $package ){
            ArShopUsersDraftPackages::model()->findByPk($package)->activatePackage();
        }
    }

    /**
     * Updates Tours of TourShowcase
     */
    public function updateShowcaseTours(){
        (new ShowcaseTour())->updateTours();
    }


//    /**
//     * Truncates operator tables
//     */
//    public function truncateOperatorTables(){
//        $tables = [
//            'countries', 'resorts', 'hotels', 'dep_cities',
//            'hotel_categories', 'hotel_statuses', 'meals', 'ticket_statuses', 'relations_dep_cities_countries'
//        ];
//
//        foreach( $tables as $table ) {
//            Yii::app()->db->createCommand()->truncateTable('{{operator_' . $table . '}}');
//        }
//
//
//        $tables = [
//            'countries', 'resorts', 'hotels', 'dep_cities',
//            'hotel_categories', 'hotel_statuses', 'meals', 'ticket_statuses'
//        ];
//
//        $hashes = [];
//        foreach( $tables as $table ){
//            $hashes[$table . '_hash'] = null;
//        }
//
//        Yii::app()->db->createCommand()->update('{{operators}}', $hashes, ['IN', 'id', 3]);
//
//    }

}