<?php
abstract class Issues_Model_Abstract
{
    /**
     * _skipAcl
     *
     * Set this to true to prevent a model from being loaded into the ACL 
     * 
     * @var boolean
     */
    protected $_skipAcl = false; 

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
        /*
         * Because roles are loaded (to be used as roles) during the creation of 
         * the AclService, we *should not* try to add them to the AclService 
         * until it has been instantiated or we will run into an infinite loop
         */
        if (!$this->_skipAcl && Zend_Registry::get('Default_DiContainer')->hasAclService()) {
            $acl = Zend_Registry::get('Default_DiContainer')->getAclService();
            $acl->addResource($this);
        }
    }

    public function isPrivate()
    {
        return false;
    }

    public function setOptions(array $options)
    {
        $classMethods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . $this->_fieldToMethod($key);
            if (in_array($method, $classMethods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    protected function _adjustedDateTime($dateTime)
    {
        $userService =  Zend_Registry::get('Default_DiContainer')->getUserService();
        return $dateTime->setTimezone(new DateTimeZone($userService->getActiveTimezone()));
    }
    
    private function _fieldToMethod($name)
    {
        return implode('',array_map('ucfirst', explode('_',$name)));
    }
}
