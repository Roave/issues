<?php
class Issues_Form_Element_LabelSelect extends Zend_Form_Element_Multiselect
{
    public function init()
    {
        $labels = Zend_Registry::get('Default_DiContainer')->getLabelService()
            ->getLabelsForSelect();
        $this->setMultiOptions($labels);
    }
}
