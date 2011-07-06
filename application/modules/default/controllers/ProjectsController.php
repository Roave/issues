<?php
class Default_ProjectsController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_projectService = Zend_Registry::get('Default_DiContainer')->getProjectService();
        $fm = $this->getHelper('FlashMessenger')->setNamespace('createForm')->getMessages(); 
        $this->view->createForm = (count($fm) > 0) ? $fm[0] : $this->getCreateForm();
    }

    public function indexAction()
    {
        $this->view->projects = $this->_projectService->getAllProjects();
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
            $form->setDescription($this->view->translate('new_project_failed'));
            $this->_helper->FlashMessenger->setNamespace('createForm')->addMessage($form);
            $this->_helper->redirector('new');
        }
        if (!$this->_projectService->createFromForm($form)) {
            $form->setDescription($this->view->translate('new_project_failed'));
            $this->_helper->FlashMessenger->setNamespace('createForm')->addMessage($form);
            return $this->_helper->redirector('new');
        }
        return $this->_helper->redirector('index', 'projects');
    }

    public function getCreateForm()
    {
        return $this->_projectService->getCreateForm()->setAction($this->_helper->url->direct('post'));
    }
}
