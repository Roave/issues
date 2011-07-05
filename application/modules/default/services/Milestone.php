<?php
class Default_Service_Milestone extends Issues_ServiceAbstract
{
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
