<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Arti
 * Date: 13.09.14
 * Time: 18:19
 * To change this template use File | Settings | File Templates.
 */

class TNotify{

    public static function notifyAdminByMail($subject, $body, $name=''){

        if( $name ) {
            $name = Yii::app()->name . ': ' . $name;
        }

        self::notifyByMail(Yii::app()->params['adminEmail'], $subject, $body, 'noreply@xtourism', $name);
    }

    /**
     * Notifies admin
     * @param string|array $email
     * @param string $subject
     * @param string $body
     * @param string $replyTo
     * @param string $name
     */
    public static function notifyByMail($email, $subject, $body, $replyTo='noreply@xtourism', $name=''){

        if(!$name){
            $name = Yii::app()->name;
        }

        $name='=?UTF-8?B?' . base64_encode($name) . '?=';
        $subject='=?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers="From: $name <$replyTo>\r\n".
            "Reply-To: $replyTo\r\n".
            "MIME-Version: 1.0\r\n".
            "Content-Type: text/html; charset=UTF-8";

        if( !is_array($email) ){
            $email = array($email);
        }

        foreach($email as $_email) {
            mail($_email, $subject, $body, $headers);
        }
    }

    /**
     * Notifies agent about showcase tour request
     * @param integer $agent_id
     * @param integer $tour_id
     * @param RequestForm $request
     */
    public static function notifyAgentAboutShowcaseTourRequest($agent_id, $tour_id, $request){
        $agent = ArUsers::model()->findByPk($agent_id);
        $tour = ArTourShowcaseTours::model()->with(['hotel', 'operator'])->findByPk($tour_id);

        if( $agent && $request && $tour ) {

            $body = "<div style='background-color: #d3d3d3; padding: 40px 100px 40px 100px; '>
                        <div style='background-color: #FFFFFF !important; padding: 20px;'>
                            <h3 style='font-size: 24px; margin-bottom: 10px; font-weight: bold; line-height: 1.1;'>
                                Заявка на тур:
                                <a href='" . TUtil::createFullUrl('FrontTourShowcase/tourInfo', ['id' => (int)$agent->id, 'tid' => (int)$tour->id]) . "' style='color: #337ab7; text-decoration: none;' target='_blank'>
                                    " . htmlspecialchars($tour->hotel->name) . "
                                </a>
                             </h3>
                             <h4>Туроператор: <span style='text-transform: uppercase;'>{$tour->operator->name}</span></h4>
                             <br/>
                             <table style='width: 100%; max-width: 100%;   border-spacing: 0; border-collapse: collapse;'>
                                 <tbody style='display: table-row-group; vertical-align: middle;'>
                                 <tr style='display: table-row; background-color: #ffffff;'>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'><b>Имя</b></td>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'>{$request->name}</td>
                                 </tr>
                                 <tr style='display: table-row; background-color: #f9f9f9;'>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'><b>Телефон</b></td>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'>{$request->phone}</td>
                                 </tr>
                                 <tr style='display: table-row; background-color: #ffffff;'>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'><b>Email</b></td>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'>{$request->email}</td>
                                 </tr>
                                 <tr style='display: table-row; background-color: #f9f9f9;'>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'><b>Комментарий</b></td>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'>{$request->comment}</td>
                                 </tr>
                                 </tbody>
                             </table>
                         </div>
                     </div>";

            self::notifyByMail($agent->email, Yii::app()->name . ' - Новая заявка на тур.', $body, Yii::app()->params['shopEmail']);
        }
    }

    /**
     * Notifies agent about showcase tour request
     * @param RequestForm $request
     * @param array $extra
     */
    public static function notifyAgentAboutSearcherTourRequest($request, $extra){
        $agent = ArUsers::model()->findByPk($extra['agent']);
        $operator = ArOperators::model()->findByPk($extra['oid']);
        $hotel = ArDirHotels::model()->findByPk($extra['hid']);

        if( $agent && $request && $operator && $hotel ) {

            $body = "<div style='background-color: #d3d3d3; padding: 40px 100px 40px 100px; '>
                        <div style='background-color: #FFFFFF !important; padding: 20px;'>
                            <h3 style='font-size: 24px; margin-bottom: 10px; font-weight: bold; line-height: 1.1;'>
                                Заявка на тур:
                                <a href='" . TUtil::createFullUrl('FrontSearcher/tourRequest', ['id' => $extra['agent'], 'oid' => $extra['oid'], 'tid' => $extra['tid'], 'hid' => $extra['hid']]) . "' style='color: #337ab7; text-decoration: none;' target='_blank'>
                                    " . htmlspecialchars($hotel->name) . "
                                </a>
                             </h3>
                             <h4>Туроператор: <span style='text-transform: uppercase;'>{$operator->name}</span></h4>
                             <br/>
                             <table style='width: 100%; max-width: 100%;   border-spacing: 0; border-collapse: collapse;'>
                                 <tbody style='display: table-row-group; vertical-align: middle;'>
                                 <tr style='display: table-row; background-color: #ffffff;'>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'><b>Имя</b></td>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'>{$request->name}</td>
                                 </tr>
                                 <tr style='display: table-row; background-color: #f9f9f9;'>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'><b>Телефон</b></td>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'>{$request->phone}</td>
                                 </tr>
                                 <tr style='display: table-row; background-color: #ffffff;'>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'><b>Email</b></td>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'>{$request->email}</td>
                                 </tr>
                                 <tr style='display: table-row; background-color: #f9f9f9;'>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'><b>Комментарий</b></td>
                                     <td style='padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;'>{$request->comment}</td>
                                 </tr>
                                 </tbody>
                             </table>
                         </div>
                     </div>";

            self::notifyByMail($agent->email, Yii::app()->name . ' - Новая заявка на тур.', $body, Yii::app()->params['shopEmail']);
        }
    }

