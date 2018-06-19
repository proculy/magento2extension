<?php
namespace Vvc\Task\Model\Task\Source;
 
class Priority implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
    * @var \Vvc\Task\Model\Task
    */
    protected $_grid;
    
    /**
    * Constructor
    *
    * @param \Vvc\Task\Model\Task $grid
    */
    public function __construct(\Vvc\Task\Model\Task $grid)
    {
        $this->_grid = $grid;
    }
    
    /**
    * Get options
    *
    * @return array
    */
    public function toOptionArray($isBlock = false)
    {
        $availableOptions = $this->_grid->getAvailablePriority();
        $options = array();
        
        foreach ($availableOptions as $key => $value) {
            if($isBlock){
                $options[$key] = $value;
            } else {
                $options[] = [
                'label' => $value,
                'value' => $key,
                ];
            }
        }
        
        return $options;
    }
}