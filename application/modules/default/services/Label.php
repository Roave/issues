<?php
class Default_Service_Label extends Issues_ServiceAbstract
{
    public function getLabelById($id)
    {
        return $this->_mapper->getLabelById($id);
    }
}
