<?php
namespace Vvc\Task\Controller\Index;

class Display extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	Protected $customerSession;
	
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Customer\Model\Session $customerSession
		)
	{
		$this->_pageFactory = $pageFactory;
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