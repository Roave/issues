<?php
class Default_Model_Mapper_Project extends Issues_Model_Mapper_DbAbstract
{
    protected $_name = 'project';

    public function getProjectById($projectId)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
                  ->where('project_id = ?', $projectId);
        $row = $db->fetchRow($sql);
        return ($row) ? new Default_Model_Project($row) : false;
    }

    public function getAllProjects()
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName());
        $rows = $db->fetchAll($sql);
        if (!$rows) return array();
        foreach ($rows as $i => $row) {
            $rows[$i] = new Default_Model_Project($row);
        }
        return $rows;
    }

    public function insert(Default_Model_Project $project)
    {
        $data = array(
            'name' => $project->getName(),
        );
        $db = $this->getWriteAdapter();
        $db->insert($this->getTableName(), $data);
        return $db->lastInsertId();
    }
}
