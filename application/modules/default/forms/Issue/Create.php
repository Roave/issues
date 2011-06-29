<?php
class Default_Form_Issue_Create extends Default_Form_Issue_Base
{
    public function init()
    {
        parent::init();
        $this->getElement('submit')->setLabel($this->translate('create_issue'));
    }
}
