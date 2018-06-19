<?php
 
namespace Vvc\Task\Ui\Component\MassAction\Status;
 
use Magento\Framework\UrlInterface;
use Zend\Stdlib\JsonSerializable;
use Vvc\Task\Model\ResourceModel\Task\CollectionFactory;
 
/**
 * Class Options
 */
class StatusOptions implements JsonSerializable
{
    /**
     * @var array
     */
    protected $options;
 
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
 
    /**
     * Additional options params
     *
     * @var array
     */
    protected $data;
 
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
 
    /**
     * Base URL for subactions
     *
     * @var string
     */
    protected $urlPath;
 
    /**
     * Param name for subactions
     *
     * @var string
     */
    protected $paramName;
 
    /**
     * Additional params for subactions
     *
     * @var array
     */
    protected $additionalData = [];
 
    /**
    * @var \Vvc\Task\Model\Task\Source\Status
    */
    protected $_statusOptions;

    protected $_task;

    /**
     * Constructor
     *
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        UrlInterface $urlBuilder,
        \Vvc\Task\Model\Task\Source\Status $statusOptions,
        \Vvc\Task\Model\Task $task,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->data = $data;
        $this->urlBuilder = $urlBuilder;
        $this->_statusOptions=$statusOptions;
        $this->_task=$task;
    }
 
    /**
     * Get action options
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $i=0;
        if ($this->options === null) {
            $statusColl = $this->_task->getAvailableStatuses();
            if(!count($statusColl)){
                return $this->options;
            }
            //make a array of massaction
            foreach ($statusColl as $key => $status) {
                $options[$i]['value']=$key;
                $options[$i]['label']=$status;
                $i++;
            }
            $this->prepareData();
            foreach ($options as $optionCode) {
                $this->options[$optionCode['value']] = [
                    'type' => 'status_' . $optionCode['value'],
                    'label' => $optionCode['label'],
                ];
 
                if ($this->urlPath && $this->paramName) {
                    $this->options[$optionCode['value']]['url'] = $this->urlBuilder->getUrl(
                        $this->urlPath,
                        [$this->paramName => $optionCode['value']]
                    );
                }
 
                $this->options[$optionCode['value']] = array_merge_recursive(
                    $this->options[$optionCode['value']],
                    $this->additionalData
                );
            }
             
            // return the massaction data
            $this->options = array_values($this->options);
        }
        
        return $this->options;
    }
 
    /**
     * Prepare addition data for subactions
     *
     * @return void
     */
    protected function prepareData()
    {
          
        foreach ($this->data as $key => $value) {
            switch ($key) {
                case 'urlPath':
                    $this->urlPath = $value;
                    break;
                case 'paramName':
                    $this->paramName = $value;
                    break;
                default:
                    $this->additionalData[$key] = $value;
                    break;
            }
        }
    }
}