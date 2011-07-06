<?php
class Default_Form_User_Base extends Issues_FormAbstract
{
    public function init()
    {
        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('Alnum', true, array()),
                array('StringLength', true, array(3, 128)),
                array('Db_NoRecordExists', true, array(
                    'adapter'   => Zend_Registry::get('Default_DiContainer')
                        ->getUserMapper()
                        ->getReadAdapter(),
                    'table'     => Zend_Registry::get('Default_DiContainer')
                        ->getUserMapper()
                        ->getTableName(),
                    'field'     => 'username'
                ))
            ),
            'required'   => true,
            'label'      => $this->translate('username')
        ));

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
            'required'   => true,
            'label'      => $this->translate('password')
        ));

        $this->addElement('password', 'passwordVerify', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
               array('Identical', false, array('token' => 'password'))
            ),
            'required'   => true,
            'label'      => $this->translate('confirm_password')
        ));

        $this->addElement('select', 'role', array(
            'required'   => true,
            'label'      => 'Role',
            'multiOptions'   => Zend_Registry::get('Default_DiContainer')
                        ->getRoleService()
                        ->getRolesForForm(),
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
}
