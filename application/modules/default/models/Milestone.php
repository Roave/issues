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
     * Misc storager
     *
     * @var array
     */
    protected $_storage = array();

    /**
     * __construct 
     * 
     * @return void
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $acl = Zend_Registry::get('Default_DiContainer')->getAclService();
        $acl->addResource($this);
    }
 
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

    public function getIssues($status = null)
    {
        if (!isset($this->_storage["getIssues-$status"])) {
            $this->_storage["getIssues-$status"] = Zend_Registry::get('Default_DiContainer')->getIssueService()
                ->getIssuesByMilestone($this, $status);
        }

        return $this->_storage["getIssues-$status"];
    }

    public function getProgress()
    {
        $allIssues = count($this->getIssues());

        if ($allIssues === 0) {
            return 0.0;
        }

        return (count($this->getIssues('closed')) / $allIssues) * 100;
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
}
