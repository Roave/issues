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
        if (!Zend_Registry::get('Default_DiContainer')->getAclService()->isAllowed('comment', 'view-all')) {
            $sql = $this->_addAclJoins($sql);
        }
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

        if (!Zend_Registry::get('Default_DiContainer')->getAclService()->isAllowed('comment', 'view-all')) {
            $sql = $this->_addAclJoins($sql);
        }

        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    public function save(Default_Model_Comment $comment)
    {
        if ($comment->getCommentId() == null) {
            return $this->_insert($comment);
        } else {
            return $this->_update($comment);
        }
    }

    protected function _insert(Default_Model_Comment $comment)
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

    protected function _update(Default_Model_Comment $comment)
    {
        $db = $this->getWriteAdapter();
        $oldComment = $this->getCommentById($comment->getCommentId());

        $data = array();
        if ($oldComment->getText() != $comment->getText()) {
            $data['text'] = $comment->getText();
            $oldData['text'] = $oldComment->getText();
        }

        if ($oldComment->getPrivate() != $comment->getPrivate()) {
            $data['private'] = $comment->getPrivate() ? 1 : 0;
            $oldData['private'] = $oldComment->getPrivate ? 1 : 0;
        }

        if (!count($data)) {
            return true;
        }

        // save audit trail
        foreach ($data as $field => $newValue) {
            $this->auditTrail($comment, 'update', $field, $oldData[$field], $newValue);
        }

        return $db->update('comment', $data, array(
            'comment_id = ?'  => $comment->getCommentId()
        ));
    }

    public function auditTrail($comment, $action, $field, $oldValue, $newValue)
    {
        if ($comment instanceof Default_Model_Comment) {
            $comment = $comment->getCommentId();
        }

        $userId = Zend_Registry::get('Default_DiContainer')
            ->getUserService()
            ->getIdentity()
            ->getUserId();

        $db = $this->getWriteAdapter();
        return $db->insert('comment_history', array(
            'comment_id'        => $comment,
            'revision_author'   => $userId,
            'revision_time'     => new Zend_Db_Expr('NOW()'),
            'action'            => $action,
            'field'             => $field,
            'old_value'         => $oldValue,
            'new_value'         => $newValue
        ));
    }

    public function clearCommentResourceRecords($comment)
    {
        if ($comment instanceof Default_Model_Comment) {
            $comment = $comment->getCommentId();
        }

        $this->getWriteAdapter()->delete('acl_resource_record', array(
            'resource_type = ?' => 'comment',
            'resource_id = ?'   => $comment
        ));
    }
}
