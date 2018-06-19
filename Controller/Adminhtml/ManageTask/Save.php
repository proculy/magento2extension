<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
 
namespace Vvc\Task\Controller\Adminhtml\ManageTask;
 
use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
 
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;
 
    /**
     * @var \Magento\Backend\Helper\Js
     */
    protected $jsHelper;

    protected $_taskFactory;
 
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Backend\Helper\Js $jsHelper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Backend\Helper\Js $jsHelper,
        \Vvc\Task\Model\TaskFactory $taskFactory
    )
    {
        $this->cacheTypeList = $cacheTypeList;
        parent::__construct($context);
        $this->jsHelper = $jsHelper;
        $this->_taskFactory = $taskFactory;
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
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_taskFactory->create();

            $id = $this->getRequest()->getParam('task_id');

            if (strlen($id) > 0) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();

                if (!isset($id)) {
                    $this->_eventManager->dispatch('vvc_task_send_email', ['taskInfo' => $model->getData()]);
                }

                $this->cacheTypeList->invalidate('full_page');
                $this->messageManager->addSuccess(__('You saved this Task.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['task_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Task.'));
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath('*/*/edit', ['task_id' => $this->getRequest()->getParam('task_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}