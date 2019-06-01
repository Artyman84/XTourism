<?php

class UsersController extends BackendController {

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
            'ajaxOnly + enableAgents, deleteAgents, deleteUsers', // we only allow deletion via POST request
            ['application.filters.XssFilter + profile, createUser'],
        );
    }

    /*
    Права доступа
*/
    public function accessRules() {
        return array(
            // даем доступ только админам
            array(
                'allow',
                'roles' => array('admin'),
            ),

            // всем остальным разрешаем посмотреть только на страницу авторизации
            array(
                'allow',
                'actions' => array('profile'),
                'roles' => array('moderator'),
                'expression' => '(Yii::app()->user->id == ($_GET["id"]))',
            ),

            // запрещаем все остальное
            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }


    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model=new ArUsers('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['ArUsers'])) {
            $model->attributes = $_GET['ArUsers'];
        }

        $this->render(
            'index',
            array(
                'model' => $model,
                'search' => $model->search(Yii::app()->user->role == ArUsers::ROLE_ADMIN ? 'notSuperAdmins' : '')
            )
        );
    }

    /**
     * Action "Enable/Disable users"
     */
    public function actionEnableUsers(){
        $ids = (array)Yii::app()->request->getParam('ids', array());
        $enable = (int)Yii::app()->request->getParam('enable', 0);

        ArUsers::model()->updateByPk($ids, array('state' => !$enable), $this->availableRoles() );
    }

    /**
     * Action "Delete Showcases"
     * @throws CHttpException
     */
    public function actionDeleteUsers(){
        $ids = (array)Yii::app()->request->getParam('ids', array());

        /********** Более медленное удаление по AR **********/
        $agents = ArUsers::model()->findAllByAttributes(array('id' => $ids, 'role' => $this->availableRoles(false)));
        $this->deleteUsers($agents);
    }

    /**
     * Action "Decline Showcases"
     * @throws CHttpException
     */
    public function actionDeclineAgents(){
        $ids = (array)Yii::app()->request->getParam('ids', array());

        /********** Более медленное отклонение агента по AR **********/
        $agents = ArUsers::model()->findAllByAttributes(array('id' => $ids, 'role' => 'guest'));
        $this->deleteUsers($agents);

        if( !Yii::app()->request->isAjaxRequest ){
            $this->redirect(Yii::app()->createUrl('Users/index'));
        }
    }

    /**
     * Action "Accept Showcases"
     * @throws CHttpException
     */
    public function actionAcceptAgents(){
        $ids = (array)Yii::app()->request->getParam('ids', array());

        /********** Более медленное подтверждение агента по AR **********/
        $users = ArUsers::model()->findAllByAttributes(array('id' => $ids, 'role' => 'guest'));
        $this->acceptUsers($users);

        if( !Yii::app()->request->isAjaxRequest ){
            $id = $ids[0];
            $url = Yii::app()->createUrl('Users/index') . '#blink=' . $id;
            $this->redirect($url);
        }
    }


    public function actionProfile($id){
        $model = $this->loadModel($id);
        $role = $model->role;
        $user = Yii::app()->user;

        if( $role == ArUsers::ROLE_GUEST ){

            // accept
            $action = 'accept';

        } elseif( $role == ArUsers::ROLE_MODERATOR || $role == ArUsers::ROLE_AGENT ||
                ( $role == ArUsers::ROLE_ADMIN && $user->id == $model->id) ||
                ( $role == ArUsers::ROLE_ADMIN && $user->role == ArUsers::ROLE_SUPERADMIN) ||
                ( $role == ArUsers::ROLE_SUPERADMIN && $user->id == $model->id) ){

            if( isset($_POST['ArUsers']) ){

                $attributes = $_POST['ArUsers'];

                if( !empty( $attributes['password'] ) ){
                    $attributes['password'] = CPasswordHelper::hashPassword($attributes['password']);
                }

                $model->attributes = $attributes;

                if( $model->save() ){

                    $notifyByEmail = Yii::app()->request->getParam('notifyByEmail', 0);
                    if($notifyByEmail){
                        TNotify::notifyUserAboutChanging($model, $_POST['ArUsers']['password']);
                    }

                    // Модераторы не могут видеть список пользователей
                    if( $user->role == ArUsers::ROLE_MODERATOR ){
                        Yii::app()->user->setFlash('moderator_profile', parent::flashMessage('success', 'Ваш профайл был успешно изменен.', true));
                    } else {
                        $url = Yii::app()->createUrl('Users/index') . '#blink=' . $model->id;
                        $this->redirect($url);
                    }
                }
            }

            // edit
            $action = 'edit';

        } else {
            // view
            $action = 'view';
        }


        $this->render('profile/profile_' . $action, array('model' => $model));
    }

    public function actionCreateUser(){
        $model = new ArUsers();
        $model->unsetAttributes();

        if( isset($_POST['ArUsers']) ){

            $attributes = $_POST['ArUsers'];

            if( !empty( $attributes['password'] ) ){
                $attributes['password'] = CPasswordHelper::hashPassword($attributes['password']);
            }

            $model->attributes = $attributes;

            if( $model->save() ){

                $notifyByEmail = Yii::app()->request->getParam('notifyByEmail', 0);
                if($notifyByEmail){
                    TNotify::notifyUserAboutCreatingAccount($model, $_POST['ArUsers']['password']);
                }

                $url = Yii::app()->createUrl('Users/index', array('id' => $model->id)) . '#blink=' . $model->id;
                $this->redirect($url);
            }
        }

        $this->render('profile/profile_edit', array('model' => $model));
    }


    /********************************* Protected *********************************/

    /**
     * Deletes users
     * @param array $users
     */
    protected function deleteUsers($users){
        foreach( $users as $user ){
            $user->delete();
        }
    }

    /**
     * Accepts users
     * @param array $users
     */
    protected function acceptUsers($users){
        foreach( $users as $user ){
            TNotify::notifyAgentAboutAccepting($user);
            $user->role = 'agent';
            $user->state = 0;
            $user->save();
        }
    }

    /**
     * Returns available roles, which current user can to processing
     * @param bool $criteria
     * @return array|CDbCriteria
     */
    protected function availableRoles($criteria=true){
        $availableRoles = [ArUsers::ROLE_GUEST, ArUsers::ROLE_AGENT, ArUsers::ROLE_MODERATOR];
        if( Yii::app()->user->role == ArUsers::ROLE_SUPERADMIN ){
            $availableRoles[] = ArUsers::ROLE_ADMIN;
        }

        if( $criteria ){
            $criteria = new CDbCriteria();
            $criteria->addInCondition('role', $availableRoles);
            $availableRoles = $criteria;
        }

        return $availableRoles;
    }

    /**
     * Returns list of available roles, which current user can to processing
     * @param bool $ucFirst
     * @param bool $self
     * @return array|CDbCriteria
     */
    protected function availableRolesList($ucFirst=true, $self=false){
        $availableRoles = array_flip($this->availableRoles(false));
        foreach($availableRoles as $role => $name){
            $availableRoles[$role] = ArUsers::roleName($role, $ucFirst);
        }

        if( $self ){
            $availableRoles = array(Yii::app()->user->role => ArUsers::roleName(Yii::app()->user->role, $ucFirst)) + $availableRoles;
        }

        return $availableRoles;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ArUsers the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = ArUsers::model()->findByPk($id);
        if($model === null) {
            throw new CHttpException(404, 'Такого пользователя не существует.');
        }

        return $model;
    }

}
