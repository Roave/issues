<?php
class Default_Service_Comment extends Issues_ServiceAbstract
{
    protected $_createForm;

    public function getCommentById($id)
    {
        return $this->_mapper->getCommentById($id);
    }

    public function getCommentsByIssue($issue)
    {
        return $this->_mapper->getCommentsByIssue($issue);
    }

    public function getCreateForm()
    {
        $acl = Zend_Registry::get('Default_DiContainer')->getAclService();
        if (!$acl->isAllowed('issue', 'comment')) {
            return false;
        }

        if (null === $this->_createForm) {
            $this->_createForm = new Default_Form_Comment_Create();
        }
        return $this->_createForm;
    }

    public function canEditComment(Default_Model_Comment $comment)
    {
        $acl = Zend_Registry::get('Default_DiContainer')->getAclService();
        if ($acl->isAllowed('comment', 'edit-all')) {
            return true;
        }

        $userId = Zend_Registry::get('Default_DiContainer')
            ->getUserService()->getIdentity()->getUserId();

        if ($acl->isAllowed('comment', 'edit-own')) {
            if ($comment->getCreatedBy()->getUserId() == $userId) {
                return true;
            }
        }

        return false;
    }

    public function canDeleteComment(Default_Model_Comment $comment)
    {
        $acl = Zend_Registry::get('Default_DiContainer')->getAclService();
        if ($acl->isAllowed('comment', 'delete-all')) {
            return true;
        }

        $userId = Zend_Registry::get('Default_DiContainer')
            ->getUserService()->getIdentity()->getUserId();

        if ($acl->isAllowed('comment', 'delete-own')) {
            if ($comment->getCreatedBy()->getUserId() == $userId) {
                return true;
            }
        }

        return false;
    }

    public function deleteComment($comment)
    {
        if (!($comment instanceof Default_Model_Comment)) {
            $comment = $this->getCommentById($comment);
        }

        if (!$this->canDeleteComment($comment)) {
            return false;
        }

        $comment->setDeleted(true);
        $result = $this->_mapper->save($comment);

        Zend_Registry::get('Default_DiContainer')->getIssueMapper()
            ->auditTrail($issueId, 'remove-comment', '', $comment->getCommentId());
    }

    public function getEditForm(Default_Model_Comment $comment)
    {
        $form = new Default_Form_Comment_Edit();
        $form->setDefaultValues($comment);
        return $form;
    }

    public function createFromForm($form, $issueId, $userId = null)
    {
        $acl = Zend_Registry::get('Default_DiContainer')->getAclService();
        if (!$acl->isAllowed('issue', 'comment')) {
            return false;
        }

        $identity = Zend_Registry::get('Default_DiContainer')
            ->getUserService()->getIdentity();

        if ($userId === null) {
            $userId = $identity->getUserId();
        }

        $permissions = $form->getValue('permissions');

        $comment = new Default_Model_Comment();
        $comment->setCreatedBy($identity)
            ->setIssue($issueId)
            ->setText($form->getValue('text'))
            ->setPrivate($permissions['private'] ? true : false);
        $return = $this->_mapper->save($comment);

        Zend_Registry::get('Default_DiContainer')->getIssueMapper()
            ->auditTrail($issueId, 'add-comment', '', '', $return);

        if ($permissions['private']) {
            Zend_Registry::get('Default_DiContainer')->getAclService()
                ->addResourceRecord($permissions['roles'], 'comment', $return);
        }

        return $return;
    }

    public function updateFromForm(Default_Form_Comment_Edit $form, $comment)
    {
        if (!($comment instanceof Default_Model_Comment)) {
            $comment = $this->getCommentById($comment);
        }

        if (!$comment) {
            return false;
        }

        if (!$this->canEditComment($comment)) {
            return false;
        }

        $comment->setText($form->getValue('text'))
            ->setPrivate($form->getSubform('permissions')->getElement('private')->isChecked());
        $result = $this->_mapper->save($comment);

        $this->_mapper->clearCommentResourceRecords($comment);

        if ($comment->isPrivate()) {
            $roles = $form->getSubform('permissions')->getElement('roles')->getValue();
            Zend_Registry::get('Default_DiContainer')
                ->getAclService()
                ->addResourceRecord($roles, 'comment', $comment->getCommentId());
        }

        if ($result === false) {
            return false;
        } else if ($result === 0) {
            return true;
        }

        return $result;
    }
}
