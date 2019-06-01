<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 01.05.2015
 * Time: 9:58
 */

class TAdminSidebar extends TSiteMenu {

    /**
     * @var
     */
    private $isAdmin;

    /**
     * @var
     */
    private $isSuperAdmin;

    /**
     * Init
     */
    public function init(){
        $role = Yii::app()->user->role;
        $admins = [ArUsers::ROLE_ADMIN => 1, ArUsers::ROLE_SUPERADMIN => 1];

        $this->isAdmin = isset($admins[$role]);
        $this->isSuperAdmin = $role == ArUsers::ROLE_SUPERADMIN;

        parent::init();
    }

    /**
     * Runs Sidebar
     */
    public function run() {

        if ($this->isAdmin && $this->checkMenuItem(self::T_BMI_PERSONAL)) {

            $this->PersonalSideBar();

        } elseif ($this->isSuperAdmin && $this->checkMenuItem(self::T_BMI_TOUR_SEARCH_SETTINGS)) {

            $this->TourSearchSettingsSideBar();

        } elseif( $this->isAdmin && $this->checkMenuItem(self::T_BMI_PRODUCTS_AND_PACKAGES) ){

            $this->ProductsAndPackages();

        } elseif( $this->isAdmin && $this->checkMenuItem(self::T_BMI_AGENT_PRODUCT) ){

            $this->AgentProducts();
        }
    }

