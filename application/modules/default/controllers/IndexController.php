<?php
class Default_IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_issueService = Zend_Registry::get('Default_DiContainer')->getIssueService();
        $this->view->openIssues = $this->_issueService->filterIssues('open');

        $this->_labelService = Zend_Registry::get('Default_DiContainer')->getLabelService();
        $this->view->labels = $this->_labelService->getAllLabels();
        $this->view->labelsSelect = $this->_labelService->getLabelsForSelect($this->view->labels);

        $this->_userService = Zend_Registry::get('Default_DiContainer')->getUserService();
        $this->view->users = $this->_userService->getUsersForSelect();
    }
}
