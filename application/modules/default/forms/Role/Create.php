<?php
class Default_Form_Role_Create extends Default_Form_Role_Base
{
    public function init()
    {
        parent::init();
        $this->getElement('submit')->setLabel($this->translate('save_role'));
    }
}