    /**
     * Render Personal Sidebar
     */
    private function AgentProducts(){?>
        <ul class="nav nav-sidebar">
            <li <?php echo ($this->controller_name == 'userTourShowcase' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('userTourShowcase/index')?>"><span class="glyphicon glyphicon-th"></span> Витрины туров</a>
            </li>

            <li <?php echo ($this->controller_name == 'userSearcher' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('UserSearcher/index')?>"><span class="glyphicon glyphicon-search"></span> Поисковики туров</a>
            </li>

            <li <?php echo ($this->controller_name == 'userConstruct' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('UserConstruct/index')?>"><span class="fa fa-magic"></span> Конструкторы лендингов</a>
            </li>

            <li <?php echo ($this->controller_name == 'toursRequests' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('ToursRequests/index')?>"><span class="flaticon-phone-auricular-and-a-clock"></span> Заявки на туры</a>
            </li>

        </ul>
        <?
    }

    /**
     * Render Personal Sidebar
     */
    private function PersonalSideBar(){?>
        <ul class="nav nav-sidebar">
            <li class="active">
                <a href="<?=Yii::app()->createUrl('Users/index')?>"><i class="fa fa-users i-margin"></i> Персонал</a>
            </li>
        </ul><?
    }


    /**
     * Render TourSearch Sidebar
     */
    private function TourSearchSettingsSideBar(){
        ?><h4 class="page-header" style="margin: 0;">Поисковик</h4>

        <ul class="nav nav-sidebar">
            <li <?php echo ($this->controller_name == 'migration' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('Migration/index')?>"><span class="glyphicon glyphicon-transfer i-margin"></span> Скрещивание данных</a>
            </li>
            <li <?php echo ($this->controller_name == 'searchTourOperators' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('SearchTourOperators/index')?>"><span class="flaticon-call-center-worker-with-headset"></span> Операторы туров</a>
            </li>
        </ul>
        <? $tables = ArDirectorySearch::getTableNames(); ?>
        <ul class="nav nav-sidebar">
<!--            <li role="presentation" class="dropdown-header"> Справочники данных</li>-->
            <? $isSearch = $this->controller_name == 'searchDirectories'; ?>
            <? $view = Yii::app()->request->getParam('view'); ?>

            <li <?php echo ( $isSearch && $view == 'dep_cities' ?  'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('SearchDirectories/index', ['view' => 'dep_cities'])?>">
                    <span class="fa fa-book i-margin"></span>
                    <?=$tables['dep_cities']?>
                </a>
            </li>

            <li <?php echo ( $isSearch && $view == 'countries' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('SearchDirectories/index', ['view' => 'countries'])?>">
                    <span class="fa fa-book i-margin"></span>
                    <?=$tables['countries']?>
                </a>
            </li>

            <li <?php echo ( $isSearch && $view == 'cities' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('SearchDirectories/index', ['view' => 'cities'])?>">
                    <span class="fa fa-book i-margin"></span>
                    <?=$tables['cities']?>
                </a>
            </li>

            <li <?php echo ( $isSearch && $view == 'resorts' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('SearchDirectories/index', ['view' => 'resorts'])?>">
                    <span class="fa fa-book i-margin"></span>
                    <?=$tables['resorts']?>
                </a>
            </li>

            <li <?php echo ( $isSearch && $view == 'hotels' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('SearchDirectories/index', ['view' => 'hotels'])?>">
                    <span class="fa fa-book i-margin"></span>
                    <?=$tables['hotels']?>
                </a>
            </li>

            <li <?php echo ( $isSearch && $view == 'hotel_categories' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('SearchDirectories/index', ['view' => 'hotel_categories'])?>">
                    <span class="fa fa-book i-margin"></span>
                    <?=$tables['hotel_categories']?>
                </a>
            </li>

            <li <?php echo ( $isSearch && $view == 'hotel_statuses' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('SearchDirectories/index', ['view' => 'hotel_statuses'])?>">
                    <span class="fa fa-book i-margin"></span>
                    <?=$tables['hotel_statuses']?>
                </a>
            </li>

            <li <?php echo ( $isSearch && $view == 'meals' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('SearchDirectories/index', ['view' => 'meals'])?>">
                    <span class="fa fa-book i-margin"></span>
                    <?=$tables['meals']?>
                </a>
            </li>

            <li <?php echo ( $isSearch && $view == 'ticket_statuses' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('SearchDirectories/index', ['view' => 'ticket_statuses'])?>">
                    <span class="fa fa-book i-margin"></span>
                    <?=$tables['ticket_statuses']?>
                </a>
            </li>

        </ul>

        <ul class="nav nav-sidebar">
            <li <?php echo ($isSearch && $this->action_name == 'checkingCategories' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('SearchDirectories/checkingCategories')?>"><span class="glyphicon glyphicon-star i-margin"></span> Проверка звезд отелей</a>
            </li>
        </ul>
        <?
    }


    /**
     * Render Products and packages Sidebar
     */
    private function ProductsAndPackages() {
        ?><h4 class="page-header" style="margin: 0;">Продукты и пакеты</h4>

        <ul class="nav nav-sidebar">
            <li <?php echo ($this->controller_name == 'shopProducts' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('shopProducts/index')?>"><span class="flaticon-package-cube-box-for-delivery"></span> Продукты</a>
            </li>
            <li <?php echo ($this->controller_name == 'shopPackages' ? 'class="active"' : '')?>>
                <a href="<?=Yii::app()->createUrl('shopPackages/index')?>"><span class="flaticon-delivery-package-opened"></span> Пакеты</a>
            </li>
        </ul>

        <? if( $this->isAdmin ) {?>
            <ul class="nav nav-sidebar">
                <li <?php echo ($this->controller_name == 'usersDraftPackages' ? 'class="active"' : '')?>>
                    <a href="<?=Yii::app()->createUrl('usersDraftPackages/index')?>"><span class="fa fa-suitcase i-margin"></span> Неактивные пакеты</a>
                </li>
                <li <?php echo ($this->controller_name == 'usersPackages' ? 'class="active"' : '')?>>
                    <a href="<?=Yii::app()->createUrl('usersPackages/index')?>"><span class="fa fa-briefcase i-margin"></span> Активные пакеты</a>
                </li>
                <li <?php echo ($this->controller_name == 'usersInvoices' ? 'class="active"' : '')?>>
                    <a href="<?=Yii::app()->createUrl('usersInvoices/index')?>"><span class="flaticon-verification-of-delivery-list-clipboard-symbol"></span> Счета</a>
                </li>
            </ul>
        <? }
    }

}