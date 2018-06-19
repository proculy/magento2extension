<?php
namespace Vvc\Task\Model;
class Task extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const STATUS_NOTSTART = 0;
    const STATUS_START = 1;
    const STATUS_INPROGRESS = 2;
    const STATUS_END = 3;

    const PRIORITY_HIGH = 0;
    const PRIORITY_NORMAL = 1;
    const PRIORITY_LOW = 2;

	const CACHE_TAG = 'customer_task';

	protected $_cacheTag = 'customer_task';

	protected $_eventPrefix = 'customer_task';

    /**
     * @param \Magento\Framework\Model\Context $context
    * @param \Magento\Framework\Registry $registry
    * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
    * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
    * @param array $data
    */
    function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

	protected function _construct()
	{
		$this->_init('Vvc\Task\Model\ResourceModel\Task');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
    }

    /**
     * Prepare grid's statuses.
    * Available event staff_grid_get_available_statuses to customize statuses.
    *
    * @return array
    */
    public function getAvailableStatuses()
    {
        return [self::STATUS_NOTSTART => __('Not Start'),
         self::STATUS_START => __('Start'),
         self::STATUS_INPROGRESS => __('In Progress'),
         self::STATUS_END => __('End')];
    }

    public function getAvailablePriority()
    {
        return [self::PRIORITY_HIGH => __('High'),
         self::PRIORITY_NORMAL => __('Normal'),
         self::PRIORITY_LOW => __('Low')];
    }
}