<?php
namespace Vvc\Task\Block;
class Headergird extends \Magento\Framework\View\Element\Template
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

	public function getActionName(){
		//return "insert";
		return $this->_urlBuilder->getUrl("helloworld/index/insert");
	}
	public function getViewTaskActionLink(){
		return $this->_urlBuilder->getUrl("task/index/tasklist");
	}
	public function getViewChartActionLink(){
		return $this->_urlBuilder->getUrl("task/index/display");
	}
}
?>