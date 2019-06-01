<?php
/**
 * Extension for catching FATAL errors
 *
 * 'preload'=>array('fatalerrorcatch',...),
 *  ...
 * 'components'=>array(
 *   ...
 *   'fatalerrorcatch'=>array(
 *     'class'=>'ext.error.FatalErrorCatch',
 *   ),
 *
 */
class FatalErrorCatch extends CApplicationComponent {


    /**
     * Errors types that we want to catch
     * @var array
     */
    public $errorTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING];

    /**
     * Init
     */
    public function init() {
        register_shutdown_function([$this, 'shutdownHandler']);
        return parent::init();
    }

    /**
     * Error handler
     */
    public function shutdownHandler() {
        $e = error_get_last();
        if ($e !== null && in_array($e['type'], $this->errorTypes)) {
            $msg = 'Fatal error: ' . $e['message'];
            Yii::app()->handleError($e['type'], $msg, $e['file'], $e['line']);
        }
    }
}