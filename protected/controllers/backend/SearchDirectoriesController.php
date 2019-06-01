<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 30.09.2015
 * Time: 17:52
 */

class SearchDirectoriesController extends BackendController{

    /**
     * Filters
     * @return array
     */
    public function filters(){
        return [
            'accessControl',
            'accessCRUD + delete, edit',
            'ajaxOnly + regionsAndCitiesByCountry, regionsByCountry, resortsByCountry, enable, delete, checkCategories, deleteCheckingCategories',
            ['application.filters.XssFilter + edit', 'clean' => 'GET,POST,COOKIE'],
        ];
    }

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

    /**
     * @param $filterChain
     * @return bool
     */
    public function filterAccessCRUD($filterChain) {
        $view = Yii::app()->request->getParam('view');

        /*************** Access ***************/
        $access_granted = in_array($view, ['hotel_categories', 'hotel_statuses', 'meals', 'ticket_statuses', 'dep_cities', 'hotels', 'resorts']);
        $access_granted = $access_granted || ($view == 'countries' && $this->action->id == 'edit' && Yii::app()->request->getParam('id'));


        if( $access_granted ) {
            $filterChain->run();
        } else {
            http_response_code(403);
            echo 'Access Denied!';
        }

    }

    /**
     * Action "Index"
     * @throws CHttpException
     */
    public function actionIndex(){
        $view = Yii::app()->request->getParam('view');
        $model = $this->getModel($view, null, 'search');
        $model->unsetAttributes();  // clear any default values

        $modelName = get_class($model);
        if(isset($_GET[$modelName])) {
            $model->attributes = $_GET[$modelName];
        }

        $this->loadViewGrid($view, $model);
    }

    /**
     * Action "Edit"
     * @param null|int $id
     */
    public function actionEdit($id=null){
        $view = Yii::app()->request->getParam('view');
        $model = $this->getModel($view, $id);

        $modelName = get_class($model);
        if( isset($_POST[$modelName]) ){
            $model->attributes = $_POST[$modelName];
            $this->saveView($view, $model);
        }

        if( Yii::app()->request->isAjaxRequest ){
            echo CJavaScript::jsonEncode($model->getErrors());
            Yii::app()->end();
        } else {
            $this->loadViewEdit($view, $model);
        }

    }

    /**
     * Action "Enable|Disable elements"
     */
    public function actionEnable(){
        $view = Yii::app()->request->getParam('view');
        $model = $this->getModel($view);

        $ids = (array)Yii::app()->request->getParam('ids', array());
        $enable = (int)Yii::app()->request->getParam('enable');

        $model::model()->updateByPk($ids, array('disabled' => !$enable));
    }

    /**
     * Action "Deletes elements"
     */
    public function actionDelete(){

        $view = Yii::app()->request->getParam('view');
        $ids = (array)Yii::app()->request->getParam('ids', []);

        // For long deletion
        set_time_limit(0);

        /********** Более медленное удаление по AR **********/
        $_model = $this->getModel($view);
        $models = $_model::model()->findAllByAttributes(['id' => $ids]);

        $not_deleted_ids = [];
        $not_deleted_names = [];
        foreach( $models as $model ){

            if( !$model->delete() ){
                $not_deleted_ids[] = $model->id;
                $not_deleted_names[] = $model->name;
            }
        }

        echo CJSON::encode(['ids' => $not_deleted_ids, 'names' => $not_deleted_names]);
    }


    /**
     * Action "Divide resorts"
     * @param integer $id
     * @throws CHttpException
     */
    public function actionDivideResorts($id){
        $view = Yii::app()->request->getParam('view');
        $country_id = Yii::app()->request->getParam('country_id');
        $model = $this->getModel('resorts', $id);

        if( !$model->is_combined ) {
            throw new CHttpException(404, 'Выбранный курорт не является комбинированным.');
        }

        // Удалем комбинированный курорт
        $model->delete();

        $url = Yii::app()->createUrl('SearchDirectories/index', ['view' => $view, ArDirectorySearch::arDirModelName($view) . '[dir_country_id]' => $country_id]);
        $this->redirect($url);

    }


