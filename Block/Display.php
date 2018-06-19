<?php
namespace Vvc\Task\Block;

class Display extends \Magento\Framework\View\Element\Template
{
    protected $taskFactory;
    protected $helper;
    protected $storeManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Vvc\Task\Model\TaskFactory $taskFactory,
        \Vvc\Task\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->taskFactory = $taskFactory;
        $this->helper = $helper;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function initTask()
    {
        return __('This is Task List');
    }

    public function getCustomerTaskCollection(){
        $taskDetailUrl = $this->storeManager->getStore()->getBaseUrl();
        $collection = $this->helper->getTaskCollection()->getData();

        foreach ($collection as $key => $value) {
            $collection[$key]['taskDetailUrl'] = $taskDetailUrl . 'task/index/updatetask/id/' . $value['task_id'];
        }

        return $collection;
    }

    public function getViewTaskActionLink(){
        return $this->_urlBuilder->getUrl("task/index/tasklist");
    }

    public function getViewChartActionLink(){
        return $this->_urlBuilder->getUrl("task/index/display");
    }
}