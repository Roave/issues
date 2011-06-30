<?php
class Default_Service_Label extends Issues_ServiceAbstract
{
    public function getLabelById($id)
    {
        return $this->_mapper->getLabelById($id);
    }

    public function getLabelsByIssue($issue)
    {
        return $this->_mapper->getLabelsByIssue($issue);
    }

    public function getAllLabels()
    {
        return $this->_mapper->getAllLabels();
    }
}
