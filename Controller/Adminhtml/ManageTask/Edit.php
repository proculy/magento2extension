<?php
 
namespace Vvc\Task\Controller\Adminhtml\ManageTask;
 
use Magento\Backend\App\Action;
 
class Edit extends \Magento\Backend\App\Action
{ 
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    
    protected $_taskFactory;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Vvc\Task\Model\TaskFactory $taskFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_taskFactory = $taskFactory;
        parent::__construct($context);
    }
 
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Vvc_Task::save');
    }
 
    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Vvc_Task::managetask')
            ->addBreadcrumb(__('Task'), __('Task'))
            ->addBreadcrumb(__('Task Infomation'), __('Task Infomation'));
        return $resultPage;
    }
 
    /**
     * Edit Task
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('task_id');
        $model = $this->_taskFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This task no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
 
                return $resultRedirect->setPath('*/*/');
            }
        }
 
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Task') : __('New Task'),
            $id ? __('Edit Task') : __('New Task')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Task'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Task'));
 
        return $resultPage;
    }
}