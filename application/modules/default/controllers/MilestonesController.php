<?php
class Default_MilestonesController extends Zend_Controller_Action
{
    public function init()
    {
        // Get a list of projects from the DB
        $this->_projectService = Zend_Registry::get('Default_DiContainer')->getProjectService();
        $this->view->projects = $this->_projectService->getAllProjects();
        
        $this->_milestoneService = Zend_Registry::get('Default_DiContainer')->getMilestoneService();
    }

    public function indexAction()
    {
        $this->view->milestones = $this->_milestoneService->getAllMilestones();
    }

    public function viewAction()
    {
        $this->view->milestone = $this->_milestoneService->getMilestoneById($this->_getParam('id'));
        $this->view->issues = Zend_Registry::get('Default_DiContainer')
            ->getIssueService()
            ->getIssuesByMilestone($this->view->milestone);

        $this->view->user = Zend_Registry::get('Default_DiContainer')
            ->getUserService()
            ->getIdentity();
    }

    public function newAction()
    {
        $fm = $this->getHelper('FlashMessenger')->setNamespace('createForm')->getMessages(); 
        $this->view->createForm = (count($fm) > 0) ? $fm[0] : $this->getCreateForm();
    }

    public function postAction()
    {
        $fm = $this->getHelper('FlashMessenger')->setNamespace('createForm')->getMessages(); 
        $form = (count($fm) > 0) ? $fm[0] : $this->getCreateForm();

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
