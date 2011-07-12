<?php
class Default_Form_Permission_Base extends Issues_Form_SubForm
{
    public function init()
    {
        $this->setLabel('Permissions');

        $this->addElement('checkbox', 'private', array(
            'label'             => 'Private?',
        ));

        $this->addElement(new Issues_Form_Element_RoleSelect('roles', array(
            'label'         => 'Roles',
            'allowEmpty'    => true,
            'validators'    => array(
                new Issues_Validate_FieldDepends('private', 'checked')
            )
        )));
    }
}
