<?php
class Default_Model_Mapper_AclRecord extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'acl_record';

    public function getAllRecords()
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName());
        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    protected function _rowsToModels($rows)
    {
        if (!$rows) return array();
        foreach ($rows as $i => $row) {
            $rows[$i] = new Default_Model_AclRecord($row);
        }
        return $rows;
    }
}
