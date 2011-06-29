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
        $this->_userModel = $this->_mapper->getUserByUsername($username);
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
            'role' => new Default_Model_Role(array(
                'role_id'   => 0,
                'role_name' => 'guest'    
            ))
        )));
        return $auth->getIdentity();
    }
    
    public function setAuthAdapter(Zend_Auth_Adapter_Interface $adapter)
    {
        $this->_authAdapter = $adapter;
    }
    
    public function getAuthAdapter($username, $password)
    {
        if (null === $this->_authAdapter) {
            $authAdapter = new Zend_Auth_Adapter_DbTable(
                Zend_Db_Table_Abstract::getDefaultAdapter(),
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
        //if (Zend_Auth::getInstance()->getIdentity()->getRole() != 'admin') return false;
        $user = new Default_Model_User();
        $user->setUsername($form->getValue('username'))
            ->setPassword($form->getValue('password'))
            ->setRole(1);
        return $this->_mapper->insert($user);
    }
}
