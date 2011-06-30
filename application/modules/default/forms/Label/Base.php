<?php
class Default_Form_Label_Base extends Issues_FormAbstract
{
    public function init()
    {
        $this->addElement('text', 'text', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength', true, array(3, 50)),
                array('Db_NoRecordExists', true, array(
                    'adapter'   => Zend_Registry::get('Default_DiContainer')
                        ->getLabelMapper()
                        ->getReadAdapter(),
                    'table'     => Zend_Registry::get('Default_DiContainer')
                        ->getLabelMapper()
                        ->getTableName(),
                    'field'     => 'text'
                ))
            ),
            'required'      => true,
            'label'         => 'Label Text'
        ));

        $this->addElement('text', 'color', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength', true, array(3, 50)),
            ),
            'required'      => true,
            'label'         => 'Color'
        ));

        $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'decorators' => array('ViewHelper',array('HtmlTag', array('tag' => 'dd', 'id' => 'form-submit')))
        ));
    }
}
