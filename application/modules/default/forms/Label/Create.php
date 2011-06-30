<?php
class Default_Form_Label_Create extends Default_Form_Label_Base
{
    public function init()
    {
        parent::init();
        $this->getElement('submit')->setLabel('Save Label');
    }
}
