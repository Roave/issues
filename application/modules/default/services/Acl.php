<?php
class Default_Service_Acl extends Issues_ServiceAbstract
{
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
        $this->_mapper = Zend_Registry::get('Default_DiContainer')->getAclMapper();
        $this->_roleService = Zend_Registry::get('Default_DiContainer')->getRoleService();

        $this->_acl = new Zend_Acl();

        $this->_setupRoles();
        $this->_loadAclRecords();
    }

    /**
     * _setupRoles 
     * 
     * @return void
     */
    protected function _setupRoles()
    {
        $this->_roles = $this->_roleService->getAllRoles();
        foreach ($this->_roles as $i) {
            $this->_acl->addRole($i);
        }
        $currentRoles = Zend_Registry::get('Default_DiContainer')->getUserService()
            ->getIdentity()->getRoles();
        
        $this->_acl->addRole(new Zend_Acl_Role('user'), $currentRoles);
    }

    /**
     * _loadAclRecords 
     * 
     * @return void
     */
    protected function _loadAclRecords()
    {
        $records = $this->_mapper->getAllRecords();
        foreach ($records as $i) {
            if ($i->getResource() && !$this->_acl->has($i->getResource())) {
                $this->_acl->addResource($i->getResource());
            }
            if ($i->getType() == 'allow') {
                $this->_acl->allow($i->getRoleId(), $i->getResource() ?: null, $i->getAction() ?: null);
            } else {
                $this->_acl->deny($i->getRoleId(), $i->getResource() ?: null, $i->getAction() ?: null);
            }
        }
    }

    /**
     * @return void
     */
    public function addResource($obj)
    {
        if (!is_object($obj) || $this->_acl->has($obj)) {
            return false;
        }

        $nameParts = explode('_', strtolower(get_class($obj)));
        $simpleName = array_pop($nameParts);

        if (!$this->_acl->has($simpleName)) {
            $this->_acl->addResource(new Zend_Acl_Resource($simpleName));
        }

        $this->_acl->addResource($obj->getResourceId(), $simpleName);

        if ($obj->isPrivate()) {
            $this->_acl->deny(null, $obj->getResourceId(), null, new Default_Model_Acl_HasPermissionAssertion());
        }

        return true;
    }

    /**
     * isAllowed 
     * 
     * @param mixed $role 
     * @param mixed $resource 
     * @param mixed $action 
     * @return void
     */
    public function isAllowed($resource, $action)
    {
        return $this->_acl->isAllowed('user', $resource, $action);
    }

    public function addResourceRecord(array $roles, $resourceType, $resourceId)
    {
        $mapper = Zend_Registry::get('Default_DiContainer')->getAclResourceRecordMapper();
        return $mapper->addResourceRecord($roles, $resourceType, $resourceId);
    }

    public function getResourceRecords(array $roles, $resourceType, $resourceId)
    {
        $mapper = Zend_Registry::get('Default_DiContainer')->getAclResourceRecordMapper();
        return $mapper->getResourceRecords($roles, $resourceType, $resourceId);
    }
}
