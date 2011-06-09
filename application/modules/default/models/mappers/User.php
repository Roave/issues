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
        return new Default_Model_User($db->fetchRow($sql));
    }
}
