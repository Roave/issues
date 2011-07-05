<?php
class Default_Model_AclRecord extends Issues_Model_Abstract
{
    /**
     * _roleId 
     * 
     * @var int
     */
    protected $_roleId;

    /**
     * _resource 
     * 
     * @var string
     */
    protected $_resource = "";

    /**
     * _action 
     * 
     * @var string
     */
    protected $_action = "";

    /**
     * _type 
     * 
     * @var string
     */
    protected $_type = "";
 
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
     * Get resource.
     *
     * @return resource
     */
    public function getResource()
    {
        return $this->_resource;
    }
 
    /**
     * Set resource.
     *
     * @param $resource the value to be set
     */
    public function setResource($resource)
    {
        $this->_resource = $resource;
        return $this;
    }
 
    /**
     * Get action.
     *
     * @return action
     */
    public function getAction()
    {
        return $this->_action;
    }
 
    /**
     * Set action.
     *
     * @param $action the value to be set
     */
    public function setAction($action)
    {
        $this->_action = $action;
        return $this;
    }
 
    /**
     * Get type.
     *
     * @return type
     */
    public function getType()
    {
        return $this->_type;
    }
 
    /**
     * Set type.
     *
     * @param $type the value to be set
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }
}
