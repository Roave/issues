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
     * Email
     *
     * @var string
     */
    protected $_email;

    /**
     * Role
     *
     * @var string
     */
    protected $_role;

    /**
     * Get the user ID
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->_userId;
    }

    /**
     * Set the user ID
     *
     * @param int $userId
     *
     * @return Default_Model_User
     */
    public function setUserId($userId)
    {
        $this->_userId = (int)$userId;
    }

    /**
     * Get the username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * Set the username
     *
     * @param string $username
     *
     * @return Default_Model_User
     */
    public function setUsername($username)
    {
        $this->_username = $username;
    }

    /**
     * Set the password
     *
     * @param string $password
     * @param bool $hash Set to true to hash password
     *
     * @return Default_Model_User
     */
    public function setPassword($password, $hash = false)
    {
        if ($hash !== false) {
            $this->_password = sha1($password.'somes@lt');
        } else {
            $this->_password = $password;
        }

        return $this;
    }

    /**
     * Get the hashed password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * Get the email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Set the email
     *
     * @param string $email
     *
     * @return Default_Model_User
     */
    public function setEmail($email)
    {
        $this->_email = $email;

        return $this;
    }

    /**
     * Get the role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->_role;
    }

    /**
     * Set the role
     *
     * @param string $role
     *
     * @return Default_Model_User
     */
    public function setRole($role)
    {
        $this->_role = $role;

        return $this;
    }

}
