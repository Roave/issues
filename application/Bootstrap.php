<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initProfiler()
    {
        $this->bootstrap('db');
        $profiler = new Zend_Db_Profiler('All DB Queries');
        $profiler->setEnabled(true);
        $this->getResource('db')->setProfiler($profiler);
    }

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
