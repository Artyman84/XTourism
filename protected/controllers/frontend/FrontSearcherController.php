<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 19.09.14
 * Time: 11:19
 * To change this template use File | Settings | File Templates.
 */

Yii::import('tsearch.searchers.*');
Yii::import('tsearch.hotel.*');
Yii::import('tsearch.searchers.widgets.*');

use TSearch\Searcher AS TSearcher;

class FrontSearcherController extends FrontendController{

    /**
     * @var string
     */
    public $layout = 'searcher';

    /**
     * Filters
     * @return array
     */
    public function filters(){
        return array(
            'ajaxOnly + changeDepCity, changeCountry, search, searchByHotel, tourInfo',
            'ACL + index, search, searchByHotel, changeCountry, changeDepCity, tourRequest, tourInfo',
            'TourParams + tourRequest, tourInfo',
            ['application.filters.XssFilter + tourRequest'],
        );
    }

    /**
     * Checks user's ACL for "Searcher"
     * @param $filterChain
     */
    public function filterACL($filterChain){
        $user_id = Yii::app()->request->getParam('id', 0);
        $if_id = Yii::app()->request->getQuery('if_id', null);
        $package = ArShopUsersPackages::model()->with('products')->findByAttributes(['user_id' => $user_id]);

        // TODO: Раскоментировать когда заработает поиск туров!!!
//        if($package && $package->hasProduct(ArShopProductsTypes::PDT_SEARCHER)) {
        if($package && ($package->hasProduct(ArShopProductsTypes::PDT_TOUR_SHOWCASE) || $package->hasProduct(ArShopProductsTypes::PDT_SEARCHER)) ) {
            if ( $package->isValid() ) {

                $filterChain->run();

            } else {

                if (Yii::app()->request->isAjaxRequest) {
                    echo 'expired';
                } else {
                    Yii::app()->controller->redirect(Yii::app()->createUrl(Yii::app()->controller->id . '/ExpiredAlert', ['iframeId' => $if_id]));
                }

            }

        } elseif(Yii::app()->request->isAjaxRequest){
            echo '403';
        }

    }

    /**
     * Checks tour request params
     * @param $filterChain
     */
    public function filterTourParams($filterChain){
        $p = Yii::app()->request->getQuery('p', null);
        $data = $this->decodeTourParams($p);

        if( $data ){
            $filterChain->run();
        } else {

            if (Yii::app()->request->isAjaxRequest) {
                echo 'bad_params';
            } else {
                $this->badTourInfo();
            }

        }
    }

    /**
     * Actions
     * @return array
     */
    public function actions(){
        return [
            'changeDepCity' => [
                'class' => 'actions.changeDepCity.changeDepCity',
                'user_id' => Yii::app()->request->getQuery('id'),
                'dirDepCity' => Yii::app()->request->getPost('dirDepCity'),
                'dirCountry' => Yii::app()->request->getPost('dirCountry')
            ],
            'changeCountry' => [
                'class' => 'actions.changeCountry.changeCountry',
                'user_id' => Yii::app()->request->getQuery('id'),
                'dirDepCity' => Yii::app()->request->getPost('dirDepCity'),
                'dirCountry' => Yii::app()->request->getPost('dirCountry')
            ],
            'searchByHotel' => [
                'class' => 'actions.searchByHotel.searchByHotel',
                'hotel_id' => Yii::app()->request->getQuery('hid'),
                'params' => Yii::app()->request->getPost('params')
            ]
        ];
    }


    /**
     * Action "Expired Product Alert"
     */
    public function actionExpiredAlert(){
        $params = ['class' => 'warning', 'iframeId' => Yii::app()->request->getParam('iframeId', 0)];
        $this->decorator('iframe_alert', '<span class="glyphicon glyphicon-warning-sign"></span> Поиск туров временно недоступен', $params);
    }

    /**
     * Index
     * @param integer $id
     */
    public function actionIndex($id) {
        $settings = $this->userSettings($id);
        $searcher = new TSearcher($settings);
        $if_id = Yii::app()->request->getQuery('if_id', null);

        $this->render('index', ['settings' => $settings, 'searcher' => $searcher, 'if_id' => $if_id, 'uid' => $id]);
    }

