<?php
class Default_DiContainer extends Issues_DiContainerAbstract
{
    /**
     * Get the user service
     *
     * @return Default_Service_User
     */
    public function getUserService()
    {
        if (!isset($this->_storage['userService'])) {
            $this->_storage['userService'] = new Default_Service_User($this->getUserMapper());
        }
        return $this->_storage['userService'];
    }

    /**
     * Get the user mapper
     *
     * @return Default_Model_Mapper_User
     */
    public function getUserMapper()
    {
        if (!isset($this->_storage['userMapper'])) {
            $this->_storage['userMapper'] = new Default_Model_Mapper_User();
        }
        return $this->_storage['userMapper'];
    }

    /**
     * Get the role service
     *
     * @return Default_Service_Role
     */
    public function getRoleService()
    {
        if (!isset($this->_storage['roleService'])) {
            $this->_storage['roleService'] = new Default_Service_Role($this->getRoleMapper());
        }
        return $this->_storage['roleService'];
    }

    /**
     * Get the role mapper
     *
     * @return Default_Model_Mapper_Role
     */
    public function getRoleMapper()
    {
        if (!isset($this->_storage['roleMapper'])) {
            $this->_storage['roleMapper'] = new Default_Model_Mapper_Role();
        }
        return $this->_storage['roleMapper'];
    }

    /**
     * Get the project service
     *
     * @return Default_Service_Project
     */
    public function getProjectService()
    {
        if (!isset($this->_storage['projectService'])) {
            $this->_storage['projectService'] = new Default_Service_Project($this->getProjectMapper());
        }
        return $this->_storage['projectService'];
    }

    /**
     * Get the project mapper
     *
     * @return Default_Model_Mapper_Project
     */
    public function getProjectMapper()
    {
        if (!isset($this->_storage['projectMapper'])) {
            $this->_storage['projectMapper'] = new Default_Model_Mapper_Project();
        }
        return $this->_storage['projectMapper'];
    }

    /**
     * Get the issue service
     *
     * @return Default_Service_Issue
     */
    public function getIssueService()
    {
        if (!isset($this->_storage['issueService'])) {
            $this->_storage['issueService'] = new Default_Service_Issue($this->getIssueMapper());
        }
        return $this->_storage['issueService'];
    }

    /**
     * Get the issue mapper
     *
     * @return Default_Model_Mapper_Issue
     */
    public function getIssueMapper()
    {
        if (!isset($this->_storage['issueMapper'])) {
            $this->_storage['issueMapper'] = new Default_Model_Mapper_Issue();
        }
        return $this->_storage['issueMapper'];
    }

    /**
     * Get the label service 
     * 
     * @return Default_Service_Label
     */
    public function getLabelService()
    {
        if (!isset($this->_storage['labelService'])) {
            $this->_storage['labelService'] = new Default_Service_Label($this->getLabelMapper());
        }
        return $this->_storage['labelService'];
    }

    /**
     * Get the label mapper
     *
     * @return Default_Model_Mapper_Label
     */
    public function getLabelMapper()
    {
        if (!isset($this->_storage['labelMapper'])) {
            $this->_storage['labelMapper'] = new Default_Model_Mapper_Label();
        }
        return $this->_storage['labelMapper'];
    }

    /**
     * Get the milestone service 
     * 
     * @return Default_Service_Milestone
     */
    public function getMilestoneService()
    {
        if (!isset($this->_storage['milestoneService'])) {
            $this->_storage['milestoneService'] = new Default_Service_Milestone($this->getMilestoneMapper());
        }
        return $this->_storage['milestoneService'];
    }

    /**
     * Get the milestone mapper
     *
     * @return Default_Model_Mapper_Milestone
     */
    public function getMilestoneMapper()
    {
        if (!isset($this->_storage['milestoneMapper'])) {
            $this->_storage['milestoneMapper'] = new Default_Model_Mapper_Milestone();
        }
        return $this->_storage['milestoneMapper'];
    }

    /**
     * Get the comment service 
     * 
     * @return Default_Service_Comment
     */
    public function getCommentService()
    {
        if (!isset($this->_storage['commentService'])) {
            $this->_storage['commentService'] = new Default_Service_Comment($this->getCommentMapper());
        }
        return $this->_storage['commentService'];
    }

    /**
     * Get the comment mapper
     *
     * @return Default_Model_Mapper_Comment
     */
    public function getCommentMapper()
    {
        if (!isset($this->_storage['commentMapper'])) {
            $this->_storage['commentMapper'] = new Default_Model_Mapper_Comment();
        }
        return $this->_storage['commentMapper'];
    }

    public function hasAclService()
    {
        return (isset($this->_storage['aclService']));
    }

    public function getAclService()
    {
        if (!isset($this->_storage['aclService'])) {
            $this->_storage['aclService'] = new Default_Service_Acl();
        }
        return $this->_storage['aclService'];
    }

    public function getAclMapper()
    {
        if (!isset($this->_storage['aclMapper'])) {
            $this->_storage['aclMapper'] = new Default_Model_Mapper_AclRecord();
        }
        return $this->_storage['aclMapper'];
    }
}
