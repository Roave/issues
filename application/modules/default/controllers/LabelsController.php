<?php
class Default_LabelsController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_labelService = Zend_Registry::get('Default_DiContainer')->getLabelService();
    }

    public function postAction()
    {
        $form = $this->_labelService->getCreateForm();
        $request = $this->getRequest();
        if (!$request->isPost()) return $this->_helper->redirector('index','index');
        if (false === $form->isValid($request->getPost())) {
            $form->setDescription($this->view->translate('new_label_failed'));
            $this->_helper->FlashMessenger->addMessage($form);
            return $this->_helper->redirector('index', 'index');
        }
        if (!$this->_labelService->createLabel($form->getValue('text'), '#000')) {
            $form->setDescription($this->view->translate('new_label_failed'));
            $this->_helper->FlashMessenger->addMessage($form);
            return $this->_helper->redirector('index','index');
        }
        return $this->_helper->redirector('index', 'index');
    }
}
