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
}
