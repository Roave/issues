<?php
class Default_Model_Mapper_User extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'user';
    protected $_modelClass = 'Default_Model_User';

    protected $_fields;

    protected function _init()
    {
        $this->_fields = array('*',
            'last_ip'      => new Zend_Db_Expr('INET_NTOA(`last_ip`)'),
            'register_ip'  => new Zend_Db_Expr('INET_NTOA(`register_ip`)')
        );
    }

    public function getUserByUsername($username, $addSettings = false)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName(), $this->_fields)
            ->where('username = ?', $username);
        $row = $db->fetchRow($sql);
        return $this->_rowToModel($row);
    }

    public function getUserById($userId)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName(), $this->_fields)
                  ->where('user_id = ?', $userId);
        $row = $db->fetchRow($sql);
        return $this->_rowToModel($row);
    }

    public function insert(Default_Model_User $user)
    {
        $data = array(
            'user_id'       => $user->getUserId(),
            'username'      => $user->getUsername(),
            'password'      => sha1($user->getPassword().'somes@lt'),
            'register_time' => new Zend_Db_Expr('NOW()'),
            'register_ip'   => new Zend_Db_Expr("INET_ATON('{$_SERVER['REMOTE_ADDR']}')"),
        );
        $db = $this->getWriteAdapter();
        $db->insert($this->getTableName(), $data);
        $userId = $db->lastInsertId();
        $user->setUserId($userId);
        $this->updateUserRoles($user);
        return $userId;
    }

    public function updateUserRoles($user)
    {
        $db = $this->getWriteAdapter();
        foreach ($user->getRoles() as $role) {
            $data = array(
                'user_id' => $user->getUserId(),
                'role_id' => $role->getRoleId(),
            );
            $db->insert('user_role_linker', $data);
        }
    }

    public function updateLastLogin($user)
    {
        $data = array(
            'last_login' => new Zend_Db_Expr('NOW()'),
            'last_ip'    => new Zend_Db_Expr("INET_ATON('{$_SERVER['REMOTE_ADDR']}')")
        );
        $db = $this->getWriteAdapter();
        return $db->update($this->getTableName(), $data, $db->quoteInto('user_id = ?', $user->getUserId()));
    }

    public function getAllUsers()
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName());
        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    public function getUserSettings($user)
    {
        if ($user instanceof Default_Model_User) {
            $user = $user->getUserId();
        }

        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from('user_settings')
            ->where('user_id = ?', $user);
        
        $rows = $db->fetchAll($sql);
        if (!$rows) return array();

        $return = array();
        foreach ($rows as $row) {
            $return[$row['name']] = $row['value'];
        }

        return $return;
    }

    public function insertUserSetting($user, $key, $value)
    {
        if ($user instanceof Default_Model_User) {
            $user = $user->getUserId();
        }

        $data = array(
            'user_id'   => $user,
            'key'       => $key,
            'value'     => $value
        );

        try {
            $this->getWriteAdapter()->insert('user_settings', $data);
        } catch (Exception $e) {} // probably a duplicate key
    }

    public function updateUserSetting($user, $key, $value)
    {
        if ($user instanceof Default_Model_User) {
            $user = $user->getUserId();
        }

        $data = array('value' => $value);
        $where = array(
            'user_id = ?' => $user,
            'name = ?' => $key
        );

        return $this->getWriteAdapter()->update('user_settings', $data, $where);
    }

    protected function _addSettings($row)
    {
        if (is_array($row) && is_numeric($row['user_id'])) {
            $row['settings'] = $this->getUserSettings($row['user_id']);
        }
        return $row;
    }
}
