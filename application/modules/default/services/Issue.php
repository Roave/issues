<?php
class Default_Service_Issue extends Issues_ServiceAbstract 
{
    protected $_createForm;

    public function getCreateForm()
    {
        if (null === $this->_createForm) {
            $this->_createForm = new Default_Form_Issue_Create();
        }
        return $this->_createForm;
    }

    public function getAllIssues()
    {
        return $this->_mapper->getAllIssues();
    }

    public function filterIssues($status)
    {
        return $this->_mapper->filterIssues($status);
    }

    public function createFromForm(Default_Form_Issue_Create $form)
    {
        if (Zend_Auth::getInstance()->getIdentity()->getRole()->getRoleName() == 'guest') {
            return false; 
        } 
        $issue = new Default_Model_Issue();
        $issue->setTitle($form->getValue('title'))
            ->setDescription($form->getValue('description'))
            ->setStatus('open')
            ->setProject($form->getValue('project'))
            ->setCreatedBy(Zend_Auth::getInstance()->getIdentity());
        return $this->_mapper->insert($issue);
    }
}

