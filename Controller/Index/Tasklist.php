<?php
namespace Vvc\Task\Controller\Index;

class Tasklist extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $_taskFactory;
	Protected $customerSession;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Vvc\Task\Model\TaskFactory $taskFactory,
		\Magento\Customer\Model\Session $customerSession
		)
	{
		$this->_pageFactory = $pageFactory;
		$this->_taskFactory = $taskFactory;
		$this->customerSession=$customerSession;
		return parent::__construct($context);
	}

	public function execute()
	{
		try{
			if($this->customerSession->isLoggedin()) {
				return $this->_pageFactory->create();
			} else {
				$this->customerSession->setAfterAuthUrl($this->_url->getCurrentUrl());
                $this->customerSession->authenticate();
			}


		}catch(Exception $e){

        }
		
	}
}
?>