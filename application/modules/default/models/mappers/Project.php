<?php
class Default_Model_Mapper_Project extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'project';
    protected $_modelClass = 'Default_Model_Project';

    public function getProjectById($projectId)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
                  ->where('project_id = ?', $projectId);
        if (!Zend_Registry::get('Default_DiContainer')->getAclService()->isAllowed('project', 'view-all')) {
            $sql = $this->_addAclJoins($sql);
        }
        $row = $db->fetchRow($sql);
        return $this->_rowToModel($row);
    }

    public function getAllProjects()
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName());
        if (!Zend_Registry::get('Default_DiContainer')->getAclService()->isAllowed('project', 'view-all')) {
            $sql = $this->_addAclJoins($sql);
        }
        $rows = $db->fetchAll($sql);
        return $this->_rowsToModels($rows);
    }

    public function insert(Default_Model_Project $project)
    {
        $data = array(
            'name'      => $project->getName(),
            'private'   => $project->isPrivate() ? 1 : 0,
        );
        $db = $this->getWriteAdapter();
        $db->insert($this->getTableName(), $data);
        return $db->lastInsertId();
    }
}
