<?php
class Default_Model_Project extends Issues_Model_Abstract
{
    /**
     * _projectId 
     * 
     * @var int
     */
    protected $_projectId;

    /**
     * _projectName 
     * 
     * @var string
     */
    protected $_projectName;


 
    /**
     * Get projectId.
     *
     * @return projectId
     */
    public function getProjectId()
    {
        return $this->_projectId;
    }
 
    /**
     * Set projectId.
     *
     * @param $projectId the value to be set
     */
    public function setProjectId($projectId)
    {
        $this->_projectId = (int)$projectId;
        return $this;
    }
 
    /**
     * Get projectName.
     *
     * @return projectName
     */
    public function getProjectName()
    {
        return $this->_projectName;
    }
 
    /**
     * Set projectName.
     *
     * @param $projectName the value to be set
     */
    public function setProjectName($projectName)
    {
        $this->_projectName = $projectName;
        return $this;
    }
}
