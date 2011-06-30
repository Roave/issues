<?php
class Default_Model_Mapper_Label extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'label';

    public function getLabelById($id)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('label_id = ?', $id);
        $row = $db->fetchRow($sql);
        return ($row) ? new Default_Model_Label($row) : false;
    }

    public function getAllLabels()
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName());
        $rows = $db->fetchAll($sql);
        if (!$rows) return array();

        $return = array();
        foreach ($rows as $i => $row) {
            $return[$i] = new Default_Model_Label($row);
        }
        return $return;
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

        $rows = $db->fetchAll($sql);
        if (!$rows) return array();

        $return = array();
        foreach ($rows as $i => $row) {
            $return[$i] = new Default_Model_Label($row);
        }
        return $return;
    }

    public function insert(Default_Model_Label $label)
    {
        $data = array(
            'text'  => $label->getText(),
            'color' => $label->getColor()
        );

        $db = $this->getWriteAdapter();
        $db->insert($this->getTableName(), $data);
        return $db->lastInsertId();
    }
}
