<?php
class Default_Service_Project extends Issues_ServiceAbstract 
{
    protected $_createForm;

    public function getCreateForm()
    {
        if (null === $this->_createForm) {
            $this->_createForm = new Default_Form_Project_Create();
        }
        return $this->_createForm;
    }

    public function createFromForm(Default_Form_Project_Create $form)
    {
        if (Zend_Auth::getInstance()->getIdentity()->getRole()->getName() == 'guest') {
            return false; 
        } 
        $project = new Default_Model_Project();
        $project->setProjectName($form->getValue('project_name'));
        return $this->_mapper->insert($project);
    }

    public function getAllProjects()
    {
        return $this->_mapper->getAllProjects();
    }

    public function getProjectsForForm()
    {
        $projects = $this->_mapper->getAllProjects();
        $projectsForForm = array();
        foreach ($projects as $i => $project) {
            $projectsForForm[$project->getProjectId()] = $project->getProjectName();
        }
        return $projectsForForm;
    }
}
