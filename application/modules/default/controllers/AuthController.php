<?php
class Default_AuthController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_userService = Zend_Registry::get('Default_DiContainer')->getUserService();
        $fm = $this->getHelper('FlashMessenger')->setNamespace('loginForm')->getMessages(); 
        $this->view->loginForm = (count($fm) > 0) ? $fm[0] : $this->getLoginForm();

        $this->_aclService = Zend_Registry::get('Default_DiContainer')->getAclService();
    }

    public function loginAction()
    {
        if (!$this->_aclService->isAllowed('user', 'login')) {
            return $this->_helper->redirector('index', 'index');
        }
    }

    public function logoutAction()
    {
        $this->_userService->logout();
        return $this->_helper->redirector('index','index');
    }

    public function authenticateAction()
    {
        $form = $this->view->loginForm;
        $request = $this->getRequest();
        if (!$request->isPost()) return $this->_helper->redirector('login');
        $form->populate($request->getPost());
        $vals = $form->getValues();
        if (false === $this->_userService->authenticate($vals['username'], $vals['password'])) {
            $form->setDescription($this->view->translate('login_failed'));
            $this->_helper->FlashMessenger->setNamespace('loginForm')->addMessage($form);
            $this->_helper->redirector('login');
        }
        return $this->_helper->redirector('index', 'index');
    }

    public function getLoginForm()
    {
        return $this->_userService->getLoginForm()->setAction($this->_helper->url->direct('authenticate'));
    }
}
