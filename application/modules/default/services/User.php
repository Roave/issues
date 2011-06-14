<?php
class Default_Service_User 
{
    protected $_loginForm;
    protected $_registerForm;
    protected $_mapper;
    protected $_authAdapter;
    protected $_userModel;
    protected $_auth;

    public function __construct(Default_Model_Mapper_User $userMapper = null)
    {
        $this->_mapper = null === $userMapper ? new Default_Model_Mapper_User() : $userMapper;
    }

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
            'role' => 'guest'
        )));
        return $auth->getIdentity();
    }
    
    public function logout()
    {
        $this->getAuth()->clearIdentity();
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
                'user',
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

    public function getLoginForm()
    {
        if (null === $this->_loginForm) {
            $this->_loginForm = new Default_Form_User_Login();
        }
        return $this->_loginForm;
    }

    public function getRegisterForm()
    {
        if (null === $this->_registerForm) {
            $this->_registerForm = new Default_Form_User_Register();
        }
        return $this->_registerForm;
    }

    public function emailExists($email)
    {
        if (false === $this->_mapper->getUserByEmail($email)){
            return false;
        }
        return true;
    }
}
