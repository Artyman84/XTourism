<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 01.05.2015
 * Time: 9:58
 */

class TBackendMenu extends TSiteMenu {


    public function run(){?>

        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container-fluid">

                <div class="row">

                    <div class="col-sm-3 col-md-2">
                        <div class="nav-justified">
                            <a class="navbar-brand" href="<?=Yii::app()->createUrl('Admin/index')?>"><?=Yii::app()->name?></a>
                        </div>
                    </div>


                    <div class="col-sm-9 col-md-10">
                        <div class="collapse navbar-collapse">

                            <div class="nav navbar-nav pull-left">
                                <ul class="nav navbar-nav">
                                    <li class="text-primary <?=($this->checkMenuItem(self::T_BMI_PERSONAL) ? ' active' : '')?>">
                                        <a tabindex="-3" href="<?=Yii::app()->createUrl('Users/index', array('id' => Yii::app()->user->id))?>">
                                            <i class="fa fa-users i-margin"></i>
                                            Персонал
                                        </a>
                                    </li>

                                    <li class="text-primary <?=($this->checkMenuItem(self::T_BMI_PRODUCTS_AND_PACKAGES) ? ' active' : '')?>">
                                        <a tabindex="-3" href="<?=Yii::app()->createUrl('ShopProducts/index')?>">
                                            <span class="flaticon-delivery-package"></span>
                                            Продукты и пакеты
                                        </a>
                                    </li>

                                    <li class="text-primary <?=$this->checkMenuItem(self::T_BMI_AGENT_PRODUCT) ? 'active' : ''?>">
                                        <a href="<?=Yii::app()->createUrl('userTourShowcase/index')?>">
                                            <span class="flaticon-package-cube-box-for-delivery"></span>
                                            Продукты турагентов
                                        </a>
                                    </li>

                                    <li class="dropdown <?=$this->checkMenuItems([self::T_BMI_TOUR_SEARCH_SETTINGS]) ? 'active' : ''?>">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="fa fa-gears i-margin"></span> Настройки компонентов<span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li <?=$this->checkMenuItem(self::T_BMI_TOUR_SEARCH_SETTINGS) ? 'class="active"' : ''?>><a href="<?=Yii::app()->createUrl('Migration/index')?>"><span class="glyphicon glyphicon-search"></span> Поисковик туров</a></li>
                                        </ul>
                                    </li>

                                </ul>
                            </div>


                            <div class="nav nav navbar-nav pull-right">
                                <ul class="nav nav navbar-nav">
                                    <li user="<?=$this->userId?>" class="text-primary <?=($this->checkMenuItem(self::T_BMI_PROFILE) ? ' active' : '')?>" style="margin-right: 20px;">
                                        <a tabindex="-3" href="<?=Yii::app()->createUrl('Users/profile', array('id' => Yii::app()->user->id))?>">
                                            <span class="glyphicon glyphicon-user"></span>
                                            <?php echo Yii::app()->user->name;?>:
                                            <?php echo ArUsers::roleName(Yii::app()->user->role);?>
                                        </a>
                                    </li>
                                    <li class="text-danger">
                                        <a tabindex="-5" class="color-danger" href="<?php echo Yii::app()->createUrl('Admin/logout');?>"><span class="glyphicon glyphicon-log-out"></span> <b>Выйти</b></a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </nav><?
    }
}