<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 01.05.2015
 * Time: 9:58
 */

class TSiteMenu extends CWidget {

    // Backend Menu Items
    const T_BMI_PERSONAL = 'backend_personal';
    const T_BMI_PRODUCTS_AND_PACKAGES = 'backend_products_and_packages';
    const T_BMI_PROFILE = 'backend_profile';
    //const T_BMI_TOUR_SHOWCASE = 'backend_tour_showcase';
    const T_BMI_TOUR_SEARCH_SETTINGS = 'backend_tour_search_settings';
    const T_BMI_AGENT_PRODUCT = 'backend_agent_product';

    // Frontend Menu Items
    const T_FMI_PROFILE = 'frontend_profile';
    const T_FMI_USER_PACKAGE = 'frontend_user_package';
    const T_FMI_TOUR_SHOWCASE = 'frontend_tour_showcase';
    const T_FMI_HOTEL_SHOWCASE = 'frontend_hotel_showcase';
    const T_FMI_TOUR_SEARCH = 'frontend_tour_search';
    const T_FMI_LP_BUILDER = 'frontend_lpbuilding';
    const T_FMI_ABOUT = 'frontend_about';
    const T_FMI_REGISTRATION = 'frontend_registration';
    const T_FMI_CONTACTS = 'frontend_tour_contacts';
    const T_FMI_PROMOTION = 'frontend_promotion';
    const T_FMI_TOUR_SEARCH_MODULE = 'frontend_search_tour_module';
    const T_FMI_TOUR_SHOWCASE_MODULE = 'frontend_showcase_tour_module';
    const T_FMI_CLIENTS_TOUR_REQUESTS = 'frontend_clients_tours_request';

    /**
     * Current controller
     * @var string
     */
    public $controller_name;

    /**
     * Current action
     * @var string
     */
    public $action_name;

    /**
     * @var null|int
     */
    public $userId;

    /**
     * ACLs for sidebars
     * @var array
     */
    protected static $menuItemsForControllers = [

        // Backend Menu Items

        self::T_BMI_PROFILE => [
            'users' => [
                'profile' => 'user'
            ]
        ],

        self::T_BMI_PRODUCTS_AND_PACKAGES => [
            'shopProducts' => 1,
            'shopPackages' => 1,
            'usersDraftPackages' => 1,
            'usersPackages' => 1,
            'usersInvoices' => 1,
        ],

        self::T_BMI_PERSONAL => [
            'users' => 1
        ],

        self::T_BMI_TOUR_SEARCH_SETTINGS => [
            'migration' => 1,
            //'userSearcher' => 1,
            'searchTourOperators' => 1,
            'searchDirectories' => 1
        ],

        self::T_BMI_AGENT_PRODUCT => [
            'userSearcher' => 1,
            'userTourShowcase' => 1,
            'userConstruct' => 1,
            'toursRequests' => 1
        ],



        // Frontend Menu Items

        self::T_FMI_PROFILE => [
            'users' => [
                'profile' => 1
            ]
        ],

        self::T_FMI_USER_PACKAGE => [
            'packages' => [
                'userPackage' => 1
            ]
        ],

        self::T_FMI_ABOUT => [
            'welcome' => [
                'page' => 'view'
            ]
        ],

        self::T_FMI_REGISTRATION => [
            'welcome' => [
                'registration' => 1
            ]
        ],

        self::T_FMI_CONTACTS => [
            'welcome' => [
                'support' => 1
            ]
        ],

        self::T_FMI_PROMOTION => [
            'welcome' => [
                'promotion' => 1
            ]
        ],

        self::T_FMI_TOUR_SHOWCASE => [
            'userTourShowcase' => 1,
        ],

        self::T_FMI_HOTEL_SHOWCASE => [
            'userHotelShowcase' => 1,
        ],

        self::T_FMI_TOUR_SEARCH => [
            'userSearcher' => 1,
        ],

        self::T_FMI_LP_BUILDER => [
            'userConstruct' => 1,
        ],

        self::T_FMI_TOUR_SEARCH_MODULE => [
            'welcome' => [
                'searchTours' => 1
            ],
        ],

        self::T_FMI_TOUR_SHOWCASE_MODULE => [
            'welcome' => [
                'showcase' => 1
            ],
        ],

        self::T_FMI_CLIENTS_TOUR_REQUESTS => [
            'toursRequests' => 1
        ]

    ];


    /**
     * Init
     */
    public function init(){
        $this->controller_name = lcfirst($this->controller_name);
        $this->action_name = lcfirst($this->action_name);
    }


    /**
     * Checks Sidebar ACL by controller
     * @param string $item
     * @return mixed
     */
    protected function checkMenuItem($item){

        if( !isset(self::$menuItemsForControllers[$item][$this->controller_name]) ){

            return false;

        } else {

            $itemController = self::$menuItemsForControllers[$item][$this->controller_name];

            if( !is_array($itemController) ){
                return true;
            }

            if( isset($itemController[$this->action_name]) ) {

                switch( $itemController[$this->action_name] ){
                    case 'user': return $this->userId == Yii::app()->user->id;

                    case 'view':
                        $itemName = explode('_', $item);
                        return $itemName[1] == Yii::app()->request->getParam('view');

                    case 1: return true;

                    default: return false;

                }

            } else {
                return false;
            }

        }
    }

    /**
     * Checks Sidebars ACL by controller
     * @param array $items
     * @return bool
     */
    protected function checkMenuItems($items){
        foreach( $items as $item ){
            if( $this->checkMenuItem($item) ){
                return true;
            }
        }

        return false;
    }

}