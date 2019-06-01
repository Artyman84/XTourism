<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */

class HResidenceList extends CWidget {

    /**
     * @var ArHotelResidence
     */
    public $residence;

    /**
     * @var ArCurrencyCards
     */
    public $cards;


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
        $cardPath = Yii::app()->baseUrl . '/images/hotel_cards/';
        $cardDir = Yii::getPathOfAlias('webroot') . '/images/hotel_cards/';
        $icons = ArHotelResidence::icons();

        $htmlResidence = '<table class="table table-striped"><tbody>';

        foreach($this->residence as $r){
            $value = $r->value;
            $r->name = str_replace(':', '', $r->name);

            if( $value ) {
                $icon = '';
                $res_name = mb_strtolower($r->name, 'utf8');
                if( isset($icons[$res_name]) ){
                    $icon = "<i class='fa fa-" . $icons[$res_name] . "'></i>";
                }

                $htmlResidence .= "<tr>
                                    <td style='width: 25%;' class='text-info'>{$icon} {$r->name}</td>
                                    <td style='vertical-align: middle;'>{$value}</td>
                                  </tr>";
            }
        }

        if( !empty($this->cards) ){
            $value = "";
            $without_img = "";
            foreach($this->cards as $card) {

                if( in_array($card->name, ['Кредитные карты не принимаются/оплата только наличными', 'Только наличные']) ){

                    $value = 'Только наличные';
                    break;

                } else {

                    $file = $card->name . '.png';
                    if ( /*file_exists($cardDir . $file)*/ $card->description ) {
                        $value .= "<img src='{$cardPath}{$file}' style='height: 40px; width: 65px; margin-right: 2px; margin-top: 4px;' data-toggle='tooltip' data-placement='top'  title='{$card->description}' alt='{$card->name}'>&nbsp;";
                    } else {
                        $without_img .= "<a href='#' onclick='return false;' style='vertical-align: sub; margin-right: 2px; text-decoration: none; padding: 12px; margin-bottom: 20px; line-height: 1.42857143; background-color: #fff; border: 1px solid #ddd; border-radius: 4px; -webkit-transition: border .2s ease-in-out; -o-transition: border .2s ease-in-out; transition: border .2s ease-in-out;' alt='{$card->name}'>{$card->name}</a>";
                    }
                }
            }

            $value .= $without_img;

            if( $value ){
                $name = 'Карты, которые принимает отель';

                $htmlResidence .= "<tr >
                                    <td style='width: 25%;' class='text-info'><i class='fa fa-credit-card'></i> {$name}</td>
                                    <td style='vertical-align: middle;'>{$value}</td>
                                  </tr>";
            }
        }


        $htmlResidence .= '</tbody></table>';

        echo $htmlResidence;
    }

}