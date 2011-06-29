<?php
class Default_Model_User extends Issues_Model_Abstract
{
    /**
     * User ID
     *
     * @var int
     */
    protected $_userId;

    /**
     * Username
     *
     * @var string
     */
    protected $_username;

    /**
     * Password
     *
     * @var string
     */
    protected $_password;

    /**
     * Role
     *
     * @var Auth_Model_Role
     */
    protected $_role;

    /**
     * Last login date/time
     *
     * @var DateTime
     */
    protected $_lastLogin;

    /**
     * Last IP they logged in with
     *
     * @var string
     */
    protected $_lastIp;

    /**
     * Registration date/time
     *
     * @var DateTime
     */
    protected $_registerTime;

    /**
     * IP address they registered with
     *
     * @var string
     */
    protected $_registerIp;
 
    /**
     * Get userId.
     *
     * @return userId
     */
    public function getUserId()
    {
        return $this->_userId;
    }
 
    /**
     * Set userId.
     *
     * @param $userId the value to be set
     */
    public function setUserId($userId)
    {
        $this->_userId = (int)$userId;
        return $this;
    }
 
    /**
     * Get username.
     *
     * @return username
     */
    public function getUsername()
    {
        return $this->_username;
    }
 
    /**
     * Set username.
     *
     * @param $username the value to be set
     */
    public function setUsername($username)
    {
        $this->_username = $username;
        return $this;
    }
 
    /**
     * Get password.
     *
     * @return password
     */
    public function getPassword()
    {
        return $this->_password;
    }
 
    /**
     * Set password.
     *
     * @param $password the value to be set
     */
    public function setPassword($password)
    {
        $this->_password = $password;
        return $this;
    }
 
    /**
     * Get role.
     *
     * @return Auth_Model_Role role
     */
    public function getRole()
    {
        return $this->_role;
    }
 
    /**
     * Set role.
     *
     * @param $role int|Auth_Model_Role the value to be set
     */
    public function setRole($role)
    {
        if ($role instanceof Default_Model_Role) {
            $this->_role = $role;
        } elseif (is_numeric($role)) {
            $this->_role = Zend_Registry::get('Default_DiContainer')->getRoleMapper()->getRoleById((int)$role);
        }
        return $this;
    }
 
    /**
     * Get lastLogin.
     *
     * @return lastLogin
     */
    public function getLastLogin()
    {
        return $this->_lastLogin;
    }
 
    /**
     * Set lastLogin.
     *
     * @param $lastLogin the value to be set
     */
    public function setLastLogin($lastLogin)
    {
        $this->_lastLogin = new DateTime($lastLogin);
    }
 
    /**
     * Get lastIp.
     *
     * @return lastIp
     */
    public function getLastIp()
    {
        return $this->_lastIp;
    }
 
    /**
     * Set lastIp.
     *
     * @param $lastIp the value to be set
     */
    public function setLastIp($lastIp)
    {
        $this->_lastIp = $lastIp;
    }
 
    /**
     * Get registerTime.
     *
     * @return registerTime
     */
    public function getRegisterTime()
    {
        return $this->_registerTime;
    }
 
    /**
     * Set registerTime.
     *
     * @param $registerTime the value to be set
     */
    public function setRegisterTime($registerTime)
    {
        $this->_registerTime = new DateTime($registerTime);
    }
 
    /**
     * Get registerIp.
     *
     * @return registerIp
     */
    public function getRegisterIp()
    {
        return $this->_registerIp;
    }
 
    /**
     * Set registerIp.
     *
     * @param $registerIp the value to be set
     */
    public function setRegisterIp($registerIp)
    {
        $this->_registerIp = $registerIp;
    }
}
