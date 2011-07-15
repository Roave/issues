<?php
class Default_Form_Comment_Edit extends Default_Form_Comment_Base
{
    public function init()
    {
        parent::init();
        $this->getElement('submit')->setLabel($this->translate('save_comment'));
    }

    public function setDefaultValues(Default_Model_Comment $comment)
    {
        $this->getElement('text')->setValue($comment->getText(false));

        $this->getSubform('permissions')
            ->getElement('private')
            ->setChecked($comment->isPrivate());

        $roles = Zend_Registry::get('Default_DiContainer')
            ->getAclService()
            ->getRolesForResource('comment', $comment->getCommentId());

        $roleIds = array();
        foreach ($roles as $role) {
            $roleIds[] = $role->getRoleId();
        }

        $this->getSubform('permissions')
            ->getElement('roles')
            ->setValue($roleIds);
    }
}
