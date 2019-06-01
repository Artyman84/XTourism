<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 28.06.2016
 * Time: 12:23
 */

class FrontProductController extends FrontendController{

    public function filters() {
        return [];
    }


    public function actionIndex() {

        $p = Yii::app()->request->getQuery('p', null);
        $if_id = Yii::app()->request->getQuery('if_id', null);
        $cw = Yii::app()->request->getQuery('cw', null);

        if($p && $if_id) {

            if( ($params = CJSON::decode(TUtil::encrypt(TUtil::base64url_decode($p)))) ){

                if( !empty($params['uid']) && !empty($params['p']) ) {

                    $product = ucfirst($params['p']);
                    $uid = $params['uid'];
                    unset($params['p'], $params['uid']);

                    $this->redirect($this->createUrl('Front' . $product . '/index/', ['id' => $uid, 'if_id' => $if_id, 'cw' => $cw]));
                }
            }
        }

    }

}