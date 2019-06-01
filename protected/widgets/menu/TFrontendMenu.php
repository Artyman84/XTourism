<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 01.05.2015
 * Time: 9:58
 */

class TFrontendMenu extends TSiteMenu {

    /**
     * Init
     */
    public function init() {
        parent::init();
    }

    public function run() {?>

        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container container-frontend">

                <div class="nav-justified">
                    <a class="navbar-brand" href="<?php echo Yii::app()->createUrl('Welcome')?>"><?=Yii::app()->name?></a>
                </div>


                <div class="collapse navbar-collapse">

                    <ul class="nav nav navbar-nav">
                        <li <?php echo ($this->checkMenuItem(self::T_FMI_ABOUT) ? 'class="active"' : '')?>>
                            <a tabindex="-1" href="<?php echo Yii::app()->createUrl('Welcome/page', array('view' => 'about'))?>"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;О Нас</a>
                        </li>
                        <li <?php echo ($this->checkMenuItem(self::T_FMI_CONTACTS) ? 'class="active"' : '') ?>>
                            <a tabindex="-2" href="<?php echo Yii::app()->createUrl('Welcome/contacts')?>"><span class="glyphicon glyphicon-envelope"></span>&nbsp;Обратная связь</a>
                        </li>
                        <?php if( Yii::app()->user->isGuest ) {?>
                            <li <?php echo ($this->checkMenuItem(self::T_FMI_REGISTRATION) ? 'class="active"' : '') ?>>
                                <a href="<?php echo Yii::app()->createUrl('Welcome/registration')?>" class=""><span class="glyphicon glyphicon-edit"></span>&nbsp;Регистрация</a>
                            </li>
                        <? } ?>
                        <li <?php echo ($this->checkMenuItem(self::T_FMI_TOUR_SHOWCASE_MODULE) ? 'class="active"' : '') ?>>
                            <a tabindex="-2" href="<?php echo Yii::app()->createUrl('Welcome/showcase')?>"><span class="glyphicon glyphicon-th"></span>&nbsp;Витрина туров</a>
                        </li>
                    </ul><?php

                    if( Yii::app()->user->isGuest ){

                        $this->loginForm();

                    } else {?>

                        <div class="nav nav navbar-nav pull-right">

                            <ul class="nav nav navbar-nav">

                                <? $this->tourRequestsItem(); ?>

                                <li class="dropdown <?=$this->checkMenuItems([self::T_FMI_TOUR_SHOWCASE, self::T_FMI_TOUR_SEARCH, self::T_FMI_LP_BUILDER]) ? 'active' : ''?>">
                                    <? $this->componentsMenu(); ?>
                                </li>

                                <li class="dropdown <?=($this->checkMenuItems([self::T_FMI_PROFILE, self::T_FMI_USER_PACKAGE]) ? ' active' : '')?>">

                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <?php echo Yii::app()->user->name;?><span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">

                                        <li <?=$this->checkMenuItem(self::T_FMI_PROFILE) ? 'class="active"' : ''?>>
                                            <a tabindex="-3" href="<?=Yii::app()->createUrl('Users/profile')?>">
                                                <span class="glyphicon glyphicon-user i-margin"></span>
                                                Профайл
                                            </a>
                                        </li>

                                        <li class="text-primary <?=($this->checkMenuItem(self::T_FMI_USER_PACKAGE) ? ' active' : '')?>">
                                            <a tabindex="-3" href="<?=Yii::app()->createUrl('Packages/userPackage')?>">
                                                <span class="flaticon-delivery-package-opened"></span>
                                                Пакет
                                            </a>
                                        </li>

                                        <li>
                                            <a tabindex="-5" class="color-danger" href="<?php echo Yii::app()->createUrl('welcome/logout');?>">
                                                <span class="glyphicon glyphicon-log-out i-margin"></span> <b>Выйти</b>
                                            </a>
                                        </li>
                                    </ul>

                                </li>

                            </ul>

                        </div><?php

                    }?>

                </div>
            </div>
        </nav><?
    }

    /**
     * Renders Login Form
     */
    protected function loginForm(){?>

        <div class="nav nav navbar-nav pull-right">
            <ul class="nav nav navbar-nav">
                <li>
                    <form class="navbar-form navbar-right" role="form" action="<?php echo Yii::app()->createUrl('Welcome')?>" method="post">
                        <div class="form-group">
                            <input type="text" name="LoginForm[email]" placeholder="Введите email" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" name="LoginForm[password]" placeholder="Введите пароль" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-log-in"></span> Войти</button>
                    </form>
                </li>
            </ul>
        </div><?
    }

