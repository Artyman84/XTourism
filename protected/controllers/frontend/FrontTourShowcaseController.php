<?php
/**
 * Created by PhpStorm.
 * User: Arti
 * Date: 09.04.2015
 * Time: 9:51
 */

class FrontTourShowcaseController extends FrontendController{

    const ROWS_OF_TOURS = 2;

    /**
     * @var ArShopUsersPackages
     */
    private $package;

    /**
     * @var int
     */
    private $midnight;

    /**
     * @var string
     */
    public $layout = 'showcase';

    /**
     * @return array
     */
    public function filters(){
        return array(
            'ajaxOnly + searchByHotel',
            'ACL + index, tourInfo, searchByHotel',
            ['application.filters.XssFilter + tourInfo'],
        );
    }

    /**
     * Actions
     * @return array
     */
    public function actions(){
        return [
            'searchByHotel' => [
                'class' => 'actions.searchByHotel.searchByHotel',
                'hotel_id' => Yii::app()->request->getQuery('hid'),
                'params' => Yii::app()->request->getPost('params')
            ]
        ];
    }

    public function __construct($id){

        parent::__construct($id);

        $user_id = Yii::app()->request->getParam('id', 0);
        $this->package = ArShopUsersPackages::model()->with('products')->findByAttributes(['user_id' => $user_id]);
        $this->midnight = strtotime('midnight');
    }

    /**
     * Checks user's ACL for "Fire Showcase"
     * @param $filterChain
     */
    public function filterACL($filterChain){
        $if_id = Yii::app()->request->getQuery('if_id', null);

        if($this->package && $this->package->hasProduct(ArShopProductsTypes::PDT_TOUR_SHOWCASE)) {
            if ($this->package->isValid()) {

                $filterChain->run();

            } else {

                if (Yii::app()->request->isAjaxRequest) {
                    echo 'expired';
                } else {
                    Yii::app()->controller->redirect(Yii::app()->createUrl(Yii::app()->controller->id . '/ExpiredAlert', ['iframeId' => $if_id]));
                }

            }

        } elseif(Yii::app()->request->isAjaxRequest) {
            echo '403';
        }

    }

    /**
     * Action "Expired Product Alert"
     */
    public function actionExpiredAlert(){
        $params = ['class' => 'warning', 'iframeId' => Yii::app()->request->getParam('iframeId', 0)];
        $this->decorator('iframe_alert', '<span class="glyphicon glyphicon-warning-sign"></span> Витрина туров временно недоступна', $params);
    }


    /**
     * Shows showcase of tours
     * @param integer $id
     * @throws CHttpException
     */
    public function actionIndex($id){

        $page = (int)Yii::app()->request->getParam('page', 1);
        $userShowcase = ArUserTourShowcase::model()->findByAttributes(['user_id' => $id]);

        if( isset($_GET['TourShowcaseStandardSettings']) ) {
            $settings = $_GET['TourShowcaseStandardSettings'];
        } else {
            $settings = $userShowcase->settings;
        }

        $settings['rows'] = empty($settings['rows']) ? 2 : $settings['rows'];

        // Эта ветка работает всегда.
        // Убрать true, если вернется параметр per_row
        // TODO: Удалить полностью per_row из проекта
        if( empty($settings['per_row']) || true ){
            $cw = Yii::app()->request->getParam('cw', null);
            $per_page = $this->perPage($cw, $page == 1 ? $settings['rows'] : 2);
        } else {
            $per_page = $settings['per_row']*self::ROWS_OF_TOURS;
        }

        $condition = ['AND', 't.dc_dir_id = :cid', 't.start_date > ' . $this->midnight];
        $params = [':cid' => $userShowcase->dc_dir_id];

        $showcase = new \TSearch\ShowcaseTour();
        $resorts = [];


        /************************ Default values ************************/

        $country = Yii::app()->request->getParam('cn', $userShowcase->settings['country']);
        $resort = Yii::app()->request->getParam('r', $userShowcase->settings['resort']);
        $category = Yii::app()->request->getParam('ct', $userShowcase->settings['category']);

        // Если есть фильтр с странами и "страна по умолчанию" не в этом фильтре, тогда не надо выбирать туры и курорты по это стране
        if( $country && ( !empty($userShowcase->settings['countries']) && in_array($country, $userShowcase->settings['countries']) || empty($userShowcase->settings['countries']) ) ){
            $condition[] = 'h.dir_country_id = :country';
            $params[':country'] = $country;
            $resorts = $showcase->resortsList($userShowcase->dc_dir_id, $country, false);
        }

        if( $resort ){
            $db = Yii::app()->db;
            $is_combined = $db->createCommand()->select('is_combined')->from('{{directory_resorts}}')->where('id = :id', [':id' => $resort])->queryScalar();

            if( $is_combined ){
                $resort = $db->createCommand()->select('id')->from('{{directory_resorts}}')->where('parent_id = :id', [':id' => $resort])->queryColumn();
                $condition[] = ['IN', 'h.dir_resort_id', $resort];
            } else {
                $condition[] = 'h.dir_resort_id = :resort';
                $params[':resort'] = $resort;
            }
        }

        if( $category ){
            $condition[] = 'h.dir_category_id = :category';
            $params[':category'] = $category;
        }



        /************************ Filters ************************/

        if( !empty($userShowcase->settings['operators']) ){
            $condition[] = ['IN', 't.o_id', $userShowcase->settings['operators']];
        }

        if( !empty($userShowcase->settings['countries']) ){
            $condition[] = ['IN', 'h.dir_country_id', $userShowcase->settings['countries']];
        }

        /************************ Filters ************************/
        $offset = 0;
        if($page > 1){
            // Если приходят новые страницы( page > 1), тогда формируем offset на основании того, сколько первоначальных( page = 1) рядов с турами указал агент.
            // Высчитываем offset первй страницы и прибовляем к нему последующее смещение
            $offset = $this->perPage($cw, $settings['rows']) + ($page - 2) * $per_page;
        }

        $tours = $this->listOfTours($condition, $params, $per_page, $offset);

        $totalCount = null;
        if( $page == 1 ){
            $totalCount = Yii::app()->db->createCommand('SELECT FOUND_ROWS()')->queryScalar();
        }

        $this->changeChildrenResorts($tours);
        if( Yii::app()->request->isAjaxRequest) {

            if( Yii::app()->request->getParam('with_resorts', false) && $country ){
                $resorts = $showcase->resortsList($userShowcase->dc_dir_id, $country, false);
            }

            echo CJSON::encode([
                'tours' => $this->renderTours($tours, $id, $settings, true),
//                'count' => $page*$per_page,
                'count' => $offset + $per_page,
                'resorts' => $resorts,
                'totalCount' => $totalCount
            ]);

        } else {

            $this->addJsFile();
            $if_id = Yii::app()->request->getQuery('if_id', null);

            $this->render('index', [
                'iframe_id' => $if_id,
                'settings' => $settings,
                'tours' => $tours,
                'totalCount' => $totalCount,
                'count' => $offset + $per_page,
                'countries' => $showcase->countriesList($userShowcase->dc_dir_id, $userShowcase->settings['countries'], false),
                'resorts' => $resorts,
                'categories' => $showcase->categoriesList($userShowcase->dc_dir_id, false),
                'uid' => $id,
            ]);
        }
    }


