<?php
class Default_Model_Issue extends Issues_Model_Abstract implements Zend_Acl_Resource_Interface 
{
    /**
     * _issueId 
     * 
     * @var int
     */
    protected $_issueId;

    /**
     * _title 
     * 
     * @var string
     */
    protected $_title;

    /**
     * _description 
     * 
     * @var string
     */
    protected $_description;

    /**
     * _status 
     * 
     * @var string
     */
    protected $_status;

    /**
     * _project 
     * 
     * @var Default_Model_Project
     */
    protected $_project;

    /**
     * _createdBy 
     * 
     * @var Default_Model_User
     */
    protected $_createdBy;

    /**
     * _assignedTo 
     * 
     * @var Default_Model_User
     */
    protected $_assignedTo;

    /**
     * _createdTime 
     * 
     * @var DateTime
     */
    protected $_createdTime;

    /**
     * _lastUpdateTime 
     * 
     * @var DateTime
     */
    protected $_lastUpdateTime;
 
    /**
     * _private 
     * 
     * @var boolean
     */
    protected $_private;

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
        $this->_issueId = (int) $issueId;
        return $this;
    }
 
    /**
     * Get title.
     *
     * @return title
     */
    public function getTitle()
    {
        return $this->_title;
    }
 
    /**
     * Set title.
     *
     * @param $title the value to be set
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }
 
    /**
     * Get description.
     *
     * @return description
     */
    public function getDescription()
    {
        return $this->_description;
    }
 
    /**
     * Set description.
     *
     * @param $description the value to be set
     */
    public function setDescription($description)
    {
        $this->_description = $description;
        return $this;
    }
 
    /**
     * Get status.
     *
     * @return status
     */
    public function getStatus()
    {
        return $this->_status;
    }
 
    /**
     * Set status.
     *
     * @param $status the value to be set
     */
    public function setStatus($status)
    {
        $this->_status = $status;
        return $this;
    }
 
    /**
     * Get project.
     *
     * @return project
     */
    public function getProject()
    {
        return $this->_project;
    }
 
    /**
     * Set project.
     *
     * @param $project the value to be set
     */
    public function setProject($project)
    {
        if ($project instanceof Default_Model_Project) {
            $this->_project = $project;
        } elseif (is_numeric($project)) {
            $this->_project = Zend_Registry::get('Default_DiContainer')->getProjectMapper()->getProjectById((int)$project);
        }
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
     * Get assignedTo.
     *
     * @return assignedTo
     */
    public function getAssignedTo()
    {
        return $this->_assignedTo;
    }
 
    /**
     * Set assignedTo.
     *
     * @param $assignedTo the value to be set
     */
    public function setAssignedTo($assignedTo)
    {
        if ($assignedTo instanceof Default_Model_User) {
            $this->_assignedTo = $assignedTo;
        } elseif (is_numeric($assignedTo)) {
            $this->_assignedTo = Zend_Registry::get('Default_DiContainer')->getUserMapper()->getUserById((int)$assignedTo);
        }
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
     * Get lastUpdateTime.
     *
     * @return lastUpdateTime
     */
    public function getLastUpdateTime()
    {
        if ($this->_lastUpdateTime) {
            return $this->_adjustedDateTime($this->_lastUpdateTime);
        } else {
            return false;
        }
    }
 
    /**
     * Set lastUpdateTime.
     *
     * @param $lastUpdateTime the value to be set
     */
    public function setLastUpdateTime($lastUpdateTime)
    {
        if ($lastUpdateTime !== null) {
            $this->_lastUpdateTime = new DateTime($lastUpdateTime);
        } else {
            $this->_lastUpdateTime = false;
        }
        return $this;
    }

    public function getLabels()
    {
        return Zend_Registry::get('Default_DiContainer')->getLabelService()
            ->getLabelsByIssue($this);
    }

    /**
     * getResourceId 
     * 
     * @return string
     */
    public function getResourceId()
    {
        return 'issue-' . $this->getIssueId();
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
}
