<?php
class Issues_Form_Element_RoleSelect extends Zend_Form_Element_Multiselect
{
    public function init()
    {
        $roles = Zend_Registry::get('Default_DiContainer')->getRoleService()
            ->getRolesForForm();
        $this->setMultiOptions($roles);

        $currentRoles = Zend_Registry::get('Default_DiContainer')->getUserService()
            ->getIdentity()->getRoles();
        $this->setValue($currentRoles);

    }
}
