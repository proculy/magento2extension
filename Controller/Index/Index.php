<?php
namespace Vvc\Task\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;

	protected $_taskFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Vvc\Task\Model\TaskFactory $taskFactory
		)
	{
		$this->_pageFactory = $pageFactory;
		$this->_taskFactory = $taskFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
		$task = $this->_taskFactory->create();
		$collection = $task->getCollection();
		foreach($collection as $item){
			echo "<pre>";
			print_r($item->getData());
			echo "</pre>";
		}
		exit();
		return $this->_pageFactory->create();
	}
}
?>