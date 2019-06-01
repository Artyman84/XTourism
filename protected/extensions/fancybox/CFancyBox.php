<?php
class CFancyBox extends CWidget
{
	public $target;
	public $config=array();
	
    public function run()
    {
		$assets = dirname(__FILE__).'/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.mousewheel-3.0.2.pack.js', CClientScript::POS_HEAD);
		Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.fancybox-1.3.1.js', CClientScript::POS_HEAD);
		Yii::app()->clientScript->registerCssFile($baseUrl . '/jquery.fancybox-1.3.1.css');
		$config = CJavaScript::encode($this->config);
		Yii::app()->getClientScript()->registerScript(__CLASS__, "
			$('$this->target').fancybox($config);
		");
	} 
}