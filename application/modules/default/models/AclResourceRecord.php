<?php
class Default_Model_AclResourceRecord extends Issues_Model_Abstract
{
    /**
     * _roleId 
     * 
     * @var float
     */
    protected $_roleId;

    /**
     * _resourceType 
     * 
     * @var string
     */
    protected $_resourceType;

    /**
     * _resourceId 
     * 
     * @var float
     */
    protected $_resourceId;

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
     * Get resourceType.
     *
     * @return resourceType
     */
    public function getResourceType()
    {
        return $this->_resourceType;
    }
 
    /**
     * Set resourceType.
     *
     * @param $resourceType the value to be set
     */
    public function setResourceType($resourceType)
    {
        $this->_resourceType = $resourceType;
        return $this;
    }
 
    /**
     * Get resourceId.
     *
     * @return resourceId
     */
    public function getResourceId()
    {
        return $this->_resourceId;
    }
 
    /**
     * Set resourceId.
     *
     * @param $resourceId the value to be set
     */
    public function setResourceId($resourceId)
    {
        $this->_resourceId = $resourceId;
        return $this;
    }
}
