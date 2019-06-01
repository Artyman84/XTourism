<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */

class HRatingsList extends CWidget {

    /**
     * @var ArHotelRating
     */
    public $ratings;

    /**
     * @var array
     */
    private static $icons = [
        'чистота' => 'paint-brush',
        'комфорт' => 'thumbs-up',
        'месторасположение' => 'map-marker',
        'удобства' => 'smile-o',
        'персонал' => 'users',
        'соотношение цена/качество' => 'balance-scale',
        'бесплатный wi-fi' => 'wifi',
        'платный wi-fi' => 'wifi',
    ];


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
        if( empty($this->ratings) || empty($this->ratings->voices) || empty($this->ratings->scores) ){
            $htmlRatings = '<div class="alert alert-warning text-center" style="margin: 0;"><strong>Этот отель не содержит достаточно отзывов.</strong></div>';
        } else {

            $scores = json_decode($this->ratings->scores);
            $total = 0.0;

            $hRatings = '<table class="table table-striped"><tbody>';
            foreach ($scores as $score) {
                $fScore = (float)str_replace(',', '.', $score->value);
                $percents = $fScore * 10;
                $total += $fScore;
                $class = '';

                $icon = '';
                $res_score = mb_strtolower($score->name, 'utf8');
                if( isset(self::$icons[$res_score]) ){
                    $icon = "<i class='fa fa-" . self::$icons[$res_score] . "'></i>";
                }


                if ($percents < 50) {
                    $class = 'danger';
                } elseif ($percents < 80) {
                    $class = 'warning';
                } else {
                    $class = 'success';
                }

                $hRatings .= '<tr>
                                <td style="width: 25%;" class="text-info">' . $icon . ' ' . $score->name . '</td>
                                <td style="width: 80%;">
                                    <div class="progress" style="height: 18px;">
                                        <div class="progress-bar progress-bar-' . $class . '" role="progressbar" style="width: ' . $percents . '%;"></div>
                                    </div>
                                </td>
                                <th class="text-info">' . $fScore . '</th>
                              </tr>';
            }

            $hRatings .= '</tbody></table>';

            $htmlRatings = $hRatings . '<br><table class="table" style="width: auto; margin: 0;"><tbody>
                    <tr class="text-muted">
                        <th style="border: 0px;">Общая оценка:</th>
                        <th style="border: 0px;">' . round(($total/count($scores)), 1) . '</th>
                        <th style="border: 0px;">Всего голосов:</th>
                        <th style="border: 0px;">' . $this->ratings->voices . '</th>
                    </tr>
            </tbody></table>';
        }

        echo $htmlRatings;
    }

}