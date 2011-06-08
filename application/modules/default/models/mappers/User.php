<?php
class Default_Model_Mapper_User extends Zend_Db_Table_Abstract
{
    protected $_name = 'user';

    public function getUserByUsername($username)
    {
        $user = $this->select()->where('username = ?', $username);
        return new Default_Model_User($this->fetchRow($user));
    }
}
