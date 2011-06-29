<?php
class Default_Model_Mapper_Role extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'user_role';

    public function getRoles()
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
                  ->from($this->getTableName());
        $rows = $db->fetchAll($sql);
        return $rows;
    }

    public function getRoleById($roleId)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('role_id = ?', $roleId);
        $row = $db->fetchRow($sql);
        return ($row) ? new Default_Model_Role($row) : false;
    }
}
