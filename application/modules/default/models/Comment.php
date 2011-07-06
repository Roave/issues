<?php
class Default_Model_Comment extends Issues_Model_Abstract implements Zend_Acl_Resource_Interface
{
    /**
     * _commentId 
     * 
     * @var int
     */
    protected $_commentId;
    
    /**
     * _createdTime 
     * 
     * @var DateTime
     */
    protected $_createdTime;
    
    /**
     * _createdBy 
     * 
     * @var Default_Model_User
     */
    protected $_createdBy;

    /**
     * _issue
     *
     * @var Default_Model_Issue
     */
    protected $_issue;

    /**
     * _text 
     * 
     * @var string
     */
    protected $_text;

    /**
     * Get commentId.
     *
     * @return commentId
     */
    public function getCommentId()
    {
        return $this->_commentId;
    }
 
    /**
     * Set commentId.
     *
     * @param $commentId the value to be set
     */
    public function setCommentId($commentId)
    {
        $this->_commentId = (int) $commentId;
        return $this;
    }
 
    /**
     * Get createdTime.
     *
     * @return createdTime
     */
    public function getCreatedTime()
    {
        return $this->_adjustedDateTime($this->_createdTime);
    }
 
    /**
     * Set createdTime.
     *
     * @param $createdTime the value to be set
     */
    public function setCreatedTime($createdTime)
    {
        $this->_createdTime = new DateTime($createdTime);
        return $this;
    }
 
    /**
     * Get createdBy.
     *
     * @return createdBy
     */
    public function getCreatedBy()
    {
        return $this->_createdBy;
    }
 
    /**
     * Set createdBy.
     *
     * @param $createdBy the value to be set
     */
    public function setCreatedBy($createdBy)
    {
        if ($createdBy instanceof Default_Model_User) {
            $this->_createdBy = $createdBy;
        } elseif (is_numeric($createdBy)) {
            $this->_createdBy = Zend_Registry::get('Default_DiContainer')->getUserMapper()->getUserById((int)$createdBy);
        }
        return $this;
    }
 
    /**
     * Get text.
     *
     * @return text
     */
    public function getText()
    {
        return $this->_text;
    }
 
    /**
     * Set text.
     *
     * @param $text the value to be set
     */
    public function setText($text)
    {
        $this->_text = $text;
        return $this;
    }
 
    /**
     * Get issue.
     *
     * @return issue
     */
    public function getIssue()
    {
        return $this->_issue;
    }
 
    /**
     * Set issue.
     *
     * @param $issue the value to be set
     */
    public function setIssue($issue)
    {
        if ($issue instanceof Default_Model_Issue) {
            $this->_issue = $issue;
        } elseif (is_numeric($issue)) {
            $this->_issue = Zend_Registry::get('Default_DiContainer')->getIssueMapper()->getIssueById((int)$issue);
        }

        return $this;
    }

    /**
     * getResourceId 
     * 
     * @return string
     */
    public function getResourceId()
    {
        return 'comment-' . $this->getCommentId();
    }
}
