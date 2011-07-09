<?php
class Default_Model_Mapper_AclRecord extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'acl_record';
    protected $_modelClass = 'Default_Model_AclRecord';

    public function getAllRecords()
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName());
        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }
}
