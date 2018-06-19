<?php
namespace Vvc\Task\Block;
class Taskupdate extends \Magento\Framework\View\Element\Template
{
	protected $_taskFactory;
	protected $request;
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Vvc\Task\Model\TaskFactory $taskFactory,
		\Magento\Framework\App\Request\Http $request
	)
	{
		$this->_taskFactory = $taskFactory;
		$this->request = $request;
		parent::__construct($context);
	}

	public function sayHello()	{
		return __('Update Task');
	}

	public function getActionName()	{
		return $this->_urlBuilder->getUrl("task/index/update");
	}

	public function getTaskCollectionbyId() {
		$task_id = $this->request->getParam('id');
		$collection = $this->_taskFactory->create()->getCollection();
		//$result = $collection->filterOrder($task_id);
		$collection->filterOrder($task_id);
		//return null;
		return $collection->getFirstItem();
			// foreach ($collection as $item) {
			//     echo 'abc';
			// }
		// $record = $infoColl->addFieldToFilter('task_id', $task_id);
        // var_dump($record); exit();
		//return $result;

	}

}