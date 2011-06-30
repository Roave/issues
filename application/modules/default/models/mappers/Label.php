<?php
class Default_Model_Mapper_Label extends Issues_Model_Mapper_DbAbstract {
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
        $rows = $db->fetchAll($rows);
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
