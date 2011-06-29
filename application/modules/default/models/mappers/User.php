<?php
class Default_Model_Mapper_User extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'user';

    protected $_fields;

    protected function _init()
    {
        $this->_fields = array('*',
            'last_ip'      => new Zend_Db_Expr('INET_NTOA(`last_ip`)'),
            'register_ip'  => new Zend_Db_Expr('INET_NTOA(`register_ip`)')
        );
    }

    public function getUserByUsername($username)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName(), $this->_fields)
            ->where('username = ?', $username);
        $row = $db->fetchRow($sql);
        return ($row) ? new Default_Model_User($row) : false;
    }

    public function getUserById($userId)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName(), $this->_fields)
                  ->where('user_id = ?', $userId);
        $row = $db->fetchRow($sql);
        return ($row) ? new Default_Model_User($row) : false;
    }

    public function insert(Default_Model_User $user)
    {
        $data = array(
            'user_id'       => $user->getUserId(),
            'username'      => $user->getUsername(),
            'password'      => sha1($user->getPassword().'somes@lt'),
            'role'          => $user->getRole()->getRoleId(),
            'register_time' => new Zend_Db_Expr('NOW()'),
            'register_ip'   => new Zend_Db_Expr("INET_ATON('{$_SERVER['REMOTE_ADDR']}')"),
        );
        $db = $this->getWriteAdapter();
        $db->insert($this->getTableName(), $data);
        return $db->lastInsertId();
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
}