    /**
     * Notifies agent about accepting
     * @param integer $agent
     */
    public static function notifyAgentAboutAccepting($agent){

        if( $agent instanceof ArUsers ) {

            $body = '<p style="font-size: 21px; margin-bottom: 20px; font-weight: 300; line-height: 1.4;">
                        Здравствуйте, ' . $agent->name . ' ' . $agent->lastname . '!
                        <br/>
                    </p>

                    <span style="font-size: 16px;">Поздравляем, Ваша регистрация был успешно подтверждена! </span><br/>

                    <span style="font-size: 16px;">
                        Теперь Вы можете заходить на наш сайт
                        <a href="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl .'" target="_blank">' . Yii::app()->name . '</a>,
                        под логином и паролем, который был выслан ранее Вам на почту.
                    <br/>Спасибо, за то, что выбрали наш сайт!</span>';

            self::notifyByMail($agent->email, Yii::app()->name . ' - Подтверждение турагента.', $body);
        }
    }

    /**
     * Notifies agent about registration
     * @param ArUsers $agent
     * @param string $password
     */
    public static function notifyAgentAboutRegistration($agent, $password){

        if( $agent instanceof ArUsers ) {

            $body = '<p style="font-size: 21px; margin-bottom: 20px; font-weight: 300; line-height: 1.4;">
                        Здравствуйте, ' . $agent->name . ' ' . $agent->lastname . '!
                        <br/>
                    </p>

                    <span style="font-size: 16px;">Вы успешно зарегистрировались на сайте <a href="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl .'" target="_blank">' . Yii::app()->name . '</a></span><br/>

                    <hr style="margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

                    <span style="font-size: 15px; color: #777;">Логин(e-mail):</span> <code style="font-size: 14px; padding: 2px 4px; color: #c7254e;   background-color: #f9f2f4; font-family: Menlo, Monaco, Consolas, Courier New, monospace;">' . $agent->email . '</code>
                    <br/>

                    <span style="font-size: 15px; color: #777;">Пароль:</span> <code style="font-size: 14px; padding: 2px 4px; color: #c7254e;   background-color: #f9f2f4; font-family: Menlo, Monaco, Consolas, Courier New, monospace;">' . $password . '</code>
                    <br/>

                    <hr style="margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

                    <span style="font-size: 16px;">Скоро наш менеджер свяжется с Вами, для подтверждения Ваших данных.<br/>Спасибо, за то, что выбрали наш сайт!</span>';

            self::notifyByMail($agent->email, Yii::app()->name . ' - Регистрация турагента.', $body);
            self::notifyAdminAboutRegistration($agent);
        }
    }

    /**
     * Notifies admin about registration
     * @param ArUsers $agent
     */
    private static function notifyAdminAboutRegistration($agent){

        if( $agent instanceof ArUsers ) {

            $body = '<span style="font-size: 16px;">
                        Новый <a href="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl .'/admin_diar_1017.php/Users/profile/' . $agent->id . '" target="_blank">турагент</a> только что был зарегистрирован и ожидает подтверждения.
                    </span>';

            self::notifyByMail([Yii::app()->params['shopEmail'], Yii::app()->params['adminEmail']], Yii::app()->name . ' - Регистрация турагента.', $body);
        }
    }


