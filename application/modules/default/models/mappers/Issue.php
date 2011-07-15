<?php
class Default_Model_Mapper_Issue extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'issue';
    protected $_modelClass = 'Default_Model_Issue';

    public function getIssueById($issueId)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('issue_id = ?', $issueId);

        $sql = $this->_addAclJoins($sql);
        $sql = $this->_addRelationJoins($sql, 'issue');

        $row = $db->fetchRow($sql);
        return ($row) ? $this->_rowToModel($row) : false;
    }

    public function filterIssues($status = false)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName());
        if ($status) {
            $sql->where('status = ?', $status);
        }

        $sql = $this->_addLabelConcat($sql);
        $sql = $this->_addAclJoins($sql);
        $sql = $this->_addRelationJoins($sql, 'issue');
        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    public function getIssuesByProject($project)
    {
        if ($project instanceof Issues_Model_Project) {
            $project = $project->getProjectId();
        } else {
            if (!is_numeric($project)) {
                return false;
            }
        }

        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('project = ?', $project);

        $sql = $this->_addAclJoins($sql);
        $sql = $this->_addLabelConcat($sql);
        $sql = $this->_addRelationJoins($sql, 'issue');

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

    public function save(Default_Model_Issue $issue)
    {
        if ($issue->getIssueId() == null) {
            return $this->_insert($issue);
        } else {
            return $this->_update($issue);
        }
    }

    protected function _insert(Default_Model_Issue $issue)
    {
        $data = array(
            'title'             => $issue->getTitle(false),
            'description'       => $issue->getDescription(false),
            'status'            => $issue->getStatus(),
            'project'           => $issue->getProject()->getProjectId(),
            'created_by'        => $issue->getCreatedBy()->getUserId(),
            'created_time'      => new Zend_Db_Expr('NOW()'),
            'private'           => $issue->isPrivate() ? 1 : 0,
        );

        if ($issue->getAssignedTo() != null) {
            $data['assigned_to'] = $issue->getAssignedTo()->getUserId();
        }

        $db = $this->getWriteAdapter();
        $db->insert($this->getTableName(), $data);
        return $db->lastInsertId();
    }

    protected function _update(Default_Model_Issue $issue)
    {
        $db = $this->getWriteAdapter();
        $oldIssue = $this->getIssueById($issue->getIssueId());

        $data = array();
        if ($oldIssue->getTitle() != $issue->getTitle()) {
            $data['title'] = $issue->getTitle(false);
            $oldData['title'] = $oldIssue->getTitle();
        }

        if ($oldIssue->getDescription() != $issue->getDescription()) {
            $data['description'] = $issue->getDescription(false);
            $oldData['description'] = $oldIssue->getDescription();
        }

        if ($oldIssue->getStatus() != $issue->getStatus()) {
            $data['status'] = $issue->getStatus();
            $oldData['status'] = $oldIssue->getStatus();
        }

        if ($oldIssue->getProject() != $issue->getProject()) {
            $data['project'] = $issue->getProject();
            $oldData['project'] = $oldIssue->getProject();
        }

        if ($oldIssue->getPrivate() != $issue->getPrivate()) {
            $data['private'] = $issue->getPrivate() ? 1 : 0;
            $oldData['private'] = $oldIssue->getPrivate() ? 1 : 0;
        }

        if (($oldIssue->getAssignedTo() != null) && ($issue->getAssignedTo() != null)) {
            if ($oldIssue->getAssignedTo()->getUserId() != $issue->getAssignedTo()->getUserId()) {
                $data['assigned_to'] = $issue->getAssignedTo()->getUserId();
                $oldData['assigned_to'] = $oldIssue->getAssignedTo()->getUserId();
            }
        } else if (($oldIssue->getAssignedTo() == null) && ($issue->getAssignedTo() != null)) {
            $data['assigned_to'] = $issue->getAssignedTo()->getUserId();
            $oldData['assigned_to'] = '';
        } else if (($oldIssue->getAssignedTo() != null) && ($issue->getAssignedTo() == null)) {
            $data['assigned_to'] = '';
            $oldData['assigned_to'] = $oldIssue->getAssignedTo()->getUserId();
        } else if ($oldIssue->getAssignedTo() == null && $issue->getAssignedTo() == null) {
            // no update
        }

        if (!count($data)) {
            return true;
        }

        // save audit trail
        $changes = array();
        $changes['action'] = 'update';
        $changes['fields'] = array();
        foreach ($data as $field => $newValue) {
            if ($field != 'status' && $field != 'private') {
                $changes['fields'][$field] = array(
                    'old_value'     => $oldData[$field],
                    'new_value'     => $newValue
                );
            }
        }

        $changes = array($changes);
        foreach ($data as $field => $newValue) {
            if ($field == 'status') {
                $changes[] = array(
                    'action'    => 'open-close',
                    'old_value' => $oldData[$field],
                    'new_value' => $newValue
                );
            }

            if ($field == 'status') {
                $changes[] = array(
                    'action'    => 'changed-privacy',
                    'old_value' => $oldData[$field],
                    'new_value' => $newValue
                );
            }
        }
        
        
        $this->auditTrail($issue, $changes);

        $data['last_update_time'] = new Zend_Db_Expr('NOW()');

        return $db->update('issue', $data, array(
            'issue_id = ?'  => $issue->getIssueId()
        ));
    }

    public function updateLastUpdate($issue)
    {
        $data = array(
            'last_update_time' => new Zend_Db_Expr('NOW()')
        );
        $db = $this->getWriteAdapter();
        return $db->update($this->getTableName(), $data, $db->quoteInto('issue_id = ?', $issue->getIssueId()));
    }

    public function addLabelToIssue(Default_Model_Issue $issue, Default_Model_Label $label, $audit = true)
    {
        $data = array(
            'issue_id'  => $issue->getIssueId(),
            'label_id'  => $label->getLabelId()
        );

        $db = $this->getWriteAdapter();
        try {
            $db->insert('issue_label_linker', $data);

            if ($audit) {
                $this->auditTrail($issue, array(array(
                    'action'    => 'add-label',
                    'id'        => $label->getLabelId(),
                )));
            }
        } catch (Exception $e) {
            // probably a duplicate key
        }

        return true;
    }

    public function removeLabelFromIssue(Default_Model_Issue $issue, Default_Model_Label $label)
    {
        $where = array(
            'issue_id = ?'  => $issue->getIssueId(),
            'label_id = ?'  => $label->getLabelId()
        );

        $db = $this->getWriteAdapter();
        $rowsAffected = $db->delete('issue_label_linker', $where);

        if ($rowsAffected > 0) {
            $this->auditTrail($issue, array(array(
                'action'    => 'add-label',
                'id'        => $label->getLabelId(),
            )));
        }
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

    public function getIssueCounts()
    {
        $db = $this->getReadAdapter();

        $all = $db->select()
            ->from('issue', array(new Zend_Db_Expr("'all'"), 'COUNT(*)'))
            ->where('status = ?', 'open');
        $all = $this->_addAclJoins($all);
        $all = $this->_addRelationJoins($all, 'issue');

        $userId = Zend_Registry::get('Default_DiContainer')
            ->getUserService()->getIdentity()->getUserId() ?: 0;

        $mine = $db->select()
            ->from('issue', array(new Zend_Db_Expr("'mine'"), 'COUNT(*)'))
            ->where('assigned_to = ?', $userId)
            ->where('status = ?', 'open');
        $mine = $this->_addAclJoins($mine);
        $mine = $this->_addRelationJoins($mine, 'issue');

        $unassigned = $db->select()
            ->from('issue', array(new Zend_Db_Expr("'unassigned'"), 'COUNT(*)'))
            ->where('isnull(assigned_to)')
            ->where('status = ?', 'open');
        $unassigned = $this->_addAclJoins($unassigned);
        $unassigned = $this->_addRelationJoins($unassigned, 'issue');

        $result = $db->fetchAll($db->select()->union(array(
            $all, $mine, $unassigned
        )));

        $return = array(
            'all'           => $result[0]['COUNT(*)'],
            'mine'          => $result[1]['COUNT(*)'],
            'unassigned'    => $result[2]['COUNT(*)']
        );

        return $return;
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

    public function updateIssueLabels($issue, $labels, $audit = false)
    {
        if (!$issue instanceof Default_Model_Issue) return false;
        $read = $this->getReadAdapter();
        $write = $this->getWriteAdapter();
        $issueService = Zend_Registry::get('Default_DiContainer')->getIssueService();
        $labelService = Zend_Registry::get('Default_DiContainer')->getLabelService();
        // Add any new labels
        $issue->setLabels($labelService->getLabelsByIssue($issue));
        foreach ($labels as $label) {
            if (!$issue->hasLabel($label)) {
                $issueService->addLabelToIssue($issue, $label);
            }
        }
        // Delete any removed labels
        $oldLabels = $issue->getLabels();
        $issue->setLabels($labels);
        foreach ($oldLabels as $label) {
            if (!$issue->hasLabel($label)) {
                $issueService->removeLabelFromIssue($issue, $label);
            }
        }
        return true;
    }

    public function updateIssueMilestones($issue, $milestones, $audit = false)
    {
        $read = $this->getReadAdapter();
        $write = $this->getWriteAdapter();
        $changes = array();

        if ($issue instanceof Default_Model_Issue) {
            $issueId = $issue->getIssueId();
        } else {
            $issueId = $issue;
        }

        // read the existing milestones from the database
        $sql = $read->select()
            ->from('issue_milestone_linker', array('milestone_id'))
            ->where('issue_id = ?', $issueId);
        $existingMilestones = $read->fetchAll($sql);

        $existing = array();
        if ($existingMilestones) {
            foreach ($existingMilestones as $i) {
                $existing[] = $i['milestone_id'];
            }
        }

        // compute milestones to be deleted from the db
        $toDelete = array();
        if ($existing) {
            foreach ($existing as $i) {
                if (!in_array($i, $milestones)) {
                    $toDelete[] = $i;
                }
            }
        }

        // delete milestones from the db
        if (count($toDelete)) {
            $write->delete('issue_milestone_linker', array(
                'issue_id = ?'          => $issueId,
                'milestone_id IN (?)'   => $toDelete
            )); 

            if ($audit) {
                foreach ($toDelete as $i) {
                    $changes[] = array(
                        'action'    => 'remove-milestone',
                        'id'        => $i
                    );
                }
            }
        }

        // compute milestones to be added to the db
        $toAdd = array();
        if ($existing) {
            foreach ($milestones as $i) {
                if (!in_array($i, $existing)) {
                    $toAdd[] = $i;
                }
            }
        } else {
            $toAdd = $milestones;
        }

        // add milestones to the database
        foreach ($toAdd as $i) {
            $write->insert('issue_milestone_linker', array(
                'issue_id'      => $issueId,
                'milestone_id'  => $i
            ));

            // audit trail
            if ($audit) {
                $changes[] = array(
                    'action'    => 'add-milestone',
                    'id'        => $i
                );
            }
        }

        if (count($changes)) {
            $this->auditTrail($issue, $changes);
        }
    }

    public function clearIssueResourceRecords($issue)
    {
        if ($issue instanceof Default_Model_Issue) {
            $issue = $issue->getIssueId();
        }

        $this->getWriteAdapter()->delete('acl_resource_record', array(
            'resource_type = ?' => 'issue',
            'resource_id = ?'   => $issue
        ));
    }

    public function auditTrail($issue, array $changes)
    {
        $user = Zend_Registry::get('Default_DiContainer')
            ->getUserService()
            ->getIdentity();

        $comment = new Default_Model_Comment();
        $comment->setCreatedBy($user)
            ->setIssue($issue)
            ->setPrivate(false)
            ->setSystem(true);

        $text = array();
        $statusChange = false;
        foreach ($changes as $c) {
            if ($c['action'] == 'update') {
                foreach ($c['fields'] as $field => $values) {
                    $text[] = "#{$field}# changed from {$values['old_value']} to {$values['new_value']}";
                }
            } else if ($c['action'] == 'open-close') {
                if ($values['new_value'] == 'open') {
                    $statusChange = 'open';
                } else {
                    $statusChange = 'close';
                }
            } else if ($c['action'] == 'changed-privacy') {
                if ($values['new_value'] == false) {
                    $text[] = "#made_issue_public#";
                } else {
                    $text[] = "#made_issue_private#";
                }
            } else {
                $text[] = "#{$c['action']}# #id:{$c['id']}#";
            }
        }

        $comment->setText(implode("\n", $text));
        Zend_Registry::get('Default_DiContainer')
            ->getCommentService()
            ->save($comment);

        if ($statusChange !== false) {
            $comment = new Default_Model_Comment();
            $comment->setCreatedBy($user)
                ->setIssue($issue)
                ->setPrivate(false)
                ->setSystem(true);
            if ($statusChange == 'open') {
                $comment->setText('#issue-opened');
            } else {
                $comment->setText('#issue-closed');
            }

            Zend_Registry::get('Default_DiContainer')
                ->getCommentService()
                ->save($comment);
        }
    }

    protected function _addAclJoins(Zend_Db_Select $sql, $alias = null, $primaryKey = null)
    {
        if (Zend_Registry::get('Default_DiContainer')->getAclService()->isAllowed('issue', 'view-all')) {
            return $sql;
        }

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

    protected function _addLabelConcat(Zend_Db_Select $sql, $alias = null)
    {
        $alias = $alias ?: $this->getTableName();

        // have to have array() as the last param or issue_id will get 
        // overwritten with a 0 if there are no issues to join
        $sql->joinLeft(array('ill'=>'issue_label_linker'), "{$alias}.issue_id = ill.issue_id", array()); 
        $sql->columns(array('labels'=>'GROUP_CONCAT(DISTINCT ill.label_id SEPARATOR \' \')'));
        $sql->group($alias.'.issue_id');
        return $sql;
    }

    protected function _addRelationJoins(Zend_Db_Select $sql, $alias = null)
    {
        $alias = $alias ?: 'i';

        $sql->join(array('r_project'=>'project'), "r_project.project_id = $alias.project", array(
            'project.project_id'    => 'project_id',
            'project.name'          => 'name',
            'project.private'       => 'private'
        ));

        $sql->join(array('r_createdby'=>'user'), "r_createdby.user_id = $alias.created_by", array(
            'created_by.user_id'        => 'user_id',
            'created_by.username'       => 'username',
            'created_by.password'       => 'password',
            'created_by.last_login'     => 'last_login',
            'created_by.last_ip'        => new Zend_Db_Expr('INET_NTOA(`r_createdby`.`last_ip`)'),
            'created_by.register_time'  => 'register_time',
            'created_by.register_ip'    => new Zend_Db_Expr('INET_NTOA(`r_createdby`.`register_ip`)')
        ));

        $sql->joinLeft(array('r_assignedto'=>'user'), "r_assignedto.user_id = $alias.assigned_to", array(
            'assigned_to.user_id'        => 'user_id',
            'assigned_to.username'       => 'username',
            'assigned_to.password'       => 'password',
            'assigned_to.last_login'     => 'last_login',
            'assigned_to.last_ip'        => new Zend_Db_Expr('INET_NTOA(`r_assignedto`.`last_ip`)'),
            'assigned_to.register_time'  => 'register_time',
            'assigned_to.register_ip'    => new Zend_Db_Expr('INET_NTOA(`r_assignedto`.`register_ip`)')
        ));

        return $sql;
    }

    protected function _rowToModel($row, $class = false)
    {
        if (array_key_exists('project.project_id', $row)) {
            $row['project'] = new Default_Model_Project(array(
                'project_id'    => $row['project.project_id'],
                'name'          => $row['project.name'],
                'private'       => $row['project.private']
            ));

            unset($row['project.project_id'],
                $row['project.name'],
                $row['project.private']);
        }

        if (array_key_exists('created_by.user_id', $row)) {
            $row['created_by'] = new Default_Model_User(array(
                'user_id'       => $row['created_by.user_id'],
                'username'      => $row['created_by.username'],
                'password'      => $row['created_by.password'],
                'last_login'    => $row['created_by.last_login'],
                'last_ip'       => $row['created_by.last_ip'],
                'register_time' => $row['created_by.register_time'],
                'register_ip'   => $row['created_by.register_ip'],
            ));

            unset($row['created_by.user_id'],
                $row['created_by.username'],
                $row['created_by.password'],
                $row['created_by.last_login'],
                $row['created_by.last_ip'],
                $row['created_by.register_time'],
                $row['created_by.register_ip']
            );
        }

        if (array_key_exists('assigned_to.user_id', $row) && $row['assigned_to.user_id'] != null) {
            $row['created_by'] = new Default_Model_User(array(
                'user_id'       => $row['assigned_to.user_id'],
                'username'      => $row['assigned_to.username'],
                'password'      => $row['assigned_to.password'],
                'last_login'    => $row['assigned_to.last_login'],
                'last_ip'       => $row['assigned_to.last_ip'],
                'register_time' => $row['assigned_to.register_time'],
                'register_ip'   => $row['assigned_to.register_ip'],
            ));
        }

        unset($row['assigned_to.user_id'],
            $row['assigned_to.username'],
            $row['assigned_to.password'],
            $row['assigned_to.last_login'],
            $row['assigned_to.last_ip'],
            $row['assigned_to.register_time'],
            $row['assigned_to.register_ip']
        );
        return parent::_rowToModel($row);
    }
}
