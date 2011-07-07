<?php
class Default_MilestonesController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_milestoneService = Zend_Registry::get('Default_DiContainer')->getMilestoneService();
        $fm = $this->getHelper('FlashMessenger')->setNamespace('createForm')->getMessages(); 
        $this->view->createForm = (count($fm) > 0) ? $fm[0] : $this->getCreateForm();
    }

    public function indexAction()
    {
        $this->view->milestones = $this->_milestoneService->getAllMilestones();
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
            $form->setDescription($this->view->translate('new_milestone_failed'));
            $this->_helper->FlashMessenger->setNamespace('createForm')->addMessage($form);
            $this->_helper->redirector('new');
        }
        if (!$this->_milestoneService->createFromForm($form)) {
            $form->setDescription($this->view->translate('new_milestone_failed'));
            $this->_helper->FlashMessenger->setNamespace('createForm')->addMessage($form);
            return $this->_helper->redirector('new');
        }
        return $this->_helper->redirector('index', 'milestones');
    }

    public function getCreateForm()
    {
        return $this->_milestoneService->getCreateForm()->setAction($this->_helper->url->direct('post'));
    }
}