    /**
     * Tours requests item
     */
    private function tourRequestsItem(){

        $package = ArShopUsersPackages::model()->findByAttributes(['user_id' => Yii::app()->user->id]);

        if( $package && $package->isValid() && ($package->hasProduct(ArShopProductsTypes::PDT_TOUR_SHOWCASE) || $package->hasProduct(ArShopProductsTypes::PDT_SEARCHER)) ) {
            $count = Yii::app()->db->createCommand()
                ->select('COUNT(id)')
                ->from('{{clients_tours_requests}}')
                ->where('agent_id = :user_id AND state = 2', [':user_id' => Yii::app()->user->id])
                ->queryScalar();

            $is_active = $this->checkMenuItem(self::T_FMI_CLIENTS_TOUR_REQUESTS); ?>
            <li class="t-menu-tours-requests <?=$is_active ? 'active' : '' ?>">
                <a tabindex="-2" href="<?= Yii::app()->createUrl('ToursRequests') ?>" <?=$count ? 'style="padding-right: 35px;"' : '' ?>>
                    <span class="flaticon-phone-auricular-and-a-clock" style="position: relative; top: 1px;"></span> Заявки на туры
                    <? if ($count) { ?>
                        <div class="text-nowrap tour-request-count">
                            <span class="fa fa-plus"></span>
                            <strong><?=$count?></strong>
                        </div>
                    <? } ?>
                </a>
            </li><?
        }
    }


    /**
     * Renders components menu
     */
    private function componentsMenu() {
        $package = Yii::app()->user->package;

        if( $package && $package->isValid() ) { ?>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="fa fa-cogs"></span>&nbsp;Настройки продуктов<span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">

            <?php if ( $package->hasProduct(ArShopProductsTypes::PDT_TOUR_SHOWCASE) ) { ?>
                <li <?=$this->checkProductMenuItem('tour_showcase') ? 'class="active"' : ''?>>
                    <a tabindex="-3" href="<?php echo Yii::app()->createUrl('UserTourShowcase'); ?>">
                        <span class="glyphicon glyphicon-th"></span>
                        Витрина туров
                    </a>
                </li>
            <?php } ?>

            <?php if ( $package->hasproduct(ArShopProductsTypes::PDT_HOTELS_SHOWCASE) ) { ?>
                <li <?=$this->checkProductMenuItem('hotel_showcase') ? 'class="active"' : ''?>>
                    <a tabindex="-3" href="<?php echo Yii::app()->createUrl('UserHotelShowcase'); ?>">
                        <span class="fa fa-building i-margin"></span>
                        Витрина отелей
                    </a>
                </li>
            <?php } ?>

            <?php if ( $package->hasProduct(ArShopProductsTypes::PDT_SEARCHER) ) { ?>
                <li <?=$this->checkProductMenuItem('searcher') ? 'class="active"' : ''?>>
                    <a tabindex="-3" href="<?php echo Yii::app()->createUrl('UserSearcher'); ?>">
                        <span class="glyphicon glyphicon-search"></span>
                        Поисковик туров
                    </a>
                </li>
            <?php } ?>

            <?php if ( $package->hasProduct(ArShopProductsTypes::PDT_LP_BUILDER) ) { ?>
                <li <?=$this->checkProductMenuItem('lp_builder') ? 'class="active"' : ''?>>
                    <a tabindex="-3" href="<?php echo Yii::app()->createUrl('UserConstruct'); ?>">
                        <span class="fa fa-magic i-margin"></span>
                        Конструктор лендингов
                    </a>
                </li>
            <?php } ?>

            </ul><?
        }
    }

    /**
     * Overrides parent function
     * @param string $product_type
     * @return bool|mixed
     */
    private function checkProductMenuItem($product_type){
        switch($product_type){
            case 'tour_showcase': return $this->checkMenuItem(self::T_FMI_TOUR_SHOWCASE);
            case 'hotel_showcase': return $this->checkMenuItem(self::T_FMI_HOTEL_SHOWCASE);
            case 'searcher': return $this->checkMenuItem(self::T_FMI_TOUR_SEARCH);
            case 'lp_builder': return $this->checkMenuItem(self::T_FMI_LP_BUILDER);
            default: return false;
        }
    }
}