<?php
class Default_Model_Mapper_Comment extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'comment';
    protected $_modelClass = 'Default_Model_Comment';

    public function getCommentById($id)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('comment_id = ?', $id);
        $sql = $this->_addAclJoins($sql);
        $row = $db->fetchRow($sql);
        return $this->_rowToModel($row);
    }

    public function getCommentsByIssue($issue)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from('comment');

        if ($issue instanceof Default_Model_Issue) {
            $sql->where('issue = ?', $issue->getIssueId());
        } else {
            $sql->where('issue = ?', (int) $issue);
        }

        $sql = $this->_addAclJoins($sql);

        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    public function insert(Default_Model_Comment $comment)
    {
        $data = array(
            'created_time'  => new Zend_Db_Expr('NOW()'),
            'created_by'    => $comment->getCreatedBy()->getUserId(),
            'issue'         => $comment->getIssue()->getIssueId(),
            'text'          => $comment->getText(),
            'private'       => $comment->isPrivate() ? 1 : 0
        );

        $db = $this->getWriteAdapter();
        $db->insert($this->getTableName(), $data);
        return $db->lastInsertId();
    }
}
