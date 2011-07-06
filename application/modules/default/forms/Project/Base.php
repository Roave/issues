<?php
class Default_Form_Project_Base extends Issues_FormAbstract
{
    public function init()
    {
        $this->addElement('text', 'project_name', array(
            'filters'    => array('StringTrim', 'HtmlEntities'),
            'validators' => array(
                array('StringLength', true, array(2, 255)),
            ),
            'required'   => true,
            'label'      => $this->translate('project_name')
        ));

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
