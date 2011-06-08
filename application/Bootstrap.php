<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
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
