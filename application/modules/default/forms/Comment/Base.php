<?php
class Default_Form_Comment_Base extends Issues_FormAbstract
{
    public function init()
    {
        $this->addElement('textarea', 'text', array(
            'filters'       => array('StringTrim', 'HtmlEntities'),
            'validators'    => array(
                array('StringLength', true, array(10))
            ),
            'required'      => true,
            'label'         => $this->translate('comment')
        ));

        $this->addSubForm(new Default_Form_Permission_Base(), 'permissions');

        $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'decorators' => array('ViewHelper',array('HtmlTag', array('tag' => 'dd', 'id' => 'form-submit')))
        ));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'form')),
            array('Description', array('placement' => 'prepend', 'class' => 'error')),
            'Form'
        )); 
    }
}
