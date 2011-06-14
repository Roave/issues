<?php
class Default_Form_User_Base extends Issues_FormAbstract
{
    public function init()
    {
        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                'Alpha',
                array('StringLength', true, array(3, 128))
            ),
            'required'   => true,
            'label'      => $this->translate('username'),
        ));

        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('EmailAddress'),
                // TODO: Fix this, it's broken
                array('Callback', true, array('callback' => array($this, 'validateUniqueEmail')))
            ),
            'required'   => true,
            'label'      => $this->translate('email'),
        ));

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
            'required'   => true,
            'label'      => $this->translate('password'),
        ));

        $this->addElement('password', 'passwordVerify', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
               array('Identical', false, array('token' => 'password'))
            ),
            'required'   => true,
            'label'      => $this->translate('confirm_password'),
        ));

        $this->addElement('select', 'role', array(
            'required'   => true,
            'label'      => $this->translate('role'),
            'multiOptions' => array('Customer' => 'Customer', 'Admin' => 'Admin'),
        ));

        $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'decorators' => array('ViewHelper',array('HtmlTag', array('tag' => 'dd', 'id' => 'form-submit')))
        ));

         $this->addElement('hidden', 'userId', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'decorators' => array('viewHelper',array('HtmlTag', array('tag' => 'dd', 'class' => 'noDisplay')))
        ));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'form')),
            array('Description', array('placement' => 'prepend', 'class' => 'error')),
            'Form'
        ));
    }

    public function validateUniqueEmail($email)
    {
        $userService = Zend_Registry::get('Default_DiContainer')->getUserService(); 
        return $userService->emailExists($email);
    }
}
