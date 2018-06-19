<?php

namespace Vvc\Task\Ui\Component\Listing;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Customer\Model\Session;
/**
 * Data Provider for UI components based on Sellers
 *
 * @category Smile
 * @package  Smile\Seller
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * Seller collection
     *
     * @var \Smile\Seller\Model\ResourceModel\Seller\Collection
     */
    protected $collection;
    /**
     * @var \Magento\Ui\DataProvider\AddFieldToCollectionInterface[]
     */
    protected $addFieldStrategies;
    /**
     * @var \Magento\Ui\DataProvider\AddFilterToCollectionInterface[]
     */
    protected $addFilterStrategies;

    protected $_customerSession;

    protected $custSessionid;
    /**
     * Construct
     *
     * @param string                                                    $name                Component name
     * @param string                                                    $primaryFieldName    Primary field Name
     * @param string                                                    $requestFieldName    Request field name
     * @param CollectionFactory                                         $collectionFactory   The collection factory
     * @param \Magento\Ui\DataProvider\AddFieldToCollectionInterface[]  $addFieldStrategies  Add field Strategy
     * @param \Magento\Ui\DataProvider\AddFilterToCollectionInterface[] $addFilterStrategies Add filter Strategy
     * @param array                                                     $meta                Component Meta
     * @param array                                                     $data                Component extra data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Vvc\Task\Model\TaskFactory $collectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->addFieldStrategies  = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
        $this->_customerSession = $customerSession;
    }
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {

        $this->custSessionid = $this->_customerSession->getCustomer()->getId();
        //$this->custSessionid = 'phungquocvuongking@gmail.com';
        //echo($custSessionid); die;
        // if (!$this->getCollection()->isLoaded()) {
        //     $this->getCollection()->load();
        // }
        //$items = $this->getCollection()->toArray();
        $items = $this->collection->getCollection()->toArray();
        // var_dump([
        //     'items'        => array_values($items['items']),
        //     'totalRecords' => $this->collection->getCollection()->getSize()
        // ]); die;

        //return $items['items'];
        $items['items'] = array_filter($items['items'],function($obj){
            if(isset($obj)){
                if ($obj['assign_to'] == $this->custSessionid) {return true;}
                // foreach ($obj as $item) {
                //   //$name = $this->getData('name');
                //   //var_dump($item); die;
                //   if ($item['assign_to'] == 'phungquocvuongking@gmail.com') return true;
                // }
            }
            return false;
        });


        return [
            'items'        => array_values($items['items']),
            'totalRecords' => sizeof($items['items'])
        ];
    }
    /**
     * Add field to select
     *
     * @param string|array $field The field
     * @param string|null  $alias Alias for the field
     *
     * @return void
     */
    public function addField($field, $alias = null)
    {
        if (isset($this->addFieldStrategies[$field])) {
            $this->addFieldStrategies[$field]->addField($this->getCollection(), $field, $alias);
            return ;
        }
        parent::addField($field, $alias);
    }
    /**
     * {@inheritdoc}
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if (isset($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]
                ->addFilter(
                    $this->getCollection(),
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
            return;
        }
        parent::addFilter($filter);
    }
    public function addOrder($field,$direction)
    {
        //parent::addOrder($field,$direction);
    }
}