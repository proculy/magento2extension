<?php
namespace Vvc\Task\Model\Task\Source;
 
class AssignTo implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
    * @var \Vvc\Task\Model\Task
    */
    protected $_grid;
    protected $_helper;
    /**
    * Constructor
    *
    * @param \Vvc\Task\Model\Task $grid
    */
    public function __construct(\Vvc\Task\Model\Task $grid,
                                \Vvc\Task\Helper\Data $helper)
    {
        $this->_grid = $grid;
        $this->_helper=$helper;
    }

    /**
    * Get options
    *
    * @return array
    */
    public function toOptionArray($isBlock = false)
    {
        $availableOptions = $this->_helper->getAllCustomerId();
        $options = array();

        foreach ($availableOptions as $row) {
            if($isBlock){
                $options[$row['entity_id']] = $row['firstname'] . ' ' . $row['lastname'];
            } else{
                $options[] = [
                'label' => $row['firstname'] . ' ' . $row['lastname'],
                'value' => $row['entity_id'],
                ];
            }
        }

        return $options;
    }
}