    /**
     * Notifies user about changing in his account
     * @param ArUsers $user
     * @param string $password
     */
    public static function notifyUserAboutChanging($user, $password){

        if( $user instanceof ArUsers ) {

            $url = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . ($user->role == ArUsers::ROLE_AGENT ? '' : '/admin_diar_1017.php');

            $body = '<p style="font-size: 21px; margin-bottom: 20px; font-weight: 300; line-height: 1.4;">
                        Здравствуйте, ' . $user->name . ' ' . $user->lastname . '!
                        <br/>
                    </p>

                    <span style="font-size: 16px;">Ваша учетная запись была изменена на сайте <a href="' . $url . '" target="_blank">' . Yii::app()->name . '</a></span><br/>

                    <hr style="margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

                    <span style="font-size: 15px; color: #777;">Логин(e-mail):</span> <code style="font-size: 14px; padding: 2px 4px; color: #c7254e;   background-color: #f9f2f4; font-family: Menlo, Monaco, Consolas, Courier New, monospace;">' . $user->email . '</code>
                    <br/>

                    <span style="font-size: 15px; color: #777;">Пароль:</span> <code style="font-size: 14px; padding: 2px 4px; color: #c7254e;   background-color: #f9f2f4; font-family: Menlo, Monaco, Consolas, Courier New, monospace;">' . $password . '</code>
                    <br/>

                    <hr style="margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

                    <span style="font-size: 16px;">С уважением, ' . Yii::app()->name . '.</span>';

            self::notifyByMail($user->email, Yii::app()->name . ' - Изменение учетной записи.', $body);
        }
    }


    /**
     * Notifies user about creating account
     * @param ArUsers $user
     * @param string $password
     */
    public static function notifyUserAboutCreatingAccount($user, $password){

        if( $user instanceof ArUsers ) {

            $url = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . ($user->role == ArUsers::ROLE_AGENT ? '' : '/admin_diar_1017.php');

            $body = '<p style="font-size: 21px; margin-bottom: 20px; font-weight: 300; line-height: 1.4;">
                        Здравствуйте, ' . $user->name . ' ' . $user->lastname . '!
                        <br/>
                    </p>

                    <span style="font-size: 16px;">На сайте <a href="' . $url .'" target="_blank">' . Yii::app()->name . '</a> для Вас была создана учетная запись.</span><br/>

                    <hr style="margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

                    <span style="font-size: 15px; color: #777;">Логин(e-mail):</span> <code style="font-size: 14px; padding: 2px 4px; color: #c7254e;   background-color: #f9f2f4; font-family: Menlo, Monaco, Consolas, Courier New, monospace;">' . $user->email . '</code>
                    <br/>

                    <span style="font-size: 15px; color: #777;">Пароль:</span> <code style="font-size: 14px; padding: 2px 4px; color: #c7254e;   background-color: #f9f2f4; font-family: Menlo, Monaco, Consolas, Courier New, monospace;">' . $password . '</code>
                    <br/>

                    <hr style="margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

                    <span style="font-size: 16px;">С уважением, ' . Yii::app()->name . '.</span>';

            self::notifyByMail($user->email, Yii::app()->name . ' - Создание учетной записи.', $body);
        }
    }

    /**
     * Notifies admins about
     * @param ArUsers $user
     * @param string $domain
     */
    public static function notifyAdminAboutDomainRequest($user, $domain){

        if( $user instanceof ArUsers ) {

            $body = '<div style=\'background-color: #d3d3d3; padding: 40px 100px 40px 100px; \'>
                        <div style=\'background-color: #FFFFFF !important; padding: 20px;\'>
                            <h3 style=\'font-size: 24px; margin-bottom: 10px; font-weight: bold; line-height: 1.1;\'>
                                Заявка на домен                             
                             </h3>
                             
                             <table style=\'width: 100%; max-width: 100%;   border-spacing: 0; border-collapse: collapse;\'>
                                 <tbody style=\'display: table-row-group; vertical-align: middle;\'>
                                 <tr style=\'display: table-row; background-color: #ffffff;\'>
                                     <td style=\'padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;\'><b>Имя</b></td>
                                     <td style=\'padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;\'>' . $user->userName() . '</td>
                                 </tr>
                                 <tr style=\'display: table-row; background-color: #f9f9f9;\'>
                                     <td style=\'padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;\'><b>Телефон</b></td>
                                     <td style=\'padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;\'>' . $user->phone . '</td>
                                 </tr>
                                 <tr style=\'display: table-row; background-color: #ffffff;\'>
                                     <td style=\'padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;\'><b>Email</b></td>
                                     <td style=\'padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;\'>' . $user->email . '</td>
                                 </tr>
                                 <tr style=\'display: table-row; background-color: #f9f9f9;\'>
                                     <td style=\'padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;\'><b>Домен</b></td>
                                     <td style=\'padding: 8px; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;\'>' . $domain . '</td>
                                 </tr>
                                 </tbody>
                             </table>
                         </div>
                     </div>';

            self::notifyByMail([Yii::app()->params['shopEmail'], Yii::app()->params['adminEmail']], Yii::app()->name . ' - Заявка на домен.', $body);
        }
    }
}