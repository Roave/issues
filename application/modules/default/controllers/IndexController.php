<?php
class Default_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_issueService = Zend_Registry::get('Default_DiContainer')->getIssueService();
        $this->_labelService = Zend_Registry::get('Default_DiContainer')->getLabelService();
    }

    public function indexAction()
    {
        $this->view->openIssues = $this->_issueService->filterIssues('open');
        $this->view->labels = $this->_labelService->getAllLabels();
        $fm = $this->getHelper('FlashMessenger')->getMessages(); 
        $this->view->createLabelForm = (count($fm) > 0) ? $fm[0] : $this->getCreateLabelForm();
    }
    public function getCreateLabelForm()
    {
        return $this->_labelService->getCreateForm()->setAction($this->_helper->url->direct('post','labels'));
    }
}
