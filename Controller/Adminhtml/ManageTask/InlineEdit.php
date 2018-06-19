<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
 
namespace Vvc\Task\Controller\Adminhtml\ManageTask;
 
use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
 
class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;
 
    /**
     * @var \Magento\Backend\Helper\Js
     */
    protected $jsHelper;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    protected $_taskFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Backend\Helper\Js $jsHelper
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Vvc\Task\Model\TaskFactory $taskFactory,
        \Magento\Backend\Helper\Js $jsHelper
    )
    {
        $this->cacheTypeList = $cacheTypeList;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_taskFactory = $taskFactory;
        parent::__construct($context);
        $this->jsHelper = $jsHelper;
    }
 
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Vvc_Task::save');
    }
 
    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        $data = $this->getRequest()->getParam('items', []);

        if (!($this->getRequest()->getParam('isAjax') && count($data))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        foreach (array_keys($data) as $id) {
            /** @var \Vvc\Task\Model\Task $model */
            $model = $this->_taskFactory->create();

            $model->load($id);
 
            $model->setData($data[$id]);
 
            $this->_eventManager->dispatch(
                'task_managetask_prepare_save',
                ['task' => $model, 'request' => $this->getRequest()]
            );
 
            try {
                $model->save();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Task.'));
            }
        }
        return $resultJson->setData([
            'messages' => $this->getErrorMessages(),
            'error' => $this->isErrorExists()
        ]);
    }
    /**
     * Get array with errors
     *
     * @return array
     */
    protected function getErrorMessages()
    {
        $messages = [];
        foreach ($this->getMessageManager()->getMessages()->getItems() as $error) {
            $messages[] = $error->getText();
        }
        return $messages;
    }

    /**
     * Check if errors exists
     *
     * @return bool
     */
    protected function isErrorExists()
    {
        return (bool)$this->getMessageManager()->getMessages(true)->getCount();
    }
}