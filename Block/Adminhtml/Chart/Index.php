<?php
namespace Vvc\Task\Block\Adminhtml\Chart;

use Magento\Framework\UrlInterface;

class Index extends  \Magento\Backend\Block\Template
{
    const GRID_URL_PATH_EDIT = 'vvc_task/managetask/edit/task_id/';

    protected $helper;
    protected $urlBuilder;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Vvc\Task\Helper\Data $helper,
        UrlInterface $urlBuilder
    )
    {
        parent::__construct($context);
        $this->helper = $helper;
        $this->urlBuilder = $urlBuilder;
    }

    public function getCustomerTaskCollection(){
        // $taskDetailUrl = $this->storeManager->getStore()->getBaseUrl();
        $collection = $this->helper->getTaskCollection()->getData();

        foreach ($collection as $key => $value) {
            $taskDetailUrl = $this->urlBuilder->getUrl(self::GRID_URL_PATH_EDIT, ['task_id' => $value['task_id']]);
            $collection[$key]['taskDetailUrl'] = $taskDetailUrl;
        }

        return $collection;
    }
}
?>