    public function actionCheckingCategories(){

        $model = new ArDirHotelsCheckingCategories('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['ArDirHotelsCheckingCategories'])) {
            $model->attributes = $_GET['ArDirHotelsCheckingCategories'];
        }

        $this->render('grid_checking_hotels', ['model' => $model, 'search' => $model->search(), 'categories' => $this->categoriesList()]);
    }


    public function actionCheckCategories(){
        $ids = (array)Yii::app()->request->getParam('ids', array());
        $enable = (int)Yii::app()->request->getParam('enable');

        ArDirHotelsCheckingCategories::model()->updateByPk($ids, array('checked' => $enable));
    }


    public function actionEditCheckingCategory(){

        $id = Yii::app()->request->getParam('id', 0);
        $model = ArDirHotelsCheckingCategories::model()->with(['hotel'])->findByPk($id);

        if($model === null) {
            throw new CHttpException(404, 'Запрашиваемая страница не существует.');
        }

        if( isset($_POST['ArDirHotelsCheckingCategories']) ){

            $model->hotel->dir_category_id = $_POST['ArDirHotelsCheckingCategories']['category_id'];
            $model->checked = $_POST['ArDirHotelsCheckingCategories']['checked'];

            if( $model->hotel->save() && $model->save() ){
                $url = Yii::app()->createUrl('SearchDirectories/checkingCategories') . '#blink=' . $model->dir_hotel_id;
                $this->redirect($url);
            }
        }

        $model->category_id = $model->hotel->dir_category_id;
        $this->render('edit_checking_hotel', ['model' => $model, 'categories' => $this->categoriesList()]);
    }

    public function actionDeleteCheckingCategories(){
        $ids = (array)Yii::app()->request->getParam('ids', array());

        ArDirHotelsCheckingCategories::model()->deleteAllByAttributes(array('dir_hotel_id' => $ids));
    }

    /**
     * Action "Regions and cities by country"
     * @param int $id
     */
    public function actionRegionsAndCitiesByCountry($id){
        $resort = Yii::app()->request->getParam('resort', 0);
        if( !$resort ){
            $model = new ArDirResorts();
            $model->dir_country_id = $id;
        } else {
            $model = ArDirResorts::model()->findByPk($resort);
        }

        list($uncombinedResorts, $resorts_regions) = $this->uncombinedResortsAndRegions($model);

        echo '{
                "regions": ' . CJSON::encode($this->regionsList($id, true)) . ', 
                "uncombinedResorts": ' . TUtil::jsonList($uncombinedResorts, 'id', 'name') . ',
                "resorts_regions": ' . TUtil::jsonList($resorts_regions, 'dir_resort_id', 'regions_ids') .
             '}';
    }

    /**
     * Action "Regions by country"
     * @param int $id
     */
    public function actionRegionsByCountry($id){
        echo CJSON::encode($this->regionsList($id, true));
    }

    /**
     * Action "Resorts by country"
     * @param int $id
     */
    public function actionResortsByCountry($id){
        echo CJSON::encode([
            'resorts' => $this->resortsList($id, ['is_combined' => 0], true),
            'cities' => $this->citiesList($id, true)
        ]);
    }

    /****************************** Protected Auxiliaries Methods *********************************/

    /**
     * Returns an exemplar of AR directory model
     * @param string $view
     * @param null|int $id
     * @param string $scenario
     * @return mixed
     *
     * @throws
     */
    protected function getModel($view, $id=null, $scenario='insert') {

        if( !($modelName = ArDirectorySearch::arDirModelName($view)) ){
            throw new CHttpException(404, 'Запрашиваемая модель для таблицы: "' . $view . '" не существует.');
        }

        if( !$id ) {
            return new $modelName($scenario);
        }

        $model = $modelName::model()->findByPk($id);

        if( $model === null ) {
            throw new CHttpException(404, 'Запрашиваемая страница не существует.');
        }

        return $model;
    }


