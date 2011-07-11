<?php
class Default_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        // Get a list of projects from the DB
        $this->_projectService = Zend_Registry::get('Default_DiContainer')->getProjectService();
        $this->view->projects = $this->_projectService->getAllProjects();
        
        $this->_issueService = Zend_Registry::get('Default_DiContainer')->getIssueService();
        $this->_labelService = Zend_Registry::get('Default_DiContainer')->getLabelService();
        $this->_userService = Zend_Registry::get('Default_DiContainer')->getUserService();
        $this->_milestoneService = Zend_Registry::get('Default_DiContainer')->getMilestoneService();
        $this->_aclService = Zend_Registry::get('Default_DiContainer')->getAclService();

        $this->view->user = $this->_userService->getIdentity();
    }

    public function indexAction()
    {
        // print_r($this->view->projects); die(); // Debugging
        
        $this->view->labels = $this->_labelService->getAllLabels();
        $this->view->openIssues = $this->_issueService->filterIssues('open');
        $this->view->closedIssues = $this->_issueService->filterIssues('closed');

        $this->view->milestones = $this->_milestoneService->getAllMilestones();

        $fm = $this->getHelper('FlashMessenger')->getMessages(); 
        $this->view->createLabelForm = (count($fm) > 0) ? $fm[0] : $this->getCreateLabelForm();

        $this->view->labelsSelect = $this->_labelService->getLabelsForSelect($this->view->labels);

        $this->view->usersSelect = $this->_userService->getUsersForSelect();

        $this->view->milestonesSelect = $this->_milestoneService->getMilestonesForSelect($this->view->milestones);
    }

    public function getCreateLabelForm()
    {
        $form = $this->_labelService->getCreateForm();

        if ($form) {
            return $form->setAction($this->_helper->url->direct('post','labels'));
        } else {
            return false;
        }
    }
}
