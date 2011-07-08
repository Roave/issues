<?php
class Default_Model_Mapper_AclResourceRecord extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'acl_resource_record';

    public function addResourceRecord(array $roles, $resourceType, $resourceId)
    {
        $db = $this->getWriteAdapter();
        foreach ($roles as $role) {
            $data = array(
                'role_id'       => $role,
                'resource_type' => $resourceType,
                'resource_id'   => $resourceId
            );

            $db->insert('acl_resource_record', $data);
        }

        return true;
    }

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
