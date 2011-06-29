<?php
class Default_Form_Project_Create extends Default_Form_Project_Base
{
    public function init()
    {
        parent::init();
        $this->getElement('submit')->setLabel($this->translate('create_project'));
    }
}
