<?php
class Issues_Form_Element_MilestoneSelect extends Zend_Form_Element_Multiselect
{
    public function init()
    {
        $milestones = Zend_Registry::get('Default_DiContainer')->getMilestoneService()
            ->getMilestonesForSelect();
        $this->setMultiOptions($milestones);
    }
}
