<?php
class Default_Model_Mapper_AclResourceRecord extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'acl_resource_record';
    protected $_modelClass = 'Default_Model_AclResourceRecord';

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
}
