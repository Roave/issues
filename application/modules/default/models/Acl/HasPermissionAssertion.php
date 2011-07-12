<?php
class Default_Model_Acl_HasPermissionAssertion implements Zend_Acl_Assert_Interface
{
    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {
        if (!($resource instanceof Issues_Model_Abstract)) {
            throw new Issues_Model_Exception('Invalid resource for this assertion');
        }

        list($resourceType, $resourceId) = explode('-', $resource->getResourceId());

        if (!$resource->isPrivate()) {
            return $acl->isAllowed($role, $resourceType, $privilege);
        }

        $userService = Zend_Registry::get('Default_DiContainer')->getUserService();
        $userRoles = $userService->getIdentity()->getRoles();

        foreach ($userRoles as $i) {
            $roles[] = $i->getRoleId();
        }

        $aclService = Zend_Registry::get('Default_DiContainer')->getAclService();
        $records = $aclService->getResourceRecords($roles, $resourceType, $resourceId);

        if (count($records)) {
            return false;
        } else {
            return true;
        }
    }
}
