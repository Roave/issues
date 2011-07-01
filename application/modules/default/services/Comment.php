<?php
class Default_Service_Comment extends Issues_ServiceAbstract
{
    protected $_createForm;

    public function getCommentsByIssue($issue)
    {
        return $this->_mapper->getCommentsByIssue($issue);
    }

    public function getCreateForm()
    {
        if (null === $this->_createForm) {
            $this->_createForm = new Default_Form_Comment_Create();
        }
        return $this->_createForm;
    }

    public function createFromForm($form, $issueId, $userId = null)
    {
        $identity = Zend_Registry::get('Default_DiContainer')->getUserService()->getIdentity();
        if ($identity->getRole()->getName() == 'guest') {
            return false;
        }

        if ($userId === null) {
            $userId = $identity->getUserId();
        }

        $comment = new Default_Model_Comment();
        $comment->setCreatedBy($identity)
            ->setIssue($issueId)
            ->setText($form->getValue('text'));
        return $this->_mapper->insert($comment);
    }
}
