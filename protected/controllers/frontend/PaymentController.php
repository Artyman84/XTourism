<?php

/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 27.10.2017
 * Time: 13:19
 */
class PaymentController extends FrontendController {

    const KEY = '!Das#@;ZvbnxoP~s/.cZXc-s=a+0&8';

    public function filters() {
        return ['postOnly'];
    }

    public function actionIndex(){

        $data = Yii::app()->request->getPost('data');
        $hash = Yii::app()->request->getPost('hash');

        if( password_verify(self::KEY . $data, $hash) ) {
            $this->createTourAgent(CJSON::decode($data));
        }
    }


    /**
     * Create tour agent and package
     * @param array $data
     */
    private function createTourAgent($data){
        if( !empty($data['products']) && !empty($data['email']) && !empty($data['firstname']) &&
            !empty($data['lastname']) && !empty($data['order_id']) && !empty($data['currency']) &&
            !empty($data['total']) && isset($data['comment']) ){

            // Проверяем валидные ли типы продуктов пришли к нам с магазина
            $product_types = ArShopProductsTypes::model()->findAll();
            $product_types_ids = [];
            foreach ($product_types as $product_type){
                if($product_type->id != ArShopProductsTypes::PDT_EXTERNAL && in_array($product_type->id, $data['products'])) {
                    $product_types_ids[] = $product_type->id;
                }
            }

            if( !empty($product_types_ids) ){

                $user = ArUsers::model()->findByAttributes(['email' => $data['email']]);

                // Если турагента нет - создаем его.
                if( !$user ){
                    $password = Yii::app()->getSecurityManager()->generateRandomString(16, true);
                    $city = ArDirDepCities::model()->findByAttributes(['name' => 'Москва']);

                    $user = new ArUsers();
                    $user->attributes = [
                        'name' => $data['firstname'],
                        'lastname' => $data['lastname'],
                        'password' => CPasswordHelper::hashPassword($password),
                        'city_id' => $city ? $city->id : 0,
                        'email' => $data['email'],
                        'phone' => '',
                        'company' => '',
                        'role' => 'agent',
                        'state' => 0
                    ];

                    // Создаем нового турагента
                    if( $user->save() ) {

                        // Уведомляем агента о том, что на сайте для него создана учетная запись
                        TNotify::notifyUserAboutCreatingAccount($user, $password);

                        // Название пакета создаем из названий продуктов
                        $products = ArShopProducts::model()->findAllByAttributes(['type_id' => $product_types_ids]);
                        $products_names = [];
                        foreach ($products as $product) {
                            $products_names[] = $product->name;
                        }

                        $package = new ArShopUsersDraftPackages();
                        $package->attributes = [
                            'user_id' => $user->id,
                            'name' => implode(' + ', $products_names),
                            'start' => Yii::app()->dateFormatter->format('dd.MM.yyyy', strtotime('midnight')),
                            'expired' => Yii::app()->dateFormatter->format('dd.MM.yyyy', strtotime('midnight + 1 Year')),
                            'comment' => $data['comment'],
                            'price_uah' => $data['currency'] == 'UAH' ? (int)$data['total'] : '',
                            'price_rub' => $data['currency'] == 'RUB' ? (int)$data['total'] : ''
                        ];

                        // Создаем пакет и добавляем в него купленные продукты.
                        if( $package->save() ) {
                            $db = Yii::app()->db;
                            foreach( $products as $product ) {
                                $db->createcommand()->insert('{{shop_users_draft_products_to_packages}}', ['user_draft_package_id' => $package->id, 'product_id' => $product->id]);
                            }

                            // Активация пакета
                            $package->activatePackage();

                        } else {
                            echo 'Ошибка в создании пакета: <pre>'; var_dump($package->getErrors()); echo '<pre>';
                        }

                    } else {
                        echo 'Ошибка в создании турагента: <pre>'; var_dump($user->getErrors()); echo '<pre>';
                    }

                } else {
                    echo 'Пользователь с таким емейлом - ' . $data['email'] . ' уже существует.';
                }

                echo 'ok';

            } else {
                echo 'Неопределенный список продуктов: <pre>'; var_dump($product_types_ids); echo '</pre>';
            }

        } else {
            echo 'Не хватает данных: <pre>'; var_dump($data); echo '</pre>';
        }
    }

}