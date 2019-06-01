<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 21.08.14
 * Time: 11:37
 * To change this template use File | Settings | File Templates.
 */

use TSearch\tbl\Directory as tbl_directory;

class MigrationController extends BackendController{

    /**
     * Права доступа
     */
    public function accessRules() {
        return array(
            array(
                'allow',
                'roles' => array('superadmin'),
            ),

            // запрещаем все остальное
            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }

    /****************************** Actions Methods *********************************/

    public function actionIndex(){

        $this->addJsFile();
        Yii::app()->clientScript->registerPackage('scrollTo');

        $operators = ArOperators::model()->findAll(['order' => 'position, name']);
        $oid = Yii::app()->getRequest()->getParam('oid', $operators[0]->id);

        if( empty($operators) ){
            $this->redirect(Yii::app()->createUrl('operator/index'));
        }

        $freeElements = ArOperators::operatorFreeElements();
        $tables = ArDirectorySearch::getTableNames();
        unset($tables['cities']);

        $data = [
            'migrationID' => uniqid(),
            'oid' => $oid,
            'operators' => $operators,
            'freeElements' => $freeElements,
            'tabId' => Yii::app()->getRequest()->getParam('tab', 0),
            'tables' => $tables,
            'data' => $this->collectOperatorData($oid),
            'dir_hotel_categories' => CJSON::encode(tbl_directory::loadData('hotel_categories', null, false))
        ];

        $this->render('index', $data);
    }

    public function actionLoadOperatorData(){
        $id = Yii::app()->getRequest()->getPost('id');
        $update = Yii::app()->getRequest()->getPost('update', false);

        if( $update ) {
            $cron = new TSearch\Cron();
            $cron->updateOperatorsData($id, false);
        }

        $country = Yii::app()->getRequest()->getPost('country', null);
        $resort = Yii::app()->getRequest()->getPost('resort', null);
        $forbidden = (array)Yii::app()->getRequest()->getParam('forbidden', []);

        $data = $this->collectOperatorData($id, $forbidden, $country, $resort);
        $json = $tables = [];

        if( isset( $data['dep_cities'] ) ){
            $json['loadDepCities'] = $this->renderOperatorElements($data['dep_cities'], 'dep_cities');
        }

        if( isset( $data['countries'] ) ){
            $json['loadCountries'] = $this->renderOperatorElements($data['countries'], 'countries');
        }

        if( isset( $data['resorts'] ) ){
            $json['loadResorts'] = $this->renderOperatorElements($data['resorts'], 'resorts');
        }

        if( isset( $data['directory_resorts'] ) ){
            $json['loadDirResorts'] = $this->renderDirectoryElements('resorts', $data['directory_resorts']);
        }

        if( isset( $data['directory_hotels'] ) ){
            $json['loadDirHotels'] = $this->renderDirectoryElements('hotels', $data['directory_hotels'], isset( $data['hotels'] ) ? $data['hotels'] : []);
        }

        if( isset( $data['hotels'] ) ){
            $json['loadHotels'] = $this->renderOperatorElements($data['hotels'], 'hotels');
        }

        if( isset( $data['hotel_categories'] ) ){
            $json['loadHotelCategories'] = $this->renderOperatorElements($data['hotel_categories'], 'hotel_categories');
        }

        if( isset( $data['meals'] ) ){
            $json['loadMeals'] = $this->renderOperatorElements($data['meals'], 'meals');
        }

        if( isset( $data['hotel_statuses'] ) ){
            $json['loadHotelStatuses'] = $this->renderOperatorElements($data['hotel_statuses'], 'hotel_statuses');
        }

        if( isset( $data['ticket_statuses'] ) ){
            $json['loadTicketStatuses'] = $this->renderOperatorElements($data['ticket_statuses'], 'ticket_statuses');
        }

        if( isset( $data['comboCountries'] ) ){
            $json['comboCountries'] = $this->renderPartial('operators/comboCountries', array('countries' => $data['comboCountries'], 'selected' => $country), true);
        }

        if( isset( $data['comboResorts'] ) ){
            $json['comboResorts'] = $this->renderPartial('operators/comboResorts', array('resorts' => $data['comboResorts'], 'selected' => $resort), true);
        }

        if( isset( $data['statistic_by_operator'] ) ) {
            $json['statisticByOperator'] = $this->renderHotelStatistic($data['statistic_by_operator'], true);
        }

        if( isset( $data['statistic_by_country'] ) ) {
            $json['statisticByCountry'] = $this->renderHotelStatistic($data['statistic_by_country'], true);
        }

        if( isset( $data['statistic_by_resort'] ) ) {
            $json['statisticByResort'] = $this->renderHotelStatistic($data['statistic_by_resort'], true);
        }

        $json['unreadElements'] = $data['unread_elements'];

        echo json_encode($json);
    }

    public function actionLoadOperatorElements(){
        $operatorId = (int)Yii::app()->getRequest()->getParam('operatorId');
        $table = Yii::app()->getRequest()->getParam('table');
        $params = Yii::app()->getRequest()->getParam('params', null);
        $related = Yii::app()->getRequest()->getParam('related', null);

        $elements = $this->getOperatorElements($operatorId, $table, $params, $related);
        echo json_encode(array('html' => $this->renderOperatorElements($elements, $table, $related)));
    }


    public function actionBlockDirectoryElement(){
        $icon = '';
        $id = Yii::app()->getRequest()->getParam('id');
        $table = Yii::app()->getRequest()->getParam('table');
        $modelName = $this->getModelName($table);
        $model = $modelName::model()->findByPk($id);

        if( $model ){
            $model->disabled = 1 - $model->disabled;
            $model->save();
            $icon = $model->disabled ? 'ok-circle' : 'ban-circle';
        }

        echo json_encode(['icon' => $icon]);
    }

    public function actionSetReadStatus(){
        $oid = Yii::app()->getRequest()->getParam('oid');
        $elements = Yii::app()->getRequest()->getParam('elements', []);
        $table = Yii::app()->getRequest()->getParam('table');

        $db = Yii::app()->db;
        $ret = [];
        foreach ($elements as $el_id => $status) {
            $el_id = str_replace('_', '', $el_id);

            $success = $db->createCommand()->update(
                '{{' . $table . '}}',
                ['unread' => $status],
                ['AND', 'operator_id = :oid', 'element_id = :eid'],
                [':oid' => $oid, ':eid' => $el_id]
            );

            if( $success ){
                $ret[] = $el_id;
            }
        }

        echo CJSON::encode($ret);
    }


    public function actionBindElements(){
        $elements = (array)Yii::app()->getRequest()->getParam('elements');
        $dirId = (int)Yii::app()->getRequest()->getParam('directoryId');
        $oid = (int)Yii::app()->getRequest()->getParam('operatorId');
        $table = Yii::app()->getRequest()->getParam('table');

        if( $dirId ){
            TSearch\BindData::inst($oid)->bindDirectly($table, $elements, $dirId);
        } else {
            TSearch\BindData::inst($oid)->bindComparing($table, null, $elements);
        }

        $elements = TSearch\tbl\Operator::table($table)->loadData($oid, ['element_id' => $elements, 'related' => 1]);
        $data = [];
        foreach ($elements as $element){
            $data[] = ['element_id' => $element->element_id, 'directory_id' => $element->directory_id];
        }
        echo CJSON::encode($data);
    }


    public function actionUnbindElement(){
        $operatorId = Yii::app()->getRequest()->getParam('operatorId');
        $elementId = Yii::app()->getRequest()->getParam('elementId');
        $table = Yii::app()->getRequest()->getParam('table');

        TSearch\BindData::inst($operatorId)->unbind($table, $elementId);
        $elements = TSearch\tbl\Operator::table($table)->loadData($operatorId, ['element_id' => $elementId, 'related' => 0]);

        echo CJSON::encode(count($elements));
    }



    public function actionFilterOperatorElements(){
        $operatorId = (int)Yii::app()->getRequest()->getParam('operatorId');
        $table = Yii::app()->getRequest()->getParam('table');
        $params = Yii::app()->getRequest()->getParam('params', null);
        $related = Yii::app()->getRequest()->getParam('related', null);


        $elements = $this->getOperatorElements($operatorId, $table, $params, $related);
        echo json_encode(array('html' => $this->renderOperatorElements($elements, $table, $related)));
    }


    public function actionSaveDirectoryHotel(){
        $id = Yii::app()->request->getPost('id', 0);
        $name = Yii::app()->request->getPost('name', '');
        $category_id = Yii::app()->request->getPost('category_id');
        $model = ArDirHotels::model()->findByPk($id);

        if( $model && $name && $category_id ){
            $model->name = $name;
            $model->dir_category_id = $category_id;
            echo (int)$model->save();
        }
    }



    /****************************** Protected Auxiliaries Methods *********************************/

    protected function getViewName($table, $ucfirst=false){
        $parts = explode('_', $table);
        $name = $ucfirst ? ucfirst($parts[0]) : $parts[0];

        if( isset($parts[1]) ){
            $name .= ucfirst($parts[1]);
        }

        return $name;
    }

    protected function getModelName($table){
        return 'ArDir' . str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
    }

    protected function getElementName($table){
        $modelName = $this->getModelName($table);
        $model = new $modelName();

        return $model->getAttributeLabel('name');
    }

    /**
     * Returns operator's hotels
     * @param int $oid
     * @param int $resort
     * @param int|int $related
     * @return array
     */
    protected function getOperatorHotels($oid, $resort, $related=null){
        return $this->getOperatorElements($oid, 'hotels', array('resort' => $resort), $related);
    }

    /**
     * Returns operator's resorts
     * @param int $oid
     * @param int $country
     * @param int|null $related
     * @return array
     */
    protected function getOperatorResorts($oid, $country, $related=null){
        return $this->getOperatorElements($oid, 'resorts', array('country' => $country), $related);
    }

    /**
     * Returns operator's hotel statuses
     * @param int $oid
     * @param int|null $related
     * @return array
     */
    protected function getOperatorHotelStatuses($oid, $related=null){
        return $this->getOperatorElements($oid, 'hotel_statuses', null, $related);
    }

    /**
     * Returns operator's ticket statuses
     * @param int $oid
     * @param int|null $related
     * @return array
     */
    protected function getOperatorTicketStatuses($oid, $related=null){
        return $this->getOperatorElements($oid, 'ticket_statuses', null, $related);
    }

    /**
     * Returns operator's hotel categories
     * @param int $oid
     * @param int|null $related
     * @return array
     */
    protected function getOperatorHotelCategories($oid, $related=null){
        return $this->getOperatorElements($oid, 'hotel_categories', null, $related);
    }

    /**
     * Returns operator's resorts
     * @param int $oid
     * @param int|null $related
     * @return array
     */
    protected function getOperatorMeals($oid, $related=null){
        return $this->getOperatorElements($oid, 'meals', null, $related);
    }

    /**
     * Returns operator's departure cities
     * @param int $oid
     * @param int|null $related
     * @return array
     */
    protected function getOperatorDepCities($oid, $related=null){
        return $this->getOperatorElements($oid, 'dep_cities', null, $related);
    }

    /**
     * Returns operator's countries
     * @param int $oid
     * @param int|null $related
     * @return array
     */
    protected function getOperatorCountries($oid, $related=null){
        return $this->getOperatorElements($oid, 'countries', null, $related);
    }

    /**
     * Return directory hotels
     * @param int $oid
     * @param int $resort
     * @return array
     */
    protected function getDirectoryHotels($oid, $resort){
        $resorts = TSearch\tbl\Operator::table('resorts')->loadData($oid, ['element_id' => $resort]);

        $hotels = [];
        if( !empty($resorts) ) {

            $db = Yii::app()->db;
            $dir_resort_id = (int)array_pop($resorts)->directory_id;
            $dirResorts = tbl_directory::loadData('resorts', ['id' => $dir_resort_id], false);

            if( !empty($dirResorts) ) {

                $hotels_cmd = $db->createCommand()
                    ->select('h.id, h.name, h.url, h.disabled, r.name AS resort_name, c.name AS category_name, c.id AS category_id')
                    ->from('{{directory_hotels}} AS h')
                    ->join('{{directory_resorts}} AS r', 'r.id = h.dir_resort_id')
                    ->join('{{directory_hotel_categories}} AS c', 'c.id = h.dir_category_id')
                    ->setFetchMode(PDO::FETCH_OBJ);


                if ($dirResorts[0]->is_combined) {
                    $children = $db->createCommand()->select('id')->from('{{directory_resorts}}')->where('parent_id = :id', [':id' => $dirResorts[0]->id])->queryColumn();
                    $hotels_cmd->where(['IN', 'dir_resort_id', $children]);
                } else {
                    $hotels_cmd->where('dir_resort_id = :id', [':id' => $dir_resort_id]);
                }

                $hotels = $hotels_cmd->order('name')->queryAll();
            }
        }

        return $hotels;
    }

    /**
     * Return directory resorts
     * @param int $oid
     * @param int $country
     * @return array
     */
    protected function getDirectoryResorts($oid, $country){
        $countries = TSearch\tbl\Operator::table('countries')->loadData($oid, ['element_id' => $country]);

        $resorts = [];
        if( !empty($countries) ){
            $resorts = $this->getDirectoryElements('resorts', 'dir_country_id = :cnt_id', [':cnt_id' => (int)array_pop($countries)->directory_id]);
        }

        return $resorts;
    }


    protected function collectOperatorData($id, $forbidden=[], $country=null, $resort=null){
        $data = [];
        $tables = [];

        if( !isset($forbidden['hotel_statuses']) ){
            $data['hotel_statuses'] = $this->getOperatorHotelStatuses($id);
            $tables['hotel_statuses'] = 1;
        }

        if( !isset($forbidden['ticket_statuses']) ){
            $data['ticket_statuses'] = $this->getOperatorTicketStatuses($id);
            $tables['ticket_statuses'] = 1;
        }

        if( !isset($forbidden['hotel_categories']) ){
            $data['hotel_categories'] = $this->getOperatorHotelCategories($id);
            $tables['hotel_categories'] = 1;
        }

        if( !isset($forbidden['meals']) ){
            $data['meals'] = $this->getOperatorMeals($id);
            $tables['meals'] = 1;
        }

        if( !isset($forbidden['dep_cities']) ){
            $data['dep_cities'] = $this->getOperatorDepCities($id);
            $tables['dep_cities'] = 1;
        }

        $countries = $this->getOperatorCountries($id);

        if( !isset($forbidden['comboCountries']) ){
            $data['comboCountries'] = $countries;
        }

        if( !isset($forbidden['countries']) ){
            $data['countries'] = $countries;
            $tables['countries'] = 1;
        }

        if( !$country ){
            $objCountry = current($countries);
            $country = $objCountry ? $objCountry->element_id : 0;
        }

        $resorts = $this->getOperatorResorts($id, $country);

        if( !isset($forbidden['comboResorts']) ){
            $data['comboResorts'] = $resorts;
        }

        if( !isset($forbidden['resorts']) ){
            $data['resorts'] = $resorts;
            $tables['resorts'] = $country;
        }

        if( !isset($forbidden['directory_resorts']) ){
            $data['directory_resorts'] = $this->getDirectoryResorts($id, $country);
        }

        if( !$resort ){
            $objResort = current($resorts);
            $resort = $objResort ? $objResort->element_id : 0;
        }

        if( !isset($forbidden['hotels']) ){
            $data['hotels'] = $this->getOperatorHotels($id, $resort);
            $tables['hotels'] = $resort;
        }

        $data['unread_elements'] = $this->unreadElements($id, $tables);

        if( !isset($forbidden['directory_hotels']) ){
            $data['directory_hotels'] = $this->getDirectoryHotels($id, $resort);
        }

        if( !isset($forbidden['statistic_by_operator']) ){
            $data['statistic_by_operator'] = $this->getStatisticDataByOperator($id);
        }

        if( !isset($forbidden['statistic_by_country']) ){
            $data['statistic_by_country'] = $this->getStatisticDataByCountry($id, $country);
        }

        if( !isset($forbidden['statistic_by_resort']) ){
            $data['statistic_by_resort'] = $this->getStatisticDataByResort($id, $resort);
        }

        return $data;
    }



    /**
     * Returns operator's elements
     * @param int $operatorId
     * @param string $table
     * @param null|array $params
     * @param null|int $related
     * @return array
     */
    protected function getOperatorElements($operatorId, $table, $params=null, $related=null){

        $params = (array)$params;
        if( $related !== null ){
            $params['related'] = $related;
        }

        return TSearch\tbl\Operator::table($table)->loadData($operatorId, $params);
    }

    /**
     * Returns elements of directory
     * @param string $table
     * @param array|string $condition
     * @param array $params
     * @return array
     */
    private function getDirectoryElements($table, $condition=[], $params=[]){

        $cmd = Yii::app()->db->createCommand();
        $cmd->select('*');
        $cmd->from('{{directory_' . $table . '}}');
        $cmd->where($condition, $params);
        $cmd->order('name');
        $cmd->setFetchMode(PDO::FETCH_OBJ);

        return $cmd->queryAll();

    }

    /**
     * Renders operator's elements
     * @param array $elements
     * @param string $table
     * @param null|bool $related
     * @param bool $return
     * @return mixed
     */
    protected function renderOperatorElements($elements, $table, $related=null, $return=true){
        return $this->renderPartial(
            'operators/operatorElements',
            array(
                'operatorElements' => $elements,
                'name' => $this->getElementName($table),
                'table' => $table,
                'related' => $related
            ),
            $return
        );
    }

    /**
     * Renders elements of directory
     * @param string $table
     * @param null|array $directoryElements
     * @param array $operatorElements
     * @param bool $return
     * @return mixed
     */
    protected function renderDirectoryElements($table, $directoryElements=null, $operatorElements=[], $return=true){

        if( null === $directoryElements ){
            $directoryElements = $this->getDirectoryElements($table);
        }

        $related_ids = [];
        foreach ($operatorElements as $operatorElement) {
            if( $operatorElement->directory_id ) {
                $related_ids[$operatorElement->directory_id] = 1;
            }
        }

        return $this->renderPartial(
            'directories/directoryElements',
            array(
                'table' => $table,
                'directoryElements' => $directoryElements,
                'related_ids' => $related_ids
            ),
            $return
        );
    }


    /**
     * Gets hotel statistic's data by operator
     * @param integer $oid
     * @return string
     */
    protected function getStatisticDataByOperator($oid){
        $data = Yii::app()->db->createCommand()
                ->select('COUNT(*) AS `total`, COUNT(IF(`h`.`directory_id` > 0, 1, null)) AS `related`')
                ->from('{{operator_hotels}} AS h')
                ->where('h.operator_id = :oid', [':oid' => $oid])
                ->queryRow();

        return $data;
    }

    /**
     * Gets hotel statistic's data by country
     * @param integer $oid
     * @param string $country
     * @return string
     */
    protected function getStatisticDataByCountry($oid, $country){
        $data = Yii::app()->db->createCommand()
                ->select('COUNT(*) AS `total`, COUNT(IF(`h`.`directory_id` > 0, 1, null)) AS `related`')
                ->from('{{operator_countries}} AS c')
                ->join('{{operator_resorts}} AS r', 'r.country = c.element_id AND r.operator_id = :oid')
                ->join('{{operator_hotels}} AS h', 'h.resort = r.element_id AND h.operator_id = :oid')
                ->where('c.operator_id = :oid AND c.element_id = :country', [':oid' => $oid, ':country' => $country])
                ->queryRow();

        return $data;
    }

    /**
     * Gets hotel statistic's data by resort
     * @param integer $oid
     * @param string $resort
     * @return string
     */
    protected function getStatisticDataByResort($oid, $resort){
        $data = Yii::app()->db->createCommand()
            ->select('COUNT(*) AS `total`, COUNT(IF(`h`.`directory_id` > 0, 1, null)) AS `related`')
            ->from('{{operator_hotels}} AS h')
            ->where('h.operator_id = :oid AND h.resort = :resort', [':oid' => $oid, ':resort' => $resort])
            ->queryRow();

        return $data;
    }


    /**
     * Renders hotels statistic
     * @param array $data
     * @param bool $return
     * @return string
     */
    protected function renderHotelStatistic($data, $return=false){
        if( !empty( $data ) && !empty($data['total']) ) {
            $percents = round($data['related'] / $data['total'] * 100);

            if ($percents < 50) {
                $class = 'text-danger';
            } elseif ($percents < 90) {
                $class = 'text-warning';
            } else {
                $class = 'text-success';
            }

            $htmlStatistic = "<span class='text-muted t-hotels-statistic-total'>{$data['total']}</span> / <span class='$class t-hotels-statistic-related' ><span>{$data['related']}</span> ($percents%)</span>";

            if( !$return ) {
                echo $htmlStatistic;
            } else {
                return $htmlStatistic;
            }
        }
    }



    /**
     * Renders unread elements
     * @param int $oid
     * @param array $tables
     * @return array
     */
    protected function unreadElements($oid, $tables=[]){
        $ret = [];
        $db = Yii::app()->db;
        foreach ($tables as $table => $v){
            $condition = ['AND', 'operator_id = :oid', 'unread = 1'];
            $params = [':oid' => $oid];

            if( $table == 'resorts' ){
                $condition[] = 'country = :c';
                $params[':c'] = $v;
            } elseif( $table == 'hotels' ){
                $condition[] = 'resort = :r';
                $params[':r'] = $v;
            }

            $ret[$table] = $db->createCommand()
                ->select('COUNT(*) AS c')
                ->from('{{operator_' . $table . '}}')
                ->where($condition, $params)
                ->queryScalar();
        }

        return $ret;
    }


}