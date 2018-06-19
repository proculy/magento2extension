<?php
namespace Vvc\Task\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	protected $_taskFactory;
	public function __construct(
		\Vvc\Task\Model\TaskFactory $taskFactory
	)
	{
		$this->_taskFactory = $taskFactory;
	}
    public function getStatusName($idStatus){
        switch ($idStatus) {
			case '0':
				return "Not start";
			case '1':
				return "Start";
			case '2':
				return "In Progress";
			case '3':
				return "End";
			default:
				return "";
		}
    }

    public function getPriorityName($idPriority){
    	switch ($idPriority) {
			case '0':
				return "Hight";
			case '1':
				return "Normal";
			case '2':
				return "Low";
			default:
				return "";
		}
    }

    public function getListTaskByCusId($custSessionid) {
    	$collection = $this->_taskFactory->create()->getCollection();
		$collection->filterOrderWhereMyId($custSessionid);
		return $collection;
    }

    public function getListTaskAll() {
    	$collection = $this->_taskFactory->create()->getCollection();
		$collection->filterOrderNoWhere();
    	return $collection;
    }

    public function getTaskCollection() {
    	// $collection = $this->_taskFactory->create()->getCollection();
    	// return $collection;
    	$collection = $this->_taskFactory->create()->getCollection();
    	$collection->fillerCollectionChart();
    	return $collection;
    }

	public function getAllCustomerEmail(){
		$collection = $this->_taskFactory->create()->getCollection();
		return $collection->getAllCustomerEmail();
	}

	public function getAllCustomerId(){
		$collection = $this->_taskFactory->create()->getCollection();
		return $collection->getAllCustomerId();
	}

	public function getEmailByIdCustomer($id){
		$collection = $this->_taskFactory->create()->getCollection();
		return $collection->getEmailByIdCustomer($id);
	}
}
?>