    /**
     * Loads view grid
     * @param string $view
     * @param $model
     * @throws CHttpException
     */
    protected function loadViewGrid($view, $model){
        $data = ['view' => $view];
        $grid = 'grid/';

        switch( $view ){
            case 'countries':
            case 'dep_cities':
            case 'hotel_categories':
            case 'hotel_statuses':
            case 'meals':
            case 'ticket_statuses':
                $grid .= 'grid';
            break;

            case 'resorts':
            case 'cities':

                $grid .= 'grid_' . $view;
                $data['countries'] = $this->countriesList();

                if( $model->dir_country_id ) {
                    $data['country_id'] = $model->dir_country_id;
                } else {
                    //key($data['countries']);next($data['countries']);
                    $data['country_id'] = key($data['countries']);
                    $model->setAttribute('dir_country_id', $data['country_id']);
                }

                $data['regions'] = $this->regionsList($data['country_id']);
            break;

            case 'hotels':
                $grid .= 'grid_hotels';
                $data['countries'] = $this->countriesList();

                $country_id = Yii::app()->request->getParam('country_id', 0);
                $data['country_id'] = $country_id ? $country_id : key($data['countries']);

                $data['resorts'] = $this->resortsList($data['country_id']);
                $data['resort_id'] = $model->dir_resort_id;

                if( !$model->dir_resort_id ){
                    $model->dir_resort_id = array_keys($data['resorts']);
                }

                $data['categories'] = $this->categoriesList();
                $data['cities'] = $this->citiesList($data['country_id']);
            break;

            default:
                throw new CHttpException(404, 'Запрашиваемая страница для модели "' . get_class($model) . '" не существует.');
        }

        $data['model'] = $model;
        $this->render($grid, $data);
    }


    /**
     * Loads view grid
     * @param string $view
     * @param $model
     * @throws CHttpException
     */
    protected function loadViewEdit($view, $model){
        $data = ['view' => $view];
        $edit = 'edit/';

        switch( $view ){
            case 'countries':
            case 'dep_cities':
            case 'hotel_categories':
            case 'hotel_statuses':
            case 'meals':
            case 'ticket_statuses':
                $edit .= 'edit';
            break;

            case 'cities':
                $edit .= 'edit_city';

                if( $model->isNewRecord ){
                    $model->dir_country_id = Yii::app()->request->getParam('country_id', 0);
                }

                $data['countries'] = $this->countriesList();
                $data['regions'] = $this->regionsList($model->dir_country_id);
            break;

            case 'resorts':
                $edit .= 'edit_resort';

                if( $model->isNewRecord ){
                    $model->dir_country_id = Yii::app()->request->getParam('country_id', 0);
                }

                $data['countries'] = $this->countriesList();
                $data['regions'] = $this->regionsList($model->dir_country_id);

                list($data['uncombinedResorts'], $data['resorts_regions']) = $this->uncombinedResortsAndRegions($model);

                break;

            case 'hotels':
                $edit .= 'edit_hotel';

                if( $model->isNewRecord ){
                    $model->dir_resort_id = Yii::app()->request->getParam('dir_resort_id', 0);
                    $model->dir_city_id = Yii::app()->request->getParam('dir_city_id', 0);
                    $model->dir_country_id = Yii::app()->request->getParam('country_id', 0);
                }

                $data['countries'] = $this->countriesList();
                $data['categories'] = $this->categoriesList();
                $data['resorts'] = $this->resortsList($model->dir_country_id, ['is_combined' => 0]);
                $data['cities'] = $this->citiesList($model->dir_country_id);

                break;

            default:
                throw new CHttpException(404, 'Запрашиваемая страница для модели "' . get_class($model) . '" не существует.');
        }

        $data['model'] = $model;
        $this->render($edit, $data);
    }


    /**
     * Save view
     * @param string $view
     * @param CActiveRecord $model
     * @throws CHttpException
     * @return bool
     */
    private function saveView($view, &$model){


        $params = ['view' => $view];
        $modelName = get_class($model);

        switch( $view ){
            case 'countries':
            case 'dep_cities':
            case 'hotel_categories':
            case 'hotel_statuses':
            case 'meals':
            case 'ticket_statuses':
                $save_ok = $model->save();
                break;

            case 'cities':
            case 'resorts':

                $save_ok = $model->save();

                if( $view == 'resorts' && $save_ok && $model->is_combined ) {

                    $uncombinedResorts = Yii::app()->request->getParam('uncombined_resorts', []);
                    if( !empty($uncombinedResorts) ) {

                        $existsResorts = array_keys($model->childrenResortsList());
                        $deleteResorts = array_diff($existsResorts, $uncombinedResorts);
                        $newResorts = array_diff($uncombinedResorts, $existsResorts);

                        // Перескрещиваем курорты данной страны, если для комбинированного курорта поменялись его дочерние курорты
                        if( !empty( $newResorts ) || !empty($deleteResorts) ) {

                            $db = Yii::app()->db;

                            // Добавляем курорты в комбинированный курорт
                            if( !empty( $newResorts ) ) {
                                $db->createCommand()->update('{{directory_resorts}}', ['parent_id' => $model->id], ['IN', 'id', $newResorts]);
                            }

                            // Удаляем курорты из комбинированного курорта
                            if( !empty( $deleteResorts ) ) {
                                $db->createCommand()->update('{{directory_resorts}}', ['parent_id' => 0], ['IN', 'id', $deleteResorts]);
                            }

                            $this->bindHotelsByResort($model->id);
                        }

                    } else {
                        $model->delete();
                    }
                }

                // Запрещаем создавать/редактировать регионы для курортов
                //$this->saveRegions($view, $model->id, $_POST[$modelName]);

                $params[$modelName . '[dir_country_id]'] = $model->dir_country_id;

            break;

            case 'hotels':

                $save_ok = $model->save();

                if( $save_ok ) {
                    $params[$modelName . '[dir_resort_id]'] = $model->dir_resort_id;
                    $params['country_id'] = $model->dir_country_id;

                    // save hotel's images
                    ArHotelPhotos::savePhotos($model, Yii::app()->request->getPost('file_ids', []));

                    // save hotel's residences
                    ArHotelResidence::saveResidences($model->id, Yii::app()->request->getPost('Residence', []));

                    // save hotel's services
                    ArHotelServices::saveServices($model->id, Yii::app()->request->getPost('Services', []));

                    // save hotel's cards
                    ArDirHotels::saveCreditCards($model->id, Yii::app()->request->getPost('Cards', []));
                }

                break;

            default:
                throw new CHttpException(404, 'Запрашиваемая страница для модели "' . $modelName . '" не существует.');
        }

        if( $save_ok ) {
            $url = Yii::app()->createUrl('SearchDirectories/index', $params) . '#blink=' . (isset($model->id) ? $model->id : 0);

            if (Yii::app()->request->isAjaxRequest) {
                echo CJavaScript::jsonEncode(['url' => $url]);
                Yii::app()->end();
            } else {
                $this->redirect($url);
            }
        }

    }


