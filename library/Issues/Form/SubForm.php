<?php
class Issues_Form_SubForm extends Zend_Form_SubForm
{
    /**
     * _label 
     * 
     * @var string
     */
    protected $_label;

    public function __construct($options = NULL)
    {
        parent::__construct($options);
        $this->setDisableTranslator(true);
    }

    public function translate($string)
    {
        return $this->getTranslator()->_($string);
    }

    /**
     * setLabel 
     * 
     * @param mixed $label 
     * @return Issues_Form_SubForm
     */
    public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }

    /**
     * getLabel 
     * 
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * Added so the label decorator doesn't freak out
     * @return boolean
     */
    public function isRequired()
    {
        return false;
    }

    /**
     * loadDefaultDecorators 
     * 
     * @return void
     */
    public function loadDefaultDecorators()
    {
        parent::loadDefaultDecorators();
        $this->removeDecorator('DtDdWrapper');
        $this->addDecorator('Label', array('tag' => 'dt'));
    } 
}
