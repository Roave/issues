<?php
class Default_IssuesController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_issueService = Zend_Registry::get('Default_DiContainer')->getIssueService();
        $fm = $this->getHelper('FlashMessenger')->setNamespace('createForm')->getMessages(); 
        $this->view->createForm = (count($fm) > 0) ? $fm[0] : $this->getCreateForm();
    }

    public function newAction()
    {
    }

    public function postAction()
    {
        $form = $this->view->createForm;
        $request = $this->getRequest();
        if (!$request->isPost()) return $this->_helper->redirector('new');
        if (false === $form->isValid($request->getPost())) {
            $form->setDescription($this->view->translate('new_issue'));
            $this->_helper->FlashMessenger->setNamespace('createForm')->addMessage($form);
            $this->_helper->redirector('new');
        }
        if (!$this->_issueService->createFromForm($form)) {
            $form->setDescription($this->view->translate('new_issue'));
            $this->_helper->FlashMessenger->setNamespace('createForm')->addMessage($form);
            return $this->_helper->redirector('new');
        }
        return $this->_helper->redirector('list', 'issues');
    }

    public function listAction()
    {
        $this->view->issues = $this->_issueService->getAllIssues();
    }

    public function getCreateForm()
    {
        return $this->_issueService->getCreateForm()->setAction($this->_helper->url->direct('post'));
    }
}
