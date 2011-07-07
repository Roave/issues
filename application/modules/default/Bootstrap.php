<?php
class Default_Bootstrap extends Issues_Application_Module_BootstrapAbstract
{
    public function _initAclService()
    {
        $this->bootstrap('DiContainer');
        Zend_Registry::get('Default_DiContainer')->getAclService();
    }
}
