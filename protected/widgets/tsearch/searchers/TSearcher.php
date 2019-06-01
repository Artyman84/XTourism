<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 21.06.14
 * Time: 14:51
 * To change this template use File | Settings | File Templates.
 */

abstract class TSearcher extends CWidget{

    /**
     * Params of widget
     * @var array
     */
    public $params = [
        'availableDates' => [],
        'countries' => [],
        'depCities' => [],
        'durations' => [],
        'people' => [],
        'hotelCategories' => [],
        'meals' => [],
        'price' => [],
        'currency' => [],
        'resorts' => [],
        'hotels' => [],
        'operators' => [],
    ];

    /**
     * Initializing
     */
    public function init(){
        $cs = Yii::app()->clientScript;
        $assetManager = Yii::app()->assetManager;

        $cs->registerScriptFile(
            $assetManager->publish(Yii::getPathOfAlias('tsearch.searchers.') . '/TSearcher.js'),
            CClientScript::POS_END
        );
    }

    /**
     * Loads module widget
     * @param string $wName
     * @return mixed
     * @throws Exception
     */
    protected function loadWidget($wName){
        $wFullName = 'TSearcher' . ucfirst($wName);
        $wPath = 'tsearch.searchers.widgets.' . $wFullName . '.' . $wFullName;

        return  $this->widget($wPath, !empty($this->params[$wName]) ? $this->params[$wName] : []);
    }

}