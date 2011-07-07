<?php
class Default_Service_Milestone extends Issues_ServiceAbstract
{
    protected $_createForm;

    public function getCreateForm()
    {
        if (null === $this->_createForm) {
            $this->_createForm = new Default_Form_Milestone_Create();
        }
        return $this->_createForm;
    }

    public function createFromForm(Default_Form_Milestone_Create $form)
    {
        $acl = Zend_Registry::get('Default_DiContainer')->getAclService();
        if (!$acl->isAllowed('milestone', 'create')) {
            return false;
        }

        $milestone = new Default_Model_Milestone();
        $milestone->setName($form->getValue('milestone_name'));
        $milestone->setDueDate($form->getValue('milestone_duedate'));
        return $this->_mapper->insert($milestone);
    }

    public function getMilestoneById($id)
    {
        return $this->_mapper->getMilestoneById($id);
    }

    public function getMilestonesByIssue($issue)
    {
        return $this->_mapper->getMilestonesByIssue($issue);
    }

    public function getAllMilestones()
    {
        return $this->_mapper->getAllMilestones();
    }

    public function getMilestonesForSelect()
    {
        $milestones = $this->_mapper->getAllMilestones();

        $return = array();
        foreach ($milestones as $i) {
            $return[$i->getMilestoneId()] = $i->getName();
        }

        return $return;
    }
}