    /**
     * Shows tour info
     * @throws CHttpException
     */
    public function actionTourInfo(){
        Yii::import('tsearch.hotel.*');
        $this->layout = 'tourInfo';

        $id = Yii::app()->request->getParam('id', 0);
        $tid = Yii::app()->request->getParam('tid', 0);

        $tour = ArTourShowcaseTours::model()->with([
            'operator',
            'city',
            'meal',
            'hotel' => [
                'with' => [
                    'cards',
                    'ratings',
                    'residence',
                    'services',
                    'category',
                    'country',
                    'resort',
                    'photos'
                ]
        ]])->findByPk($tid, 't.start_date > ' . $this->midnight);

        $showcase_currency = ArUserTourShowcase::model()->findByAttributes(['user_id' => $id])->settings['currency'];

        if( isset($_POST['RequestForm']) ){

            $model = new RequestForm;
            $model->attributes = $_POST['RequestForm'];
            $message = parent::flashMessage('danger', "<strong>Во время подачи заявки возникли неполадки. Пожалуйста, обновите страницу и попробуйте сделать заказ еще раз.</strong>", false);

            if( $model->validate() && $tour ) {

                $has_request_insert = Yii::app()->db->createCommand()->insert('{{clients_tours_requests}}', [
                    'agent_id' => $id,
                    'created_at' => time(),
                    'client_name' => $model->name,
                    'client_phone' => $model->phone,
                    'client_email' => $model->email,
                    'client_comment' => $model->comment,
                    'client_IP' => $_SERVER["REMOTE_ADDR"],
                    'product_type' => ArShopProductsTypes::PDT_TOUR_SHOWCASE,
                    'tour_id' => $tid,
                    'operator_id' => $tour->o_id,
                    'hotel_id' => $tour->h_dir_id,
                    'dep_city_id' => $tour->dc_dir_id,
                    'meal_id' => $tour->m_dir_id,
                    'start_date' => $tour->start_date,
                    'nights' => $tour->nights,
                    'room' => $tour->room,
                    'price' => $showcase_currency ? $tour->price_rur : $tour->price,
                    'currency' => $showcase_currency ? 'RUB' : $tour->currency,
                    'adults' => $tour->adults,
                    'kids' => $tour->kids,
                    'state' => 2,
                ]);

                if( $has_request_insert ) {
                    TNotify::notifyAgentAboutShowcaseTourRequest($id, $tid, $model);
                    $message = parent::flashMessage('success', "<strong>Ваш заказ был успешно принят в обработку! Наш менеджер свяжется с Вами в ближайшее время.</strong>", false);
                }

            }

            echo CJSON::encode(array('message' => $message));
            Yii::app()->end();
        }

        if( !$tour || !$tour->hotel ){
            $this->decorator('alert', '<strong><span class="glyphicon glyphicon-warning-sign"></span>  Тура не существует, либо информация о данном туре временно недоступна.</strong>', ['class' => 'warning']);
            Yii::app()->end();
        }

        $this->render('tourInfo', ['tour' => $tour, 'uid' => $id, 'showcase_currency' => $showcase_currency]);
    }




    /*********** Protected ***********/

