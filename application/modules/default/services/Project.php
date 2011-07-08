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
        $acl = Zend_Registry::get('Default_DiContainer')->getAclService();
        if (!$acl->isAllowed('project', 'create')) {
            return false;
        }

        $permissions = $form->getValue('permissions');

        $project = new Default_Model_Project();
        $project->setName($form->getValue('project_name'))
            ->setPrivate($permissions['private'] ? true : false);
        $return = $this->_mapper->insert($project);

        if ($permissions['private']) {
            Zend_Registry::get('Default_DiContainer')->getAclService()
                ->addResourceRecord($permissions['roles'], 'project', $return);
        }

        return $return;
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
            $projectsForForm[$project->getProjectId()] = $project->getName();
        }
        return $projectsForForm;
    }
}
