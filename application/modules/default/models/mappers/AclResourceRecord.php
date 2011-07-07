<?php
class Default_Model_Mapper_AclResourceRecord extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'acl_resource_record';

    public function getResourceRecords(array $roles, $resourceType, $resourceId)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('resource_type = ?', strtolower($resourceType))
            ->where('resource_id = ?', $resourceId)
            ->where('role_id IN (?)', $roles);
        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    protected function _rowsToModels($rows)
    {
        if (!$rows) return array();
        foreach ($rows as $i => $row) {
            $rows[$i] = new Default_Model_AclResourceRecord($row);
        }
        return $rows;
    }
}
