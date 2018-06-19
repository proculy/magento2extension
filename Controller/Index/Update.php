<?php
namespace Vvc\Task\Controller\Index;

class Update extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;

	protected $_taskFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Vvc\Task\Model\TaskFactory $taskFactory
		)
	{
		$this->_pageFactory = $pageFactory;
		$this->_taskFactory = $taskFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
		$Data = $this->getRequest()->getParams();

		$task_id = $Data["task_id"];
		$status = $Data["status"];
		$progress = $Data["progress"];
		$description = $Data["description"];
	
		$DataUpdate = [	'status' => $status,
						'progress' => $progress,
						'description' => $description
					];

		$model = $this->_taskFactory->create();

		$model->load($task_id)->addData($DataUpdate);


		try {
			$updateData = $model->setId($task_id)->save();
			} catch (\Exception $e) {
				echo $e->getMessage();
			} 

		if($updateData){
            $this->messageManager->addSuccess( __('Update Record Successfully !') );
        }
        return $this->_redirect("task/index/tasklist");
	}
}
?>