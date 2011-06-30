<?php
class Default_Service_Role extends Issues_ServiceAbstract 
{
    public function getRolesForForm()
    {
        $rows = $this->_mapper->getRoles();
        $return = array();
        foreach($rows as $i => $row){
            $return[$row['role_id']] = $row['name'];
        }
        return $return;
    }
}

