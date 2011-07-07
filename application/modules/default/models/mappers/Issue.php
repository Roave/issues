<?php
class Default_Model_Mapper_Issue extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'issue';

    public function getIssueById($issueId)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
                  ->where('issue_id = ?', $issueId);

        $sql = $this->_addAclJoins($sql);

        $row = $db->fetchRow($sql);
        return ($row) ? new Default_Model_Issue($row) : false;
    }

    public function filterIssues($status = false)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName());
        if ($status) {
            $sql->where('status = ?', $status);
        }

        $sql = $this->_addAclJoins($sql);

        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    public function getAllIssues()
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName());
        $sql = $this->_addAclJoins($sql);

        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    public function insert(Default_Model_Issue $issue)
    {
        $data = array(
            'title'             => $issue->getTitle(),
            'description'       => $issue->getDescription(),
            'status'            => $issue->getStatus(),
            'project'           => $issue->getProject()->getProjectId(),
            'created_by'        => $issue->getCreatedBy()->getUserId(),
            'created_time'      => new Zend_Db_Expr('NOW()'),
            'private'           => $issue->isPrivate() ? 1 : 0,
        );

        $db = $this->getWriteAdapter();
        $db->insert($this->getTableName(), $data);
        return $db->lastInsertId();
    }

    public function updateLastUpdate($issue)
    {
        $data = array(
            'last_update_time' => new Zend_Db_Expr('NOW()')
        );
        $db = $this->getWriteAdapter();
        return $db->update($this->getTableName(), $data, $db->quoteInto('issue_id = ?', $issue->getIssueId()));
    }

    public function addLabelToIssue(Default_Model_Issue $issue, Default_Model_Label $label)
    {
        $data = array(
            'issue_id'  => $issue->getIssueId(),
            'label_id'  => $label->getLabelId()
        );

        $db = $this->getWriteAdapter();
        try {
            $db->insert('issue_label_linker', $data);
        } catch (Exception $e) {} // probably a duplicate key
        return true;
    }

    public function removeLabelFromIssue(Default_Model_Issue $issue, Default_Model_Label $label)
    {
        $where = array(
            'issue_id = ?'  => $issue->getIssueId(),
            'label_id = ?'  => $label->getLabelId()
        );

        $db = $this->getWriteAdapter();
        $db->delete('issue_label_linker', $where);
    }

    public function countIssuesByLabel(Default_Model_Label $label)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from(array('ill'=>'issue_label_linker'), array('count' => 'COUNT(*)'))
            ->join(array('i'=>'issue'), 'ill.issue_id = i.issue_id')
            ->where('ill.label_id = ?', $label->getLabelId());

        $sql = $this->_addAclJoins($sql, 'i', 'issue_id');

        return $db->fetchOne($sql);
    }

    public function getIssuesByMilestone($milestone, $status = null)
    {
        if ($milestone instanceof Default_Model_Milestone) {
            $milestone = $milestone->getMilestoneId();
        }

        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from(array('iml'=>'issue_milestone_linker'))
            ->join(array('i'=>'issue'), 'i.issue_id = iml.issue_id')
            ->where('iml.milestone_id = ?', $milestone);

        if ($status) {
            $sql->where('i.status = ?', $status);
        }

        $sql = $this->_addAclJoins($sql, 'i', 'issue_id');

        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    protected function _addAclJoins(Zend_Db_Select $sql, $alias = null, $primaryKey = null)
    {
        $sql = parent::_addAclJoins($sql, $alias, $primaryKey);

        if ($alias === null) {
            $alias = $this->getTableName();
        }

        $table = $this->getTableName();

        if ($primaryKey === null) {
            $primaryKey = $table . '_id';
        }

        $roles = Zend_Registry::get('Default_DiContainer')
            ->getUserService()
            ->getIdentity()
            ->getRoles();

        $sql->join(array('p'=>'project'), "p.project_id = $alias.project", array())
            ->joinLeft(
                array('p_arr' => 'acl_resource_record'),
                "`p_arr`.`resource_type` = 'project' AND `p_arr`.`resource_id` = `{$alias}`.`project`",
                array())
            ->where('((p.private = ?', 1)
            ->where('p_arr.role_id IN (?))', $roles)
            ->orWhere('p.private = ?)', 0);

        return $sql;
    }

    protected function _rowsToModels($rows)
    {
        if (!$rows) return array();
        foreach ($rows as $i => $row) {
            $rows[$i] = new Default_Model_Issue($row);
        }
        return $rows;
    }
}
