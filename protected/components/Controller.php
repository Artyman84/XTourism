<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	//public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();


    /**
     * Init
     */
    public function init(){
        $this->pageTitle = Yii::app()->name;
        parent::init();
    }

    /**
     * Shows flash message
     * @param string $name
     */
    public function showFlashMessage($name){
        if( Yii::app()->user->hasFlash($name) ){
            echo Yii::app()->user->getFlash($name);
        }
    }

    /**
     * Returns flash message
     * @param string $class
     * @param string $message
     * @param bool $close
     * @return string $msg
     */
    public function flashMessage($class, $message, $close=true) {
        $msg =  '<div class="alert alert-' . $class . ' alert-dismissable text-center">';
        if ($close) {
            $msg .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        }
        $msg .= $message;
        $msg .= '</div>';

        return $msg;
    }

    /**
     * Development state message
     * @param bool $return
     * @return string
     */
    public function developmentStageMsg($return=false){
        $msg = '<div class="alert alert-info alert-dismissable lead text-center">
                    <strong><span class="glyphicon glyphicon-warning-sign"></span></strong> Страница находится на стадии разработки.
                </div>';

        if( $return ){
            return $msg;
        } else {
            echo $msg;
        }
    }

    /**
     * Decorator
     * @param string $name
     * @param string $content
     * @param array $params
     */
    public function decorator($name, $content, $params=[]){
        $this->beginContent('application.views.decorators.' . $name, $params);
        echo $content;
        $this->endContent();
    }

    /**
     * Registers the CSS File
     * @param string $file
     * @param string $path
     * @param string $media
     */
    protected function addCssFile($file='', $path='', $media='screen'){
        if( $path != '' ){
            $path = Yii::getPathOfAlias($path);
        } else {
            $path = $this->getViewPath();
        }

        if( $file == '' ){
            $file = $this->getAction()->getId();
        }

        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish($path . DIRECTORY_SEPARATOR . "$file.css"),
            $media
        );
    }

    /**
     * Registers the JS File
     * @param string $file
     * @param string $path
     */
    protected function addJsFile($file='', $path=''){

        if( $path != '' ){
            $path = Yii::getPathOfAlias($path);
        } else {
            $path = $this->getViewPath();
        }

        if( $file == '' ){
            $file = $this->getAction()->getId();
        }

        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish($path . DIRECTORY_SEPARATOR . "$file.js")
        );
    }
}