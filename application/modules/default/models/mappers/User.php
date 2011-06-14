<?php
class Default_Model_Mapper_User extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'user';

    public function getUserByUsername($username)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
                  ->from($this->getTableName())
                  ->where('username = ?', $username);
        $row = $db->fetchRow($sql);
        return ($row) ? new Default_Model_User($row) : false;
    }

    public function getUserById($userId)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
                  ->from($this->getTableName())
                  ->where('user_id = ?', $userId);
        $row = $db->fetchRow($sql);
        return ($row) ? new Default_Model_User($row) : false;
    }

    public function getUserByEmail($email)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
                  ->from($this->getTableName())
                  ->where('email = ?', $email);
        $row = $db->fetchRow($sql);
        return ($row) ? new Default_Model_User($row) : false;
    }
}
