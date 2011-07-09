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
        $options = $this->getOptions();
        Zend_Registry::set('hash_salt', $options['hash_salt']);
        $userService = Zend_Registry::get('Default_DiContainer')->getUserService();
        $userService->getIdentity();
    }

    /**
     * Initialize jQuery helpers 
     * 
     * @return void
     */
    protected function _initJquery()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
        $view->jQuery()->enable()
        	 ->setVersion('1.6.2')
        	 ->setUiVersion('1.8.14')
        	 ->addStylesheet('https://ajax.googleapis.com/ajax/libs/jqueryui/'.$view->jQuery()->getUiVersion().'/themes/ui-lightness/jquery-ui.css')
        	 ->uiEnable();
    }
}
