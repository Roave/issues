<?php
class Default_Form_User_Register extends Default_Form_User_Base
{
    public function init()
    {
        parent::init();
        $this->removeElement('userId');
        $this->getElement('submit')->setLabel($this->translate('register'));
        $this->removeElement('role');
    }
}
