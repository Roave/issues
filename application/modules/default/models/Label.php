<?php
class Default_Model_Label extends Issues_Model_Abstract implements Zend_Acl_Resource_Interface
{
    /**
     * _labelId 
     * 
     * @var int
     */
    protected $_labelId;

    /**
     * Label text
     * 
     * @var string
     */
    protected $_text;

    /**
     * Label color 
     * 
     * @var string
     */
    protected $_color;

    /**
     * Number of issues with this label
     *
     * @var int
     */
    protected $_issueCount;

    /**
     * Get labelId.
     *
     * @return int labelId
     */
    public function getLabelId()
    {
        return $this->_labelId;
    }
 
    /**
     * Set labelId.
     *
     * @param int $labelId the value to be set
     *
     * @return Default_Model_Label
     */
    public function setLabelId($labelId)
    {
        $this->_labelId = (int) $labelId;
        return $this;
    }
 
    /**
     * Get text.
     *
     * @return string text
     */
    public function getText()
    {
        return $this->_text;
    }
 
    /**
     * Set text.
     *
     * @param string $text the value to be set
     *
     * @return Default_Model_Label
     */
    public function setText($text)
    {
        $this->_text = $text;
        return $this;
    }
 
    /**
     * Get color.
     *
     * @return string color
     */
    public function getColor()
    {
        return $this->_color;
    }
 
    /**
     * Set color.
     *
     * @param string $color the value to be set
     *
     * @return Default_Model_Label
     */
    public function setColor($color)
    {
        $this->_color = $color;
        return $this;
    }
 
    /**
     * Get count.
     *
     * @return int count
     */
    public function getIssueCount()
    {
        if (!isset($this->_issueCount)) {
            $this->_issueCount = Zend_Registry::get('Default_DiContainer')
                ->getIssueService()
                ->countIssuesByLabel($this);
        }
        return $this->_issueCount;
    }
 
    /**
     * Set count.
     *
     * @param int $count the value to be set
     */
    public function setCount($issueCount)
    {
        $this->_issueCount = $issueCount;
        return $this;
    }

    /**
     * getResourceId 
     * 
     * @return string
     */
    public function getResourceId()
    {
        return 'label-' . $this->getLabelId();
    }

}
