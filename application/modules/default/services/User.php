<?php
class Default_Service_User extends Issues_ServiceAbstract 
{
    protected $_loginForm;
    protected $_registerForm;
    protected $_authAdapter;
    protected $_auth;

    public function authenticate($username, $password)
    {
        $userModel = $this->_mapper->getUserByUsername($username, true);
        if (!$userModel) return false;
        $password  = $this->hashPassword($password, $userModel->getSalt());
        $adapter   = $this->getAuthAdapter($username, $password);
        $auth      = $this->getAuth();
        $result    = $auth->authenticate($adapter);
        if (!$result->isValid()) {
            return false;
        }
        $roles = Zend_Registry::get('Default_DiContainer')->getRoleService()->getRolesByUser($userModel);
        $userModel->setRoles($roles);
        $settings = $this->getUserSettings($userModel);
        $userModel->setSettings($settings);
        $userModel->addRole(1); // All users inherit guest permisisons
        $auth->getStorage()->write($userModel);
        $this->_mapper->updateLastLogin($userModel);
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
                'password'
            );
            $this->setAuthAdapter($authAdapter);
        }
        $this->_authAdapter->setIdentity($username)->setCredential($password);
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
             ->setSalt($this->randomBytes(16))
             ->setPassword($this->hashPassword($form->getValue('password'), $user->getSalt()))
             ->addRole(3);

        $user->setSettings($this->getDefaultUserSettings());

        $userId = $this->_mapper->insert($user);

        return $userId;
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

    public function hashPassword($password, $salt)
    {
        return hash('sha512', $password.$salt);
    }

    /**
     * randomBytes 
     *
     * returns X random raw binary bytes
     * 
     * @param int $byteLength 
     * @return string
     */
    public function randomBytes($byteLength)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
           $data = openssl_random_pseudo_bytes($byteLength);
        } elseif (is_readable('/dev/urandom')) {
            $fp = @fopen('/dev/urandom','rb');
            if ($fp !== false) {
                $data = fread($fp, $byteLength);
                fclose($fp);
            }
        } elseif (class_exists('COM')) {
            // @TODO: Someone care to test on Windows? Not it!
            try {
                $capi = new COM('CAPICOM.Utilities.1');
                $data = $capi->GetRandom($btyeLength,0);
            } catch (Exception $ex) {
                // Fail silently
            }
        }
        return $data;
    }
}
