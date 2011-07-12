<?php
class Default_Model_Mapper_Label extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'label';
    protected $_modelClass = 'Default_Model_Label';

    public function getLabelById($id)
    {
        if ($model = $this->_getCachedModel($id)) return $model;
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('label_id = ?', $id);

        if (!Zend_Registry::get('Default_DiContainer')->getAclService()->isAllowed('label', 'view-all')) {
            $sql = $this->_addAclJoins($sql);
        }

        $row = $db->fetchRow($sql);
        return $this->_rowToModel($row);
    }

    public function getAllLabels($counts = false, $project = false)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from(array('l' => $this->getTableName()));

        if ($counts === true) {
            $sql->joinLeft(array('ill'=>'issue_label_linker'), 'ill.label_id = l.label_id')
                ->columns(array('count'=>'COUNT(ill.issue_id)'))
                ->group('l.label_id');
        }

        if (!Zend_Registry::get('Default_DiContainer')->getAclService()->isAllowed('label', 'view-all')) {
            $sql = $this->_addAclJoins($sql, 'l', 'label_id');
        }

        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    public function getLabelsByIssue($issue)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from(array('ill'=>'issue_label_linker'))
            ->join(array('l'=>'label'), 'ill.label_id = l.label_id');

        if ($issue instanceof Default_Model_Issue) {
            $sql->where('ill.issue_id = ?', $issue->getIssueId());
        } else {
            $sql->where('ill.issue_id = ?', (int) $issue);
        }

        if (!Zend_Registry::get('Default_DiContainer')->getAclService()->isAllowed('label', 'view-all')) {
            $sql = $this->_addAclJoins($sql, 'l', 'label_id');
        }

        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    public function insert(Default_Model_Label $label)
    {
        $data = array(
            'text'      => $label->getText(),
            'color'     => $label->getColor(),
            'private'   => $label->isPrivate ? 1 : 0,
        );

        $db = $this->getWriteAdapter();
        $db->insert($this->getTableName(), $data);
        return $db->lastInsertId();
    }

    protected function _addModelToCache($model)
    {
        $keys = array($model->getLabelId(), $model->getText());
        $this->_setCachedModel($model, $keys);
    }
}
