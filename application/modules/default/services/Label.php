<?php
class Default_Service_Label extends Issues_ServiceAbstract
{
    protected $_createForm;

    public function getLabelById($id)
    {
        return $this->_mapper->getLabelById($id);
    }

    public function getLabelsByIssue($issue)
    {
        return $this->_mapper->getLabelsByIssue($issue);
    }

    public function getAllLabels()
    {
        return $this->_mapper->getAllLabels();
    }

    public function getLabelsForSelect(array $labels = null)
    {
        if ($labels === null) {
            $labels = $this->getAllLabels();
        }

        $result = array();
        foreach ($labels as $l) {
            $result[$l->getLabelId()] = $l->getText();
        }

        return $result;
    }

    public function createLabel($text, $color)
    {
        $acl = Zend_Registry::get('Default_DiContainer')->getAclService();
        if (!$acl->isAllowed('user', 'label', 'create')) {
            return false;
        }

        $label = new Default_Model_Label();
        $label->setText($text)->setColor($color);
        $this->_mapper->insert($label);
        return true;
    }

    public function getCreateForm()
    {
        if (null === $this->_createForm) {
            $this->_createForm = new Default_Form_Label_Create();
        }
        return $this->_createForm;
    }
}
