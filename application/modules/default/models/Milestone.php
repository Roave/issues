<?php
class Default_Model_Milestone extends Issues_Model_Abstract implements Zend_Acl_Resource_Interface
{
    /**
     * _milestoneId 
     * 
     * @var int
     */
    protected $_milestoneId;

    /**
     * _name 
     * 
     * @var string
     */
    protected $_name;

    /**
     * _dueDate 
     * 
     * @var DateTime
     */
    protected $_dueDate;

    /**
     * _completedDate 
     * 
     * @var DateTime
     */
    protected $_completedDate;

    /**
     * _private 
     * 
     * @var boolean
     */
    protected $_private;

    /**
     * _openCount 
     * 
     * @var int
     */
    protected $_openCount;

    /**
     * _closedCount 
     * 
     * @var int
     */
    protected $_closedCount;

    /**
     * Get milestoneId.
     *
     * @return milestoneId
     */
    public function getMilestoneId()
    {
        return $this->_milestoneId;
    }
 
    /**
     * Set milestoneId.
     *
     * @param $milestoneId the value to be set
     */
    public function setMilestoneId($milestoneId)
    {
        $this->_milestoneId = (int) $milestoneId;
        return $this;
    }
 
    /**
     * Get name.
     *
     * @return name
     */
    public function getName()
    {
        return $this->_name;
    }
 
    /**
     * Set name.
     *
     * @param $name the value to be set
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }
 
    /**
     * Get dueDate.
     *
     * @return dueDate
     */
    public function getDueDate()
    {
        return $this->_adjustedDateTime($this->_dueDate);
    }
 
    /**
     * Set dueDate.
     *
     * @param $dueDate the value to be set
     */
    public function setDueDate($dueDate)
    {
        $this->_dueDate = new DateTime($dueDate);
        return $this;
    }
 
    /**
     * Get completedDate.
     *
     * @return completedDate
     */
    public function getCompletedDate()
    {
        return $this->_adjustedDateTime($this->_completedDate);
    }
 
    /**
     * Set completedDate.
     *
     * @param $completedDate the value to be set
     */
    public function setCompletedDate($completedDate)
    {
        $this->_completedDate = new DateTime($completedDate);
        return $this;
    }

    public function getProgress()
    {
        $allIssues = $this->getOpenCount() + $this->getClosedCount();

        if ($allIssues === 0) {
            return 0.0;
        }

        return ($this->getClosedCount() / $allIssues) * 100;
    }

    /**
     * getResourceId 
     * 
     * @return string
     */
    public function getResourceId()
    {
        return 'milestone-' . $this->getMilestoneId();
    }
 
    /**
     * Get private.
     *
     * @return private
     */
    public function getPrivate()
    {
        return $this->_private;
    }
 
    /**
     * Set private.
     *
     * @param $private the value to be set
     */
    public function setPrivate($private)
    {
        $this->_private = $private;
        return $this;
    }

    /**
     * Get private
     *
     * @return private
     */
    public function isPrivate()
    {
        return $this->getPrivate();
    }
 
    /**
     * Get openCount.
     *
     * @return openCount
     */
    public function getOpenCount()
    {
        return $this->_openCount;
    }
 
    /**
     * Set openCount.
     *
     * @param $openCount the value to be set
     */
    public function setOpenCount($openCount)
    {
        $this->_openCount = (int)$openCount;
        return $this;
    }
 
    /**
     * Get closedCount.
     *
     * @return closedCount
     */
    public function getClosedCount()
    {
        return $this->_closedCount;
    }
 
    /**
     * Set closedCount.
     *
     * @param $closedCount the value to be set
     */
    public function setClosedCount($closedCount)
    {
        $this->_closedCount = (int)$closedCount;
        return $this;
    }
}
