<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */

class HServicesList extends CWidget {

    /**
     * @var ArHotelServices
     */
    public $services;

    /**
     * Init
     */
    public function init(){
        parent::init();
    }

    /**
     * Run
     */
    public function run(){
        $htmlServices = '<table class="table table-striped"><tbody>';
        $icons = ArHotelServices::icons();

        foreach($this->services as $service) {
            $service->name = str_replace(':', '', $service->name);

            $icon = '';
            $res_name = mb_strtolower($service->name, 'utf8');
            if( isset($icons[$res_name]) ){
                $icon = "<i class='fa fa-" . $icons[$res_name] . "'></i>";
            }

            $htmlServices .=
                "<tr>
                    <td style='width: 25%;' class='text-info'>{$icon} {$service->name}</td>
                    <td>{$service->value}</td>
                </tr>";
        }

        $htmlServices .= '</tbody></table>';

        echo $htmlServices;
    }

}