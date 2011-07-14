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
        return $this->_mapper->getAllLabels(true);
    }

    public function getLabelsForSelect(array $labels = null)
    {
        if ($labels === null) {
            $labels = $this->getAllLabels(true);
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
        if (!$acl->isAllowed('label', 'create')) {
            return false;
        }

        $label = new Default_Model_Label();
        $label->setText($text)->setColor($color);
        $this->_mapper->insert($label);
        return true;
    }

    public function getCreateForm()
    {
        $acl = Zend_Registry::get('Default_DiContainer')->getAclService();
        if (!$acl->isAllowed('label', 'create')) {
            return false;
        }

        if (null === $this->_createForm) {
            $this->_createForm = new Default_Form_Label_Create();
        }
        return $this->_createForm;
    }

    public function getLabelDetect($label)
    {
        if (!($label instanceof Default_Model_Label)) {
            if (is_numeric($label)) {
                $thisLabel = Zend_Registry::get('Default_DiContainer')->getLabelService()->getLabelById($label);
                $label = ($thisLabel) ? $thisLabel : NULL;
            } elseif (is_array($label)) {
                foreach ($label as $i => $thisLabel) {
                    $thisLabel = $this->getLabelDetect($thisLabel);
                    if (!$thisLabel) {
                        unset($label[$i]);
                        continue;
                    }
                    $label[$i] = $thisLabel;
                }
            } elseif(is_string($label) && trim($label) && (strstr($label,' ') !== false || is_numeric($label))) {
                $label = $this->getLabelDetect(explode(' ',$label));
            }
        }
        return $label;
    }
}
