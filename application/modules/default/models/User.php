<?php
class Default_Model_User extends Issues_Model_Abstract implements Zend_Acl_Resource_Interface
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
     * @var Default_Model_Role
     */
    protected $_roles;

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
     * Array of settings (key => value)
     *
     * @array
     */
    protected $_settings;

    /**
     * __construct 
     * 
     * @param mixed $options 
     * @return void
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        if (!isset($this->_roles)) {
            $this->_roles = array();
            if ($this->getUserId()) {
                $roles = Zend_Registry::get('Default_DiContainer')->getRoleService()->getRolesByUser($this);
                foreach ($roles as $r) {
                    $this->_roles[$r->getRoleId()] = $r;
                }
            }
        }
    }
 
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
        $this->_userId = (int) $userId;
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
     * Add a role to this user
     *
     * @param int|Default_Model_Role $role
     */
    public function addRole($role)
    {
        $role = Zend_Registry::get('Default_DiContainer')->getRoleService()->getRoleDetect($role);
        $this->_roles[$role->getRoleId()] = $role;

        return $this;
    }

    /**
     * hasRole 
     * 
     * @param Default_Model_Role|int|string $role 
     * @return void
     */
    public function hasRole($role)
    {
        $role = Zend_Registry::get('Default_DiContainer')->getRoleService()->getRoleDetect($role);
        foreach ($this->_roles as $r) {
            if ($role->getName() === $r->getName()) {
                return true;
            }
        }

        return false;
    }
 
    /**
     * Get all roles.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->_roles;
    }
 
    /**
     * Set role.
     *
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $this->_roles = Zend_Registry::get('Default_DiContainer')->getRoleService()->getRoleDetect($roles);
        return $this;
    }
 
    /**
     * Get lastLogin.
     *
     * @return lastLogin
     */
    public function getLastLogin()
    {
        return $this->_adjustedDateTime($this->_lastLogin);
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
        return $this->_adjustedDateTime($this->_registerTime);
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
 
    /**
     * Get settings.
     *
     * @return settings
     */
    public function getSetting($key)
    {
        if (isset($this->_settings[$key])) {
            return $this->_settings[$key];
        } else {
            return false;
        }
    }
 
    /**
     * Set settings.
     *
     * @param $key the setting to set
     * @param $value the value to set
     */
    public function setSetting($key, $value)
    {
        $this->_settings[$key] = $value;
        return $this;
    }
 
    /**
     * Get settings.
     *
     * @return settings
     */
    public function getSettings()
    {
        return $this->_settings;
    }
 
    /**
     * Set settings.
     *
     * @param $settings the value to be set
     */
    public function setSettings($settings)
    {
        $this->_settings = $settings;
        return $this;
    }

    /**
     * getResourceId 
     * 
     * @return string
     */
    public function getResourceId()
    {
        return 'user-' . $this->getUserId();
    }
}
