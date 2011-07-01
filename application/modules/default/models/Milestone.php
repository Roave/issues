<?php
class Default_Model_Label extends Issues_Model_Abstract 
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
}
