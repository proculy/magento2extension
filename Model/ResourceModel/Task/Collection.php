<?php
namespace Vvc\Task\Model\ResourceModel\Task;
use Zend\Db\Sql\Sql;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'task_id';
    protected $_eventPrefix = 'customer_task_collection';
    protected $_eventObject = 'task_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Vvc\Task\Model\Task', 'Vvc\Task\Model\ResourceModel\Task');
        $this->_map['fields']['entity_id'] = 'main_table.entity_id';
        $this->_idFieldName = 'task_id';
    }

    public function getAllCustomerId() {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $select = $connection->select()
            ->from(
                ['ce' => 'customer_entity'],
                ['firstname', 'lastname', 'entity_id']
            );
        return $data = $connection->fetchAll($select);
    }

    public function getAllCustomerEmail() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $select = $connection->select()
            ->from(
                ['ce' => 'customer_entity'],
                ['firstname', 'lastname', 'email']
            );
        return $data = $connection->fetchAll($select);
    }

    public function getEmailByIdCustomer($id){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $select = $connection->select()
            ->from(
                ['ce' => 'customer_entity'],
                ['entity_id', 'email','firstname','lastname']
            )->where('ce.entity_id=?',$id);

        return $data = $connection->fetchRow($select);
    }

    public function filterOrder($id){
        $this->customer_task_table = "customer_task";
        $this->customer_entity_table = "customer_entity";
        $this->admin_user_table = "admin_user";
        $this->getSelect()
            ->joinLeft(array('cus' => $this->customer_entity_table), 'main_table.assign_to = cus.entity_id')
            ->joinLeft(array('adcre' => $this->admin_user_table), 'main_table.user_created = adcre.user_id')
            ->joinLeft(array('adupd' => $this->admin_user_table), 'main_table.user_updated = adupd.user_id')
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(["main_table.*","cus.firstname","cus.lastname","adcre.firstname as adcrefirstname",
                "adcre.lastname as adcrelastname","adupd.firstname as adupdfirstname",
            "adupd.lastname as adupdlastname"]);
        $this->getSelect()->where("task_id =".$id);
    }

    public function filterOrderNoWhere(){
        // $this->getSelect()->join(array('ce' =>'customer_entity'),  'main_table.assign_to= ce.email');
        // $this->getSelect()->where("task_id=".$id);

        $this->customer_task_table = "customer_task";
        $this->customer_entity_table = "customer_entity";
        $this->admin_user_table = "admin_user";
        $this->getSelect()
            ->joinLeft(array('cus' => $this->customer_entity_table), 'main_table.assign_to = cus.entity_id')
            ->joinLeft(array('adcre' => $this->admin_user_table), 'main_table.user_created = adcre.user_id')
            ->joinLeft(array('adupd' => $this->admin_user_table), 'main_table.user_updated = adupd.user_id')
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(["main_table.*","cus.firstname","cus.lastname","adcre.firstname as adcrefirstname",
                "adcre.lastname as adcrelastname","adupd.firstname as adupdfirstname",
            "adupd.lastname as adupdlastname"]);
        //$this->getSelect()->where("task_id =".$id);
    }

    public function filterOrderWhereMyId($entity_id){
        $this->customer_task_table = "customer_task";
        $this->customer_entity_table = "customer_entity";
        $this->admin_user_table = "admin_user";
        $this->getSelect()
            ->join(array('cus' => $this->customer_entity_table), 'main_table.assign_to = cus.entity_id')
            ->joinLeft(array('adcre' => $this->admin_user_table), 'main_table.user_created = adcre.user_id')
            ->joinLeft(array('adupd' => $this->admin_user_table), 'main_table.user_updated = adupd.user_id')
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(["main_table.*","cus.firstname","cus.lastname","adcre.firstname as adcrefirstname",
                "adcre.lastname as adcrelastname","adupd.firstname as adupdfirstname",
            "adupd.lastname as adupdlastname"]);
        $this->getSelect()->where("cus.entity_id =".$entity_id);
    }

    public function fillerCollectionChart(){
        $this->customer_task_table = "customer_task";
        $this->customer_entity_table = "customer_entity";
        $this->getSelect()
            ->joinLeft(array('cus' => $this->customer_entity_table), 'main_table.assign_to = cus.entity_id')
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(["main_table.*","CONCAT(cus.firstname,' ',cus.lastname) as assignname"])
            ->order(["main_table.start_date", "main_table.end_date"]);
    }
}
?>