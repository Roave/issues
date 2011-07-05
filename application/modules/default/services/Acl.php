<?php
class Default_Service_Acl extends Issues_ServiceAbstract
{
    /**
     * _resources 
     * 
     * @var array
     */
    protected $_resources = array();

    /**
     * _roles 
     * 
     * @var array
     */
    protected $_roles = array();

    /**
     * _roleService
     * 
     * @var Default_Service_Role
     */
    protected $_roleService;

    /**
     * _acl 
     * 
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     * __construct 
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_roleService = Zend_Registry::get('Default_DiContainer')->getRoleService();
        $this->_roles = $this->_roleService->getAllRoles();

        $this->_acl = new Zend_Acl();
        foreach ($this->_roles as $i) {
            $this->_acl->addRole($i);
        }

        $currentRoles = Zend_Registry::get('Default_DiContainer')->getUserService()
            ->getIdentity()->getRoles();
        
        $this->_acl->addRole(new Zend_Acl_Role('user'), $currentRoles);
    }

    /**
     * getResources 
     * 
     * @return array
     */
    public function getResources()
    {
        return $this->_resources;
    }

    /**
     * getRoles 
     * 
     * @return void
     */
    public function getRoles()
    {
        return $this->_roles;
    }
}
