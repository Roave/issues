<?php
class Default_AuthController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_userService = Zend_Registry::get('Default_DiContainer')->getUserService();
        $fm = $this->getHelper('FlashMessenger')->setNamespace('loginForm')->getMessages(); 
        $this->view->loginForm = (count($fm) > 0) ? $fm[0] : $this->getLoginForm();
        $this->view->registerForm = (count($fm) > 0) ? $fm[0] : $this->getRegisterForm();

        $this->_aclService = Zend_Registry::get('Default_DiContainer')->getAclService();
    }
    
    public function indexAction()
    {
        // Index...
    }

    public function logoutAction()
    {
        $this->_userService->logout();
        return $this->_helper->redirector('index','index');
    }
    
    public function getLoginForm()
    {
        return $this->_userService->getLoginForm()->setAction($this->_helper->url->direct('authenticate-post'));
    }

    public function getRegisterForm()
    {
        return $this->_userService->getRegisterForm()->setAction($this->_helper->url->direct('register-post'));
    }

    public function authenticatePostAction()
    {
        // ACL Check for authentication action
        if (!$this->_aclService->isAllowed('user', 'login')) {
            return $this->_helper->redirector('index', 'index');
        }
        
        $form = $this->view->loginForm;
        $request = $this->getRequest();
        if (!$request->isPost()) return $this->_helper->redirector('index');
        $form->populate($request->getPost());
        $vals = $form->getValues();
        if (false === $this->_userService->authenticate($vals['username'], $vals['password'])) {
            $form->setDescription($this->view->translate('login_failed'));
            $this->_helper->FlashMessenger->setNamespace('loginForm')->addMessage($form);
            $this->_helper->redirector('index');
        }
        return $this->_helper->redirector('index', 'index');
    }
    
    public function registerPostAction()
    {
        // ACL Check for registration action
        if (!$this->_aclService->isAllowed('user', 'register')) {
            return $this->_helper->redirector('index', 'index');
        }
        
        $form = $this->view->registerForm;
        $request = $this->getRequest();
        if (!$request->isPost()) return $this->_helper->redirector('index');
        if (false === $form->isValid($request->getPost())) {
            $form->setDescription($this->view->translate('registration_failed'));
            $this->_helper->FlashMessenger->setNamespace('registerForm')->addMessage($form);
            $this->_helper->redirector('index');
        }
        if (!$this->_userService->createFromForm($form)) {
            $form->setDescription($this->view->translate('registration_failed'));
            $this->_helper->FlashMessenger->setNamespace('registerForm')->addMessage($form);
            return $this->_helper->redirector('index');
        }
        return $this->_helper->redirector('index', 'auth');
    }

}
