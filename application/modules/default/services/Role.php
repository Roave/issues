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

    public function getRolesByUser($user)
    {
        return $this->_mapper->getRolesByUser($user);
    }

    public function addUserToRole($user, $role)
    {
        return $this->_mapper->addUserToRole($user, $role);
    }
}

