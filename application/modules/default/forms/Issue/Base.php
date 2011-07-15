<?php
class Default_Form_Issue_Base extends Issues_FormAbstract
{
    public function init()
    {
        $this->addElement('text', 'title', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(5, 255)),
            ),
            'required'   => true,
            'label'      => $this->translate('title')
        ));

        $this->addElement('select', 'project', array(
            'required'   => true,
            'label'      => $this->translate('project'),
            'multiOptions'   => Zend_Registry::get('Default_DiContainer')
                        ->getProjectService()
                        ->getProjectsForForm(),
        ));

        $this->addElement('select', 'status', array(
            'required'      => true,
            'label'         => $this->translate('status'),
            'multiOptions'  => array(
                'open'      => 'Open',
                'closed'    => 'Closed'
            )
        ));

        $this->addElement('select', 'assigned_to', array(
            'required'      => false,
            'label'         => 'Assigned To',
            'multiOptions'  => array_merge(
                array('0' => 'Nobody'),
                Zend_Registry::get('Default_DiContainer')
                    ->getUserService()
                    ->getUsersForSelect())
            )
        );

        $this->addElement('textarea', 'description', array(
            'label'      => 'Description',
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(5)),
            ),
            'required'   => true,
        ));

        $milestones = new Issues_Form_Element_MilestoneSelect('milestones');
        $milestones->setLabel($this->translate('milestones'));
        $this->addElement($milestones);

        $labels = new Issues_Form_Element_LabelSelect('labels');
        $labels->setLabel($this->translate('labels'));
        $this->addElement($labels);

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