    /**
     * Returns uncombined resorts and his regions
     * @param ArDirResorts $model
     * @return array
     */
    private function uncombinedResortsAndRegions($model) {

        $db = Yii::app()->db;

        $resorts = $db->createCommand()
            ->select('r.id AS id, r.name AS name')
            ->from('{{directory_resorts}} AS r')
            ->where(
                ['AND', 'r.dir_country_id = :country', 'r.is_combined = 0'],
                [':country' => $model->dir_country_id]
            )
            ->order('r.name')
            ->group('r.id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        // Получаем и группируем по городам регионы
        $resorts_to_regions = $db->createCommand()
            ->select('rg.dir_resort_id, CONCAT(",", GROUP_CONCAT(rg.dir_region_id SEPARATOR ","), ",") AS regions_ids')
            ->from('{{directory_resorts_to_regions}} AS rg')
            ->join('{{directory_resorts}} AS r', 'r.id = rg.dir_resort_id')
            ->where(['IN', 'rg.dir_resort_id', TUtil::keys($resorts)])
            ->order('r.name')
            ->group('r.id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryall();

        return [$resorts, $resorts_to_regions];
    }


    /**
     * Binds hotels by directory country ID
     * @param integer $dir_resort_id
     */
    private function bindHotelsByResort($dir_resort_id){
        $db = Yii::app()->db;

        $elements = $db->createCommand()
            ->select('element_id, operator_id')
            ->from('{{operator_resorts}}')
            ->where('directory_id = :resort', [':resort' => $dir_resort_id])
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $oElements = [];
        foreach($elements as $element){
            $oElements[$element->operator_id][] = $element->element_id;
        }

        foreach( $oElements as $oid => $resorts ){
            TSearch\BindData::inst($oid)->bindComparing('hotels', $resorts);
        }
    }


    /**
     * Regions list
     * @param int $countryId
     * @param bool $js
     * @return array
     */
    protected function regionsList($countryId, $js=false){

        $regions = Yii::app()->db->createCommand()
            ->select(($js ? 'CONCAT("_", id) AS id' : 'id' ) . ', name, type')
            ->from('{{directory_regions}}')
            ->where('dir_country_id = :dir_c_id', [':dir_c_id' => $countryId])
            ->order('type DESC, name ASC ')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();

        $list = [];
        $regionTypes = ArDirRegions::regionTypeNames();
        foreach( $regions as $region ){
            $list[$regionTypes[$region->type]][$region->id] = $region->name;
        }

        return $list;
    }

    /**
     * Resorts list
     * @param int $countryId
     * @param array $params
     * @param bool $js
     * @return array
     */
    protected function resortsList($countryId, $params=[], $js=false){

        $condition = '`dir_country_id` = :dir_c_id ';
        $placeholders = [':dir_c_id' => $countryId];

        if( isset( $params['is_combined'] ) ) {
            $condition .= ' AND is_combined = :is_combined';
            $placeholders[':is_combined'] = (int)$params['is_combined'];
        }

        $list = CHtml::listData(
            ArDirResorts::model()->findAllBySql(
                'SELECT ' . ($js ? 'CONCAT("_", `id`) AS `id`' : '`id`' ) . ', `name` FROM {{directory_resorts}} WHERE ' . $condition . ' ORDER BY name ASC',
                $placeholders
            ),
            'id',
            'name'
        );

        return $list;
    }

    /**
     * Countries list
     * @param integer $countryId
     * @param bool $js
     * @return array
     */
    protected function citiesList($countryId, $js=false){
        $table = '{{directory_cities}}';
        $list = CHtml::listData(
            ArDirCities::model()->findAllBySql(
                'SELECT ' . ($js ? 'CONCAT("_", `id`) AS `id`' : '`id`' ) . ', `name` FROM ' . $table . ' WHERE `dir_country_id` = :dir_c_id ORDER BY name ASC',
                [':dir_c_id' => $countryId]
            ),
            'id',
            'name'
        );

        return $list;
    }


    /**
     * Countries list
     * @return array
     */
    protected function countriesList(){
        return CHtml::listData( ArDirCountries::model()->findAll(['order' => 'name ASC']), 'id', 'name' );
    }

    /**
     * Countries list
     * @return array
     */
    protected function categoriesList(){
        return CHtml::listData( ArDirHotelCategories::model()->findAll(['order' => 'name ASC']), 'id', 'name' );
    }

    /**
     * Saves regions(district/region/province/island) for resorts/cities
     * @param string $view
     * @param integer $id
     * @param array $attributes
     */
    private function saveRegions($view, $id, $attributes){
        $table = 'directory_' . $view . '_to_regions';
        $field = $view == 'cities' ? 'dir_city_id' : 'dir_resort_id';

        $regionsData = [];

        // Если в будущем будет необходимо редактировать регионы курортов/городов, - предусмотреть, что у курорта может быть несколько райнов!
        if( !empty($attributes[ArDirRegions::REG_TYPE_DISTRICT]) ){
            $regionsData[] = [$field => $id, 'dir_region_id' => $attributes[ArDirRegions::REG_TYPE_DISTRICT]];
        }

        if( !empty($attributes[ArDirRegions::REG_TYPE_REGION]) ){
            $regionsData[] = [$field => $id, 'dir_region_id' => $attributes[ArDirRegions::REG_TYPE_REGION]];
        }

        if( !empty($attributes[ArDirRegions::REG_TYPE_PROVINCE]) ){
            $regionsData[] = [$field => $id, 'dir_region_id' => $attributes[ArDirRegions::REG_TYPE_PROVINCE]];
        }

        if( !empty($attributes[ArDirRegions::REG_TYPE_ISLAND]) ){
            $regionsData[] = [$field => $id, 'dir_region_id' => $attributes[ArDirRegions::REG_TYPE_ISLAND]];
        }

        Yii::app()->db->createCommand()->delete('{{' . $table . '}}', $field . ' = :id', [':id' => $id]);
        TUtil::multipleInsertData($table, $regionsData);
    }

}