<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Initialize database
     *
     * @return void
     */
    protected function _initDatabase()
    {
        $this->bootstrap('db');
        Issues_Model_Mapper_DbAbstract::setDefaultAdapter($this->getResource('db'));
    }

    /**
     * Initialize Zend_Auth
     *
     * @return void
     */
    protected function _initAuth()
    {
        $this->bootstrap('db');
        $this->bootstrap('modules');
        $userService = Zend_Registry::get('Default_DiContainer')->getUserService();
        $userService->getIdentity();
    }
}
