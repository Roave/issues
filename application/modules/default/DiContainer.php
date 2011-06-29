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
}
