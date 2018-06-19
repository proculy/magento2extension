<?php
namespace Vvc\Task\Controller\Adminhtml;

use \Magento\Backend\Model\View\Result\Redirect;
use \Magento\Eav\Model\Entity\Collection\AbstractCollection;

abstract class AbstractMassAction extends \Magento\Backend\App\Action
{
    protected $filter;
    protected $collectionFactory;
    protected $resultFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Vvc\Task\Model\ResourceModel\Task\CollectionFactory $collectionFactory,
        \Magento\Framework\Controller\ResultFactory $resultFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory=$collectionFactory;
        $this->resultFactory=$resultFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            return $this->massAction($collection);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath($this->redirectUrl);
        }
    }
 
    /**
     * Check the permission to Manage Customers
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Vvc_task::managetask');
    }
 
    /**
     * Return component referer url
     * TODO: Technical dept referer url should be implement as a part of Action configuration in in appropriate way
     *
     * @return null|string
     */
    protected function getComponentRefererUrl()
    {
        return $this->filter->getComponentRefererUrl()?: $this->redirectUrl;
    }
 
    /**
     * Execute action to collection items
     *
     * @param AbstractCollection $collection
     * @return ResponseInterface|ResultInterface
     */
    abstract protected function massAction(AbstractCollection $collection);
}