<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 23.04.2016
 * Time: 23:09
 */

class PackagesController extends FrontendController {


    public function actionUserPackage() {
        $user_id = Yii::app()->user->getId();
        $userPackage = ArShopUsersPackages::model()->findByAttributes(['user_id' => $user_id]);
        $userInvoice = ArShopInvoices::activeInvoice($user_id);

        $this->render('userPackage', ['userPackage' => $userPackage, 'userInvoice' => $userInvoice]);
    }

}