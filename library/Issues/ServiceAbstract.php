<?php
abstract class Issues_ServiceAbstract
{
    protected $_mapper;

    public function __construct(Issues_Model_Mapper_DbAbstract $mapper = null)
    {
        $this->_mapper = $mapper;
    }
}
