<?php
class Default_Form_Milestone_Create extends Default_Form_Milestone_Base
{
    public function init()
    {
        parent::init();
        $this->getElement('submit')->setLabel($this->translate('create_milestone'));
    }
}
