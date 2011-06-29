<?php
class Default_IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_issueService = Zend_Registry::get('Default_DiContainer')->getIssueService();
        $this->view->issues = $this->_issueService->getAllIssues();
    }
}