    /**
     * Returns tours data
     * @param array $condition
     * @param array $params
     * @param int $per_page
     * @param int $offset
     * @return array
     */
    protected function listOfTours($condition, $params, $per_page, $offset){

        // TODO: Подумать над тем, как не вытаскивать одинаковые отели разных ТО(или же оставить как есть)
        $tours = Yii::app()->db->createCommand()
            ->select('SQL_CALC_FOUND_ROWS(0), 
                t.*,
                h.name AS hotel_name,
                h.dir_country_id,
                h.dir_city_id,
                hc.name AS category_name,
                p.count AS img_count,
                c.name AS country_name,
                r.name AS resort_name,
                r.id AS resort_id,
                r.parent_id AS resort_parent_id,
                dc.name AS dep_city_name,
                m.name AS meal_name,
                m.description AS meal_description')
            ->from('{{' . \TSearch\ShowcaseTour::table() . '}} AS t')
            ->join('{{hotel_photos}} AS p', 'p.dir_hotel_id = t.h_dir_id')
            ->join('{{directory_dep_cities}} AS dc', 'dc.id = t.dc_dir_id')
            ->join('{{directory_meals}} AS m', 'm.id = t.m_dir_id')
            ->join('{{directory_hotels}} AS h', 'h.id = t.h_dir_id')
            ->join('{{directory_countries}} AS c', 'c.id = h.dir_country_id')
            ->join('{{directory_resorts}} AS r', 'r.id = h.dir_resort_id')
            ->join('{{directory_hotel_categories}} AS hc', 'hc.id = h.dir_category_id')
            ->where($condition, $params)
            ->group('t.id')
            ->order('t.price')
            ->limit($per_page, $offset)
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        return $tours;
    }

    /**
     * Change children resorts on parents
     * @param array $tours
     */
    private function changeChildrenResorts(&$tours){
        $combined = [];
        foreach($tours as $tour){
            if( $tour->resort_parent_id ){
                $combined[] = $tour->resort_parent_id;
            }
        }

        if( isset($combined[0]) ){
            $resorts = Yii::app()->db->createCommand()
                ->select('id, name')
                ->from('{{directory_resorts}}')
                ->where(['IN', 'id', array_values($combined)])
                ->setFetchMode(PDO::FETCH_OBJ)
                ->queryAll();

            $resorts = TUtil::listKey($resorts);

            foreach($tours as &$tour){
                if(isset($resorts[$tour->resort_parent_id])){
                    $tour->resort_name = $resorts[$tour->resort_parent_id]->name;
                }
            }
        }
    }

    /**
     * Renders tours
     * @param array $tours
     * @param int $uid
     * @param array $settings
     * @param bool $return
     * @return string
     * @throws CException
     */
    protected function renderTours($tours, $uid, $settings, $return=false){
        $ret = '';
        foreach ($tours as $tour) {
            $ret .= $this->renderPartial('tour', ['tour' => $tour, 'uid' => $uid, 'settings' => $settings], true);
        }

        if( $return ){
            return $ret;
        } else {
            echo $ret;
        }
    }

    /**
     * Returns price label
     * @param ArTourShowcaseTours $tour
     * @param array $settings
     * @param bool $small
     * @return string
     */
    protected function priceLabel($tour, $settings, $small=true){

        // По умолчанию берем цену и валюту страны
        $price = $tour->price;
        $currency = $tour->currency;

        // Берем цену и валюту в рублях
        if( $settings['currency'] ){
            $price = $tour->price_rur;
            $currency = 'RUB';
        }

        $sm = $small ? '-sm' : '';
        $smPrice = '<div class="tour-price' . $sm . ' t-price-label" style="background-color: ' . $settings['label_color'] . '">
                        <strong style="color:' . $settings['price_color'] . '" class="t-price">' . TSearch\TourHelper::normalizePrice($price) . '</strong>
                        <span class="glyphicon glyphicon-' . \TSearch\TourHelper::normalizeCurrency($currency) . ' t-price" style="color:' . $settings['price_color'] . '"></span>
                    </div>';

        return $smPrice;
    }


    /**
     * Returns classes of tour's block
     * @param int $perRow
     * @return string
     *
     * TODO: remove!!!
     */
    protected function tourBlockClasses($perRow){
        switch($perRow){
            case 0: default: return 'col-lg-3 col-md-4 col-sm-4 col-xs-6 t-blockTour';
//            case 2: return 'col-lg-6 col-md-6 col-sm-6 col-xs-6 t-blockTour';
//            case 3: return 'col-lg-4 col-md-4 col-sm-4 col-xs-4 t-blockTour';
//            case 4: return 'col-lg-3 col-md-3 col-sm-3 col-xs-3 t-blockTour';
        }
    }

    /**
     * @param integer $adults
     * @param integer $children
     * @param string|null $color
     * @return string
     */
    protected function residence($adults, $children, $color=null){
        $color = $color == null ? '' : 'style="color: ' . $color . '"';
        $residence = '<i class="glyphicon glyphicon-user text-muted i-margin t-icon" title="Взрослых" ' . $color . ' ></i> ';

        switch( (int)$adults ){
            case 1: $residence .= '1 взрослый'; break;
            case 2: $residence .= '2 взрослых'; break;
            case 3: $residence .= '3 взрослых'; break;
            case 4: $residence .= '4 взрослых'; break;
        }

        if( $children ) {

            $residence .= ' + <i class="fa fa-child text-muted i-margin t-icon" title="Детей" ' . $color . '></i> ';

            switch ((int)$children) {
                case 1: $residence .= '1 ребёнок'; break;
                case 2: $residence .= '2 ребёнка'; break;
                case 3: $residence .= '3 ребёнка'; break;
                case 4: $residence .= '4 детей'; break;
                case 5: $residence .= '5 детей'; break;
            }
        }

        return $residence;
    }

    /**
     * Returns limit of tours
     * @param int $width
     * @param int $rows
     * @return int
     */
    protected function perPage($width, $rows){
        $columns = 3;

        if( $width ) {
            // xc
            if ($width < 768) {
                $columns = 2;

                // sm
            } elseif ($width < 992) {
                $columns = 3;

                // md
            } elseif ($width < 1200) {
                $columns = 3;

                // lg
            } else {
                $columns = 4;
            }
        }

        return $columns*$rows;

    }
}