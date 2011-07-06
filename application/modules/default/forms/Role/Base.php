<?php
class Default_Form_Role_Base extends Issues_FormAbstract
{
    public function init()
    {
        $this->addElement('text', 'name', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength', true, array(3, 255)),
                array('Db_NoRecordExists', true, array(
                    'adapter'   => Zend_Registry::get('Default_DiContainer')
                        ->getRoleMapper()
                        ->getReadAdapter(),
                    'table'     => Zend_Registry::get('Default_DiContainer')
                        ->getRoleMapper()
                        ->getTableName(),
                    'field'     => 'name'
                ))
            ),
            'required'      => true,
            'label'         => $this->translate('role_name')
        ));

        $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'decorators' => array('ViewHelper',array('HtmlTag', array('tag' => 'dd', 'id' => 'form-submit')))
        ));
    }
}
