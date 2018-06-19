<?php
namespace Vvc\Task\Block;
class Tasklist extends \Magento\Framework\View\Element\Template
{
	protected $_taskFactory;
	protected $request;
	protected $_customerSession;
	protected $_helper;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Vvc\Task\Model\TaskFactory $taskFactory,
		\Magento\Framework\App\Request\Http $request,
		\Magento\Customer\Model\Session $customerSession,
		\Vvc\Task\Helper\Data $helper
	)
	{
		$this->_taskFactory = $taskFactory;
		$this->request = $request;
		$this->_customerSession = $customerSession;
		$this->_helper = $helper;
		parent::__construct($context);
	}

	public function sayHello()
	{
		return __('My Task List');
	}

	public function getTaskCollection(){
		$custSessionid = $this->_customerSession->getCustomer()->getId();
		// $collection = $this->_taskFactory->create()->getCollection();
		// $collection->filterOrderWhereMyId($custSessionid);
		// return $collection;
		return $this->_helper->getListTaskByCusId($custSessionid);
	}

	public function getActionName(){
		//return "insert";
		return $this->_urlBuilder->getUrl("helloworld/index/insert");
	}
	public function getUpdateActionLink(){
		return $this->_urlBuilder->getUrl("task/index/updatetask");
	}
	public function getViewAllTaskActionLink(){
		return $this->_urlBuilder->getUrl("task/index/alltasklist");
	}
	public function getViewChartActionLink(){
		return $this->_urlBuilder->getUrl("task/index/display");
	}
	public function getStatusName($idStatus) {
		return $this->_helper->getStatusName($idStatus);
	}
	public function getPriorityName($idPriority) {
		return $this->_helper->getPriorityName($idPriority);
	}
}
?>