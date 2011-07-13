<?php
class Default_Model_Mapper_IssueHistory extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'issue_history';
    protected $_modelClass = 'Default_Model_IssueHistory';

    public function getIssueHistory($issue)
    {
        if ($issue instanceof Default_Model_Issue) {
            $issue = $issue->getIssueId();
        }

        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from(array('ih'=>'issue_history'))
            ->join(array('i'=>'issue'), 'ih.issue_id = i.issue_id')
            ->where('ih.issue_id = ?', $issue);

        $sql = $this->_addAclJoins($sql, 'i', 'issue_id');
        $sql = $this->_addRelationJoins($sql, 'ih');

        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    protected function _addRelationJoins(Zend_Db_Select $sql, $alias = null)
    {
        $alias = $alias ?: 'ih';

        $sql->join(array('r_author'=>'user'), "r_author.user_id = $alias.revision_author", array(
            'author.user_id'        => 'user_id',
            'author.username'       => 'username',
            'author.password'       => 'password',
            'author.last_login'     => 'last_login',
            'author.last_ip'        => new Zend_Db_Expr('INET_NTOA(`r_author`.`last_ip`)'),
            'author.register_time'  => 'register_time',
            'author.register_ip'    => new Zend_Db_Expr('INET_NTOA(`r_author`.`register_ip`)')
        ));

        return $sql;
    }

    protected function _rowToModel($row, $class = false)
    {
        if (array_key_exists('author.user_id', $row)) {
            $row['revision_author'] = new Default_Model_User(array(
                'user_id'       => $row['author.user_id'],
                'username'      => $row['author.username'],
                'password'      => $row['author.password'],
                'last_login'    => $row['author.last_login'],
                'last_ip'       => $row['author.last_ip'],
                'register_time' => $row['author.register_time'],
                'register_ip'   => $row['author.register_ip'],
            ));

            unset($row['created_by.user_id'],
                $row['created_by.username'],
                $row['created_by.password'],
                $row['created_by.last_login'],
                $row['created_by.last_ip'],
                $row['created_by.register_time'],
                $row['created_by.register_ip']
            );
        }

        return parent::_rowToModel($row);
    } 
}
