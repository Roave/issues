<?php
class Default_Service_Comment extends Issues_ServiceAbstract
{
    public function getCommentsByIssue($issue)
    {
        return $this->_mapper->getCommentsByIssue($issue);
    }
}
