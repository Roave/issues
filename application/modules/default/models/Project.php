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
     * _name 
     * 
     * @var string
     */
    protected $_name;


 
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
}
