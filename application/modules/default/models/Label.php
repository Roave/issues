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
    protected $_count;

    /**
     * _private 
     * 
     * @var boolean
     */
    protected $_private;

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
        return $this->_count;
    }
 
    /**
     * Set count.
     *
     * @param int $count the value to be set
     */
    public function setCount($count)
    {
        $this->_count = $count;
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

 
    /**
     * Get private.
     *
     * @return private
     */
    public function getPrivate()
    {
        return $this->_private;
    }
 
    /**
     * Set private.
     *
     * @param $private the value to be set
     */
    public function setPrivate($private)
    {
        $this->_private = $private;
        return $this;
    }

    /**
     * Get private
     *
     * @return private
     */
    public function isPrivate()
    {
        return $this->getPrivate();
    }
}
