<?php
class Issues_FormAbstract extends Zend_Form
{
    public function __construct($options = NULL)
    {
        parent::__construct($options);
        $this->setDisableTranslator(true);

    }
    public function translate($string)
    {
        return $this->getTranslator()->_($string);
    }
}
