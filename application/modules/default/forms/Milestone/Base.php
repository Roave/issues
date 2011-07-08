<?php
class Default_Form_Milestone_Base extends Issues_FormAbstract
{
    public function init()
    {
        $this->addElement('text', 'milestone_name', array(
            'filters'    => array('StringTrim', 'HtmlEntities'),
            'validators' => array(
                array('StringLength', true, array(2, 255)),
            ),
            'required'   => true,
            'label'      => $this->translate('milestone_name')
        ));

        $element = new ZendX_JQuery_Form_Element_DatePicker('milestone_duedate',
            array(
                'jQueryParams' => array(
                    'dateFormat' => 'yy-mm-dd', 
                    'defaultDate' => '+7'
                ),
                'label' => $this->translate('milestone_duedate')
            )
        );
        $this->addElement($element);

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