    /**
     * Action "Search"
     */
    public function actionSearch(){
        $params = (array)Yii::app()->getRequest()->getPost('params', []);
        (new \TSearch\SearchTour($params))->loadTours(1);
    }

//    /**
//     * Action "Tour Info"
//     */
//    public function actionTourInfo() {
//        $p = Yii::app()->request->getParam('p', null);
//        $data = $this->decodeTourParams($p);
//
//        $info = null;
//        if( $operator = TSearch\TOperator::newOperator($data['oid']) ) {
//            $tour = $operator->load('tour_info', ['id' => $data['tid']]);
//
//            if (!empty($tour) ) {
//
//                $tour = (object)$tour[0];
//
//                // Проверка на соответствие отеля из тура с отелем из справочника
//                if( $this->isHotelExists($data['oid'], $tour->hotelId, $data['hid']) ) {
//                    $info = [];
//                }
//
//            }
//        }
//
//        echo CJSON::encode($info);
//    }

    /**
     * Action "Tour Request"
     */
    public function actionTourRequest() {

        $p = Yii::app()->request->getQuery('p', null);
        $uid = Yii::app()->request->getParam('id', 0);

        $data = $this->decodeTourParams($p);
        if( isset($_POST['RequestForm']) ){

            $model = new RequestForm;
            $model->attributes = $_POST['RequestForm'];
            $message = parent::flashMessage('danger', "<strong>Во время подачи заявки возникли неполадки. Пожалуйста, обновите страницу и попробуйте сделать заказ еще раз.</strong>", false);

            if( $model->validate() ) {
                $extra = ['agent' => $uid, 'tid' => $data['tid'], 'oid' => $data['oid'], 'hid' => $data['hid']];

                $has_request_insert = Yii::app()->db->createCommand()->insert('{{clients_tours_requests}}', [
                    'agent_id' => $uid,
                    'created_at' => time(),
                    'client_name' => $model->name,
                    'client_phone' => $model->phone,
                    'client_email' => $model->email,
                    'client_comment' => $model->comment,
                    'client_IP' => $_SERVER["REMOTE_ADDR"],
                    'product_type' => ArShopProductsTypes::PDT_SEARCHER,
                    'tour_id' => $data['tid'],
                    'operator_id' => $data['oid'],
                    'hotel_id' => $data['hid'],
                    'dep_city_id' => $data['cid'],
                    'meal_id' => $data['mid'],
                    'start_date' => $data['date'],
                    'nights' => $data['ngt'],
                    'room' => $data['rm'],
                    'price' => $data['prc'],
                    'currency' => $data['cur'],
                    'adults' => $data['ad'],
                    'kids' => $data['ch'],
                    'state' => 2,
                ]);

                if( $has_request_insert ) {
                    TNotify::notifyAgentAboutSearcherTourRequest($model, $extra);
                    $message = parent::flashMessage('success', "<strong>Ваш заказ был успешно принят в обработку! Наш менеджер свяжется с Вами в ближайшее время.</strong>", false);
                }
            }

            echo CJSON::encode(array('message' => $message));
            Yii::app()->end();
        }

        $this->layout = 'tourInfo';

        $this->render('tourInfo', [
            'request_params' => $p,
            'uid' => $uid,
            'data' => $data,
            'settings' => $this->userSettings($uid)
        ]);

    }




    /******************** PROTECTED FUNCTIONS ********************/


//    /**
//     * Checks if exists hotel
//     * @param integer $oid
//     * @param string $element_id
//     * @param integer $directory_id
//     * @return mixed
//     */
//    protected function isHotelExists($oid, $element_id, $directory_id){
//        return Yii::app()->db->createCommand()
//            ->select('id')
//            ->from('{{operator_hotels}}')
//            ->where(
//                ['AND', 'operator_id = :oid', 'element_id = :element_id', 'directory_id = :directory_id'],
//                [':oid' => $oid, ':element_id' => $element_id, ':directory_id' => $directory_id]
//            )
//            ->queryScalar();
//    }

    /**
     * Returns user's settings
     * @param $uid integer
     * @return SearcherStandardSettings
     */
    protected function userSettings($uid){
        $model = ArUserSearcher::model()->findByAttributes(['user_id' => $uid]);
        return $model->searcherSettings();
    }

    /**
     * Action "Expired Product Alert"
     */
    protected function badTourInfo() {
        $this->decorator('alert', '<strong><span class="glyphicon glyphicon-warning-sign"></span> Информация о данном туре временно недоступна.</strong>', ['class' => 'warning']);
        Yii::app()->end();
    }

    /** Returns decoded tour params
     * @param string $p
     * @return mixed|string
     */
    protected function decodeTourParams($p){
        $data = TUtil::encrypt(TUtil::base64url_decode($p));
        $data = CJavaScript::jsonDecode($data);

        return $data;
    }

}