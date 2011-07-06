<?php
class Default_Model_Role extends Issues_Model_Abstract
                         implements Zend_Acl_Role_Interface, Zend_Acl_Resource_Interface
{
    /**
     * _roleId 
     * 
     * @var int
     */
    protected $_roleId;

    /**
     * _name 
     * 
     * @var string
     */
    protected $_name;
 
    /**
     * Get roleId.
     *
     * @return roleId
     */
    public function getRoleId()
    {
        return $this->_roleId;
    }
 
    /**
     * Set roleId.
     *
     * @param $roleId the value to be set
     */
    public function setRoleId($roleId)
    {
        $this->_roleId = (int) $roleId;
        return $this;
    }
 
    /**
     * Get name.
     *
     * @return string role name
     */
    public function getName()
    {
        return $this->_name;
    }
 
    /**
     * Set name.
     *
     * @param $name the value to be set
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * __toString 
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getRoleId();
    }

    /**
     * getResourceId 
     * 
     * @return string
     */
    public function getResourceId()
    {
        return 'role-' . $this->getRoleId();
    }
}
