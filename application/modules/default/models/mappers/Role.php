<?php
class Default_Model_Mapper_Role extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'user_role';
    protected $_modelClass = 'Default_Model_Role';

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
        if ($model = $this->_getCachedModel($roleId)) return $model;
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('role_id = ?', $roleId);
        $row = $db->fetchRow($sql);
        return $this->_rowToModel($row);
    }

    public function getRoleByName($name)
    {
        if ($model = $this->_getCachedModel($name)) return $model;
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('name = ?', $name);
        $row = $db->fetchRow($sql);
        return $this->_rowToModel($row);
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

    protected function _addModelToCache($model)
    {
        $keys = array($model->getRoleId(), $model->getName());
        $this->_setCachedModel($model, $keys);
    }
}
