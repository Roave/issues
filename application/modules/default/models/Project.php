<?php
class Default_Model_Project extends Issues_Model_Abstract implements Zend_Acl_Resource_Interface
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
     * _private 
     * 
     * @var boolean
     */
    protected $_private;

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
        $this->_projectId = (int) $projectId;
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
     * Get URL.
     *
     * @return URL
     */
    public function getUrlSafeName()
    {
        $url = strtolower(trim($this->getName()));
        $url = preg_replace('/[^a-z0-9]+/', '-', $url);  // replace non-alphanum with hyphen
        $url = preg_replace('/[-]{2,}/', '-', $url);    // replace multiple hyphens with 1
        $url = trim($url, '-');                         // trim extra hyphens
        return $url;
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
     * getResourceId 
     * 
     * @return string
     */
    public function getResourceId()
    {
        return 'project-' . $this->getProjectId();
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
