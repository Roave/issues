<?php
class Default_Model_Role extends Issues_Model_Abstract
{
    protected $_roleId;

    protected $_roleName;
 
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
        $this->_roleId = $roleId;
        return $this;
    }
 
    /**
     * Get roleName.
     *
     * @return roleName
     */
    public function getRoleName()
    {
        return $this->_roleName;
    }
 
    /**
     * Set roleName.
     *
     * @param $roleName the value to be set
     */
    public function setRoleName($roleName)
    {
        $this->_roleName = $roleName;
        return $this;
    }
}
