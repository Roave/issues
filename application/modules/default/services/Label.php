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

    public function getLabelsForSelect(array $labels = null)
    {
        if ($labels === null) {
            $labels = $this->getAllLabels();
        }

        $result = array();
        foreach ($labels as $l) {
            $result[$l->getLabelId()] = $l->getText();
        }

        return $result;
    }
}
