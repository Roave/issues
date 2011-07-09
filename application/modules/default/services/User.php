<?php
class Default_Service_User extends Issues_ServiceAbstract 
{
    protected $_loginForm;
    protected $_registerForm;
    protected $_authAdapter;
    protected $_userModel;
    protected $_auth;

    public function authenticate($username, $password)
    {
        $adapter = $this->getAuthAdapter($username, $password);
        $auth    = $this->getAuth();
        $result  = $auth->authenticate($adapter);
        if (!$result->isValid()) {
            return false;
        }
        $this->_userModel = $this->_mapper->getUserByUsername($username, true);
        $roles = Zend_Registry::get('Default_DiContainer')->getRoleService()->getRolesByUser($this->_userModel);
        $this->_userModel->setRoles($roles);
        $auth->getStorage()->write($this->_userModel);
        $this->_mapper->updateLastLogin($this->_userModel);
        return true;
    }

    public function getAuth()
    {
        if (null === $this->_auth) {
            $this->_auth = Zend_Auth::getInstance();
        }
        return $this->_auth;
    }

    public function getIdentity()
    {
        $auth = $this->getAuth();
        if ($auth->hasIdentity()) {
            return $auth->getIdentity();
        }
        $auth->getStorage()->write(new Default_Model_User(array(
            'roles' => array(
                new Default_Model_Role(array(
                    'role_id'   => 1,
                    'name' => 'guest'    
                )),
            ),
            'settings' => $this->getDefaultUserSettings()
        )));
        return $auth->getIdentity();
    }

    public function getDefaultUserSettings()
    {
        return array(
            'date-format'   => 'F jS, Y G:i:s',
            'timezone'      => 'America/Phoenix'
        );
    }
    
    public function setAuthAdapter(Zend_Auth_Adapter_Interface $adapter)
    {
        $this->_authAdapter = $adapter;
    }
    
    public function getAuthAdapter($username, $password)
    {
        if (null === $this->_authAdapter) {
            $authAdapter = new Zend_Auth_Adapter_DbTable(
                Zend_Registry::get('Default_DiContainer')
                       ->getUserMapper()
                       ->getReadAdapter(),
                $this->_mapper->getTableName(),
                'username',
                'password',
                'SHA1(CONCAT(?,"somes@lt"))'
            );
            $this->setAuthAdapter($authAdapter);
            $this->_authAdapter->setIdentity($username);
            $this->_authAdapter->setCredential($password);
        }
        return $this->_authAdapter;
    }
    
    public function logout()
    {
        $this->getAuth()->clearIdentity();
    }

    public function getLoginForm()
    {
        if (null === $this->_loginForm) {
            $this->_loginForm = new Default_Form_User_Login();
        }
        return $this->_loginForm;
    }

    public function setLoginForm(Zend_Form $form)
    {
        $this->_loginForm = $form;
    }

    public function getRegisterForm()
    {
        if (null === $this->_registerForm) {
            $this->_registerForm = new Default_Form_User_Register();
        }
        return $this->_registerForm;
    }

    public function createFromForm(Default_Form_User_Register $form)
    {
        $acl = Zend_Registry::get('Default_DiContainer')->getAclService();
        if (!$acl->isAllowed('user', 'register')) {
            return false;
        }

        $user = new Default_Model_User();
        $user->setUsername($form->getValue('username'))
            ->setPassword($form->getValue('password'))
            ->addRole(2);
        $userId = $this->_mapper->insert($user);
        $this->insertDefaultUserSettings($userId);

        return $userId;
    }

    public function insertDefaultUserSettings($userId)
    {
        foreach ($this->getDefaultUserSettings() as $k => $v) {
            $this->insertUserSetting($userId, $k, $v);
        }
    }

    public function getAllUsers()
    {
        return $this->_mapper->getAllUsers();
    }

    public function getUsersForSelect(array $users = null)
    {
        if ($users === null) {
            $users = $this->getAllUsers();
        }

        $result = array();
        foreach ($users as $u) {
            $result[$u->getUserId()] = $u->getUsername();
        }

        return $result;
    }

    public function getUserSettings($user)
    {
        return $this->_mapper->getUserSettings($user);
    }

    public function insertUserSetting($user, $key, $value)
    {
        if (!$this->canEditUser($user)) {
            return false;
        }

        return $this->_mapper->insertUserSetting($user, $key, $value);
    }

    public function updateUserSetting($user, $key, $value)
    {
        if (!$this->canEditUser($user)) {
            return false;
        }

        return $this->_mapper->updateUserSetting($user, $key, $value);
    }

    public function getActiveTimezone()
    {
        if (!$timezone = $this->getIdentity()->getSetting('timezone')) {
            $defaults = $this->getDefaultUserSettings();
            $timezone = $defaults['timezone'];
        }
        return $timezone;
    }

    public function canEditUser($user)
    {
        $acl = Zend_Registry::get('Default_DiContainer')->getAclService();
        if ($acl->isAllowed('user', 'edit-all')) {
            return true;
        }

        $identity = Zend_Registry::get('Default_DiContainer')
            ->getUserService()->getIdentity();

        if ($acl->isAllowed('user', 'edit-self')) {
            if ($user->getUserId() == $identity->getUserId()) {
                return true;
            }
        }

        return false;
    }
}
