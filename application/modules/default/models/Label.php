<?php

class Default_Model_Label extends Issues_Model_Abstract {
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
}
