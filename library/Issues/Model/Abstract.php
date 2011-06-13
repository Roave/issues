<?php
abstract class Issues_Model_Abstract
{
    /**
    * @var array Class methods
    */
    protected $_classMethods;

    /**
     * @var array Model mapper instances
     */
    protected $_mappers = array();

   /**
    * Constructor
    *
    * @param array|Zend_Config|null $options
    * @return void
    */
    public function __construct($options = null)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function setOptions(array $options)
    {
        if (null === $this->_classMethods) {
            $this->_classMethods = get_class_methods($this);
        }
        foreach ($options as $key => $value) {
            $method = 'set' . $this->_fieldToMethod($key);
            if (in_array($method, $this->_classMethods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
    public function getMapper($name = null)
    {
        if (!is_string($name)) {
            $name = $this->_getBaseName();
        }
        if (isset($this->_mappers[$name])) {
            return $this->_mappers[$name];
        } else {
            $class = join('_', array($this->_getNamespace(),'Model','Mapper',$this->_getInflected($name)));
            $this->_mappers[$name] = new $class();
            return $this->_mappers[$name];
        }
    }

    private function _getBaseName()
    {
        $name = explode('_', get_class($this));
        return array_pop($name);
    }

    private function _getNamespace()
    {
        $ns = explode('_', get_class($this));
        return $ns[0];
    }

    private function _fieldToMethod($name)
    {
        return implode('',array_map('ucfirst', explode('_',$name)));
    }

    private function _getInflected($name)
    {
        $inflector = new Zend_Filter_Inflector(':class');
        $inflector->setRules(array(
            ':class'  => array('Word_CamelCaseToUnderscore')
        ));
        return ucfirst($inflector->filter(array('class' => $name)));
    }

}
