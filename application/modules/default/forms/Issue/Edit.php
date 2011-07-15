<?php
class Default_Form_Issue_Edit extends Default_Form_Issue_Base
{
    public function init()
    {
        parent::init();
        $this->getElement('submit')->setLabel($this->translate('edit_issue'));
    }

    public function setDefaultValues(Default_Model_Issue $issue)
    {
        $milestones = Zend_Registry::get('Default_DiContainer')
            ->getMilestoneService()
            ->getMilestonesByIssue($issue);

        $milestoneIds = array();
        foreach ($milestones as $i) {
            $milestoneIds[] = $i->getMilestoneId();
        }

        $labels = Zend_Registry::get('Default_DiContainer')
            ->getLabelService()
            ->getLabelsByIssue($issue);
        $labelIds = array();
        foreach ($labels as $i) {
            $labelIds[] = $i->getLabelId();
        }

        $data = array(
            'title'         => $issue->getTitle(false),
            'project'       => $issue->getProject()->getProjectId(),
            'description'   => $issue->getDescription(false),
            'milestones'    => $milestoneIds,
            'labels'        => $labelIds,
            'status'        => $issue->getStatus()
        );

        if ($issue->getAssignedTo() != null) {
            $data['assigned_to'] = $issue->getAssignedTo()->getUserId();
        }

        foreach ($data as $element => $value) {
            $this->getElement($element)->setValue($value);
        }

        $this->getSubform('permissions')
            ->getElement('private')
            ->setChecked($issue->isPrivate());

        $roles = Zend_Registry::get('Default_DiContainer')
            ->getAclService()
            ->getRolesForResource('issue', $issue->getIssueId());

        $roleIds = array();
        foreach ($roles as $role) {
            $roleIds[] = $role->getRoleId();
        }

        $this->getSubform('permissions')
            ->getElement('roles')
            ->setValue($roleIds);
    }
}

