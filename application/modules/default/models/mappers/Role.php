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
        return $this->_rowsToModels($rows);
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

    protected function _rowsToModels($rows)
    {
        if (!$rows) return array();
        foreach ($rows as $i => $row) {
            $rows[$i] = new Default_Model_Role($row);
        }
        return $rows;
    }
}
