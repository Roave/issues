<?php
class Default_Form_Label_Create extends Default_Form_Label_Base
{
    public function init()
    {
        parent::init();
        //$this->removeElement('color');
        $this->setAttrib('id', 'newLabelForm');
        $this->setAttrib('class', 'floatRight');
        $this->getElement('submit')->setAttrib('id', 'newLabelButton');
        $this->getElement('text')->setAttrib('id', 'newLabelInput');
        $this->getElement('submit')->setLabel('&nbsp;');
        $this->getElement('text')->setLabel('');
        $this->setDecorators(array(
            'FormElements',
            array('Description', array('placement' => 'prepend', 'class' => 'error')),
            'Form'
        ));
    }
}
