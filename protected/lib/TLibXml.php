<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 13.09.14
 * Time: 14:13
 * To change this template use File | Settings | File Templates.
 */

class TLibXml {

    public static function getData($data, $dataIsUrl=false){
        libxml_use_internal_errors(true);

        if( $dataIsUrl ){
            $xmlData = simplexml_load_file($data);
        } else {
            $xmlData = simplexml_load_string($data);
        }

        if (!$xmlData) {
            $errors = libxml_get_errors();
            if( !empty($errors) ) {
                TLibXml::notifyAdminAboutError($errors);
            }
        }

        return $xmlData;
    }

    private static function notifyAdminAboutError($errors){
        $body = '';
        foreach($errors as $error) {
            $body .= "\t" . $error->message;
        }

        // TODO: Проверить ошибки, и не отсылать их постоянно на емейл, - записывать в логи.
        if( $body ) {
            TNotify::notifyAdminByMail('Ошибка загрузки XML', $body, 'TLibXml');
        }
    }

}