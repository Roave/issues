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

    public function getRoleByName($name)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('name = ?', $name);
        $row = $db->fetchRow($sql);

        return ($row) ? new Default_Model_Role($row) : false;
    }

    public function getRolesByUser($user)
    {
        if ($user instanceof Default_Model_User) {
            $user = $user->getUserId();
        }

        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from(array('ur'=>$this->getTableName()))
            ->join(array('url'=>'user_role_linker'), 'url.role_id = ur.role_id')
            ->where('url.user_id = ?', $user);
        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    public function addUserToRole($user, $role)
    {
        if ($user instanceof Default_Model_User) {
            $user = $user->getRoleId();
        }

        if ($role instanceof Default_Model_Role) {
            $role = $role->getRoleId();
        }

        $data = array(
            'user_id'   => $user,
            'role_id'   => $role
        );

        return $db->getWriteAdapter()->insert('user_role_linker', $data);
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
