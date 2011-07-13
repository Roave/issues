<?php
class Default_Model_IssueHistory extends Issues_Model_Abstract
{
    /**
     * _skipAcl 
     * 
     * @var boolean
     */
    protected $_skipAcl = true;

    /**
     * _issueId 
     * 
     * @var int
     */
    protected $_issueId;

    /**
     * _revisionId 
     * 
     * @var int
     */
    protected $_revisionId;

    /**
     * _revisionAuthor 
     * 
     * @var int
     */
    protected $_revisionAuthor;

    /**
     * _revisionTime 
     * 
     * @var DateTime
     */
    protected $_revisionTime;

    /**
     * _action 
     * 
     * @var string
     */
    protected $_action;

    /**
     * _field 
     * 
     * @var string
     */
    protected $_field;

    /**
     * _oldValue 
     * 
     * @var string
     */
    protected $_oldValue;

    /**
     * _newValue 
     * 
     * @var string
     */
    protected $_newValue;
 
    /**
     * Get issueId.
     *
     * @return issueId
     */
    public function getIssueId()
    {
        return $this->_issueId;
    }
 
    /**
     * Set issueId.
     *
     * @param $issueId the value to be set
     */
    public function setIssueId($issueId)
    {
        $this->_issueId = $issueId;
        return $this;
    }
 
    /**
     * Get revisionId.
     *
     * @return revisionId
     */
    public function getRevisionId()
    {
        return $this->_revisionId;
    }
 
    /**
     * Set revisionId.
     *
     * @param $revisionId the value to be set
     */
    public function setRevisionId($revisionId)
    {
        $this->_revisionId = $revisionId;
        return $this;
    }
 
    /**
     * Get revisionAuthor.
     *
     * @return revisionAuthor
     */
    public function getRevisionAuthor()
    {
        return $this->_revisionAuthor;
    }
 
    /**
     * Set revisionAuthor.
     *
     * @param $revisionAuthor the value to be set
     */
    public function setRevisionAuthor($revisionAuthor)
    {
        if ($revisionAuthor instanceof Default_Model_User) {
            $this->_revisionAuthor = $revisionAuthor;
        } elseif (is_numeric($revisionAuthor)) {
            $this->_revisionAuthor = Zend_Registry::get('Default_DiContainer')
                ->getUserMapper()
                ->getUserById((int) $revisionAuthor);
        }

        return $this;
    }
 
    /**
     * Get revisionTime.
     *
     * @return revisionTime
     */
    public function getRevisionTime()
    {
        return $this->_adjustedDateTime($this->_revisionTime);
    }
 
    /**
     * Set revisionTime.
     *
     * @param $revisionTime the value to be set
     */
    public function setRevisionTime($revisionTime)
    {
        $this->_revisionTime = new DateTime($revisionTime);
        return $this;
    }
 
    /**
     * Get action.
     *
     * @return action
     */
    public function getAction()
    {
        return $this->_action;
    }
 
    /**
     * Set action.
     *
     * @param $action the value to be set
     */
    public function setAction($action)
    {
        $this->_action = $action;
        return $this;
    }
 
    /**
     * Get field.
     *
     * @return field
     */
    public function getField()
    {
        return $this->_field;
    }
 
    /**
     * Set field.
     *
     * @param $field the value to be set
     */
    public function setField($field)
    {
        $this->_field = $field;
        return $this;
    }
 
    /**
     * Get oldValue.
     *
     * @return oldValue
     */
    public function getOldValue()
    {
        return $this->_oldValue;
    }
 
    /**
     * Set oldValue.
     *
     * @param $oldValue the value to be set
     */
    public function setOldValue($oldValue)
    {
        $this->_oldValue = $oldValue;
        return $this;
    }
 
    /**
     * Get newValue.
     *
     * @return newValue
     */
    public function getNewValue()
    {
        return $this->_newValue;
    }
 
    /**
     * Set newValue.
     *
     * @param $newValue the value to be set
     */
    public function setNewValue($newValue)
    {
        $this->_newValue = $newValue;
        return $this;
    }
}
