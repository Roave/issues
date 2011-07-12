<?php
class Default_Model_Mapper_Milestone extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'milestone';
    protected $_modelClass = 'Default_Model_Milestone';

    public function getMilestoneById($id)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('milestone_id = ?', $id);
        if (!Zend_Registry::get('Default_DiContainer')->getAclService()->isAllowed('milestone', 'view-all')) {
            $sql = $this->_addAclJoins($sql);
        }
        $row = $db->fetchRow($sql);
        return $this->_rowToModel($row);
    }

    public function getAllMilestones($counts = false)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from(array('m'=>$this->getTableName()), 'm.*');

        if ($counts === true) {
            $sql->joinLeft(array('iml'=>'issue_milestone_linker'), 'iml.milestone_id = m.milestone_id', array())
                ->joinLeft(array('i'=>'issue'), 'iml.issue_id = i.issue_id', 'status')
                ->columns(array('open_count'=>'SUM(CASE WHEN i.status = \'open\' THEN 1 ELSE 0 END)'))
                ->columns(array('closed_count'=>'SUM(CASE WHEN i.status = \'closed\' THEN 1 ELSE 0 END)'))
                ->group('m.milestone_id');
        }

        if (!Zend_Registry::get('Default_DiContainer')->getAclService()->isAllowed('milestone', 'view-all')) {
            $sql = $this->_addAclJoins($sql, 'm', 'milestone_id');
        }

        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    public function getMilestonesByIssue($issue)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from(array('iml'=>'issue_milestone_linker'), array())
            ->join(array('m'=>'milestone'), 'iml.milestone_id = m.milestone_id');

        if ($issue instanceof Default_Model_Issue) {
            $sql->where('iml.issue_id = ?', $issue->getIssueId());
        } else {
            $sql->where('iml.issue_id = ?', (int) $issue);
        }

        if (!Zend_Registry::get('Default_DiContainer')->getAclService()->isAllowed('milestone', 'view-all')) {
            $sql = $this->_addAclJoins($sql, 'm', 'milestone_id');
        }
        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
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

    public function addIssueToMilestone($milestone, $issue)
    {
        if ($milestone instanceof Default_Model_Milestone) {
            $milestone = $milestone->getMilestoneId();
        } else if (!is_numeric($milestone)) {
            return false;
        }

        if ($issue instanceof Default_Model_Issue) {
            $issue = $issue->getIssue();
        } else if (!is_numeric($issue)) {
            return false;
        }

        $db = $this->getWriteAdapter();
        $data = array(
            'milestone_id'  => $milestone,
            'issue_id'      => $issue
        );

        try {
            $db->insert('issue_milestone_linker', $data);
        } catch (Exception $e) {
            // probably a duplicate key
            return false;
        }

        return true;
    }
}
