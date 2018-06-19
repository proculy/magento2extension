<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Vvc\Task\Controller\Adminhtml\ManageTask;
 
use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action;
 
class Delete extends \Magento\Backend\App\Action
{
    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(\Magento\Backend\App\Action\Context $context)
    {
        parent::__construct($context);
    }
 
    /**
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('task_id');
        if ($id) {
            try {
                /** @var \Vvc\Task\Model\Task $model */
                $model = $this->_objectManager->create('Vvc\Task\Model\Task');
                $model->load($id);
                $model->delete();
                $this->_redirect('vvc_task/*/');
                $this->messageManager->addSuccess(__('Delete Task successfull.'));
                return;
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete this task right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('vvc_task/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a rule to delete.'));
        $this->_redirect('vvc_task/*/');
    }
}