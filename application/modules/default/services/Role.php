<?php
class Default_Service_Role extends Issues_ServiceAbstract 
{
    public function getAllRoles()
    {
        return $this->_mapper->getRoles();
    }

    public function getRolesForForm()
    {
        $rows = $this->_mapper->getRoles();
        $return = array();
        foreach($rows as $i => $row){
            $return[$row->getRoleId()] = $row->getName();
        }
        return $return;
    }

    public function getRoleByName($name)
    {
        return $this->_mapper->getRoleByName($name);
    }

    public function getRoleById($roleId)
    {
        return $this->_mapper->getRoleById($roleId);
    }

    public function getRolesByUser($user)
    {
        return $this->_mapper->getRolesByUser($user);
    }

    public function addUserToRole($user, $role)
    {
        $userService = Zend_Registry::get('Default_DiContainer')->getUserService();
        if (!$userService->canEditUser($user)) {
            return false;
        }

        return $this->_mapper->addUserToRole($user, $role);
    }

    public function getRoleDetect($role)
    {
        if (!($role instanceof Default_Model_Role)) {
            if (is_numeric($role)) {
                $role = Zend_Registry::get('Default_DiContainer')->getRoleService()->getRoleById($role);
            } elseif (is_array($role)) {
                foreach ($role as $i => $thisRole) {
                    $role[$i] = $this->getRoleDetect($thisRole);
                }
            } else {
                $role = Zend_Registry::get('Default_DiContainer')->getRoleService()->getRoleByName($role);
            }
        }
        return $role;
    }
}

