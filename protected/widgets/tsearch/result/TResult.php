<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 02.07.14
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */

class TResult extends CWidget {

    /**
     * Header html options
     * @var array
     */
    public $headerOptions = [];

    /**
     * Main html options
     * @var array
     */
    public $mainOptions = [];

    /**
     * Footer html options
     * @var array
     */
    public $footerOptions = [];

    /**
     * Count of all operators
     * @var integer
     */
    public $operators_count;

    /**
     * Progress bar class
     * @var string
     */
    public $progress_bar_class;

    /**
     * Result size
     * @var string
     */
    public $result_url;

    /**
     * Result size
     * @var string
     */
    public $result_tbl_size;

    /**
     * Result size
     * @var string
     */
    public $result_tbl_classes;

    /**
     * Result size
     * @var string
     */
    public $result_tbl_headers;

    /**
     * Result size
     * @var string
     */
    public $result_tbl_row;

    /**
     * Result error function
     * @var string
     */
    public $result_error_func;

    /**
     * Init
     */
    public function init(){

        $headerClasses = isset($this->headerOptions['class']) ? $this->headerOptions['class'] : '';
        $this->headerOptions['class'] = 'col-md-12 col-sm-12 col-xs-12 results-header results-block t-resultHeader ' . $headerClasses;

        $mainClasses = isset($this->mainOptions['class']) ? $this->mainOptions['class'] : '';
        $this->mainOptions['class'] = 'col-md-12 col-sm-12 col-xs-12 tour-result-main t-resultMain ' . $mainClasses;

        $footerClasses = isset($this->footerOptions['class']) ? $this->footerOptions['class'] : '';
        $this->footerOptions['class'] = 'col-md-12 col-sm-12 col-xs-12 results-block t-resultFooter ' . $footerClasses;

        $this->progress_bar_class = $this->progress_bar_class ? $this->progress_bar_class : 'results-progress';

        $this->result_tbl_size = $this->result_tbl_size ? $this->result_tbl_size : 20;
        $this->result_tbl_classes = $this->result_tbl_classes ? $this->result_tbl_classes : 'table table-unbordered table-hovered panel-table table-striped';

        $this->registerScripts();

        parent::init();
    }

    /**
     * Registers the JS and CSS Files
     *
     * @return void
     */
    protected function registerScripts() {
        $basePath=Yii::getPathOfAlias('tsearch.result.assets');
        $baseUrl = Yii::app()->getAssetManager()->publish($basePath);

        $cs=Yii::app()->getClientScript();
        $cs->registerCssFile($baseUrl . '/result.css');
        $cs->registerScriptFile($baseUrl . '/result.js');

        $settings = [
            'result_url' => $this->result_url,
            'size' => $this->result_tbl_size,
            'tbl_classes' => $this->result_tbl_classes,
            'headers' => $this->result_tbl_headers,
            'row' => $this->result_tbl_row,
            'result_error_func' => $this->result_error_func,
        ];

        $cs->registerScript( uniqid(), 'window.TResult = new window._TResult("' . $this->getId() . '", "' . $this->operators_count . '", ' . CJavaScript::encode($settings) . ');', CClientScript::POS_READY );
    }

    /**
     * Run
     */
    public function run(){?>
        <div id="<?=$this->getId()?>">
            <div class="row" id="resultToursSearch" style="display: none;">

                <? echo CHtml::openTag('div', $this->headerOptions); ?>

                   <div class="progress">
                       <div class="progress-bar <?=$this->progress_bar_class?> t-resultProgress" role="progressbar" aria-valuemax="100" style="width: 100%">
                       </div>
                   </div>

                    <div class="results-pager pager-block t-toursPager">
                    </div>

                <div class="t-resultAmount results-amount">
                       Найдено: <span class="t-showResult">0 туров</span>
                       <a href="#" class="t-refreshResult"><i class="fa fa-arrow-down"></i> Вывести все туры</a>
                   </div>

                <? echo CHtml::closeTag('div') ?>

                <? echo CHtml::tag('div', $this->mainOptions, true); ?>

                <div class="col-md-12 col-sm-12 col-xs-12"><hr></div>

                <? echo CHtml::openTag('div', $this->footerOptions); ?>
                    <div class="results-pager results-pager-alt t-toursResultPager">
                        <a href="#" ps="100">100</a>
                        <a href="#" ps="50">50</a>
                        <a href="#" ps="20">20</a>
                        <a href="#" ps="10" class="active">10</a>
                        <span>Выводить по</span>
                    </div>
                    <div class="results-pager pager-block t-toursPager">
                    </div>
                <? echo CHtml::closeTag('div') ?>

            </div>
        </div><?
    }

}