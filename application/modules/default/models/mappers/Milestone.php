<?php
class Default_Model_Mapper_Milestone extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'milestone';

    public function getMilestoneById($id)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('milestone_id = ?', $id);
        $row = $db->fetchRow($sql);
        return ($row) ? new Default_Model_Milestone($row) : false;
    }

    public function getAllMilestones()
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName());
        $rows = $db->fetchAll($sql);
        if (!$rows) return array();

        $return = array();
        foreach ($rows as $i => $row) {
            $return[$i] = new Default_Model_Milestone($row);
        }
        return $return;
    }

    public function getMilestonesByIssue($issue)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from(array('iml'=>'issue_milestone_linker'))
            ->join(array('m'=>'milestone'), 'iml.milestone_id = m.milestone_id');

        if ($issue instanceof Default_Model_Issue) {
            $sql->where('iml.issue_id = ?', $issue->getIssueId());
        } else {
            $sql->where('iml.issue_id = ?', (int) $issue);
        }

        $rows = $db->fetchAll($sql);
        if (!$rows) return array();

        $return = array();
        foreach ($rows as $i => $row) {
            $return[$i] = new Default_Model_Milestone($row);
        }
        return $return;
    }

    public function insert(Default_Model_Milestone $milestone)
    {
        $data = array(
            'name'      => $milestone->getName(),
            'due_date'  => $milestone->getDueDate()->format('Y-m-d'),
            'private'   => $milestone->isPrivate() ? 1 : 0,
        );

        $db = $this->getWriteAdapter();
        $db->insert($this->getTableName(), $data);
        return $db->lastInsertId();
    }
}
