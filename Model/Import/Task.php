<?php
namespace Vvc\Task\Model\Import;

use Vvc\Task\Model\Import\Task\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\ImportExport\Model\Import;
use \DateTime;

class Task extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{
    const TASK_ID = 'task_id';
    const TASK_NAME = 'task_name';
    const TASK_CONTENT = 'task_content';
    const START_DATE = 'start_date';
    const END_DATE = 'end_date';
    const STATUS = 'status';
    const ASSIGN_TO = 'assign_to';
    const PROGRESS = 'progress';
    const DESCRIPTION = 'description';
    const PRIORITY = 'priority';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const USER_CREATED = 'user_created';
    const USER_UPDATED = 'user_updated';
    const TABLE_ENTITY = 'customer_task';
    
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_ID_IS_EMPTY => 'task_id is empty',
        ValidatorInterface::ERROR_TASK_NAME_IS_EMPTY => 'task_name is empty',
        ValidatorInterface::ERROR_START_DATE_IS_EMPTY => 'start_date is empty',
        ValidatorInterface::ERROR_INVALID_START_DATE => 'start_date value is invalid',
        ValidatorInterface::ERROR_END_DATE_IS_EMPTY => 'end_date is empty',
        ValidatorInterface::ERROR_INVALID_END_DATE => 'end_date value is invalid',
        ValidatorInterface::ERROR_INVALID_START_END_DATE => 'start_date is greater than end_date',
        ValidatorInterface::ERROR_STATUS_IS_EMPTY => 'status is empty',
        ValidatorInterface::ERROR_INVALID_STATUS => 'status is invalid',
        ValidatorInterface::ERROR_ASSIGN_TO_IS_EMPTY => 'assign_to is empty',
        ValidatorInterface::ERROR_INVALID_ASSIGN_TO => 'assign_to is invalid',
        ValidatorInterface::ERROR_PROGRESS_IS_NAN => 'progress is not a number',
        ValidatorInterface::ERROR_PROGRESS_IS_OUTOFRANGE => 'progress is out of range(0~100)',
        ValidatorInterface::ERROR_PRIORITY_IS_EMPTY => 'priority is empty',
        ValidatorInterface::ERROR_INVALID_PRIORITY => 'priority is invalid',
    ];

    /**
     * Permanent entity columns.
     *
     * @var string[]
     */
    protected $_permanentAttributes = [self::TASK_ID];

    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;
    
    /**
     * Valid column names
     *
     * @array
     */
    protected $validColumnNames = [
        self::TASK_ID,
        self::TASK_NAME,
        self::TASK_CONTENT,
        self::START_DATE,
        self::END_DATE,
        self::STATUS,
        self::ASSIGN_TO,
        self::PROGRESS,
        self::DESCRIPTION,
        self::PRIORITY,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::USER_CREATED,
        self::USER_UPDATED,
    ];

    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;
    
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_connection;

    protected $validators = [];
    protected $resource;
    protected $groupFactory;
    protected $statusOptions;
    protected $priorityOptions;
    protected $assignToOptions;

    /**
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator,
        \Magento\Customer\Model\GroupFactory $groupFactory,
        \Vvc\Task\Model\Task\Source\Status $statusOptions,
        \Vvc\Task\Model\Task\Source\Priority $priorityOptions,
        \Vvc\Task\Model\Task\Source\AssignTo $assignToOptions)
    {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
        $this->groupFactory = $groupFactory;
        $this->statusOptions = $statusOptions->toOptionArray(true);
        $this->priorityOptions = $priorityOptions->toOptionArray(true);
        $this->assignToOptions = $assignToOptions->toOptionArray(true);

        /**
         * replace error code with error message
         */
        foreach ($this->_messageTemplates as $errorCode => $message) {
            $this->getErrorAggregator()->addErrorMessageTemplate($errorCode, $message);
        }
    }

    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'manage_task';
    }

    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {
        $title = false;
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }
        $this->_validatedRows[$rowNum] = true;
        // BEHAVIOR_DELETE and BEHAVIOR_REPLACE use specific validation logic
        if (Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            if (!isset($rowData[self::TASK_ID]) || strlen(trim($rowData[self::TASK_ID])) === 0) {
                $this->addRowError(ValidatorInterface::ERROR_ID_IS_EMPTY, $rowNum);
                return false;
            }
            return true;
        }

        if (Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            if (!isset($rowData[self::TASK_ID]) || strlen(trim($rowData[self::TASK_ID])) === 0) {
                $this->addRowError(ValidatorInterface::ERROR_ID_IS_EMPTY, $rowNum);
                return false;
            }
        }

        //check task name is blank
        if (!isset($rowData[self::TASK_NAME]) || strlen(trim($rowData[self::TASK_NAME])) === 0) {
            $this->addRowError(ValidatorInterface::ERROR_TASK_NAME_IS_EMPTY, $rowNum);
            return false;
        }

        //check start date is blank
        if (!isset($rowData[self::START_DATE]) || strlen(trim($rowData[self::START_DATE])) === 0) {
            $this->addRowError(ValidatorInterface::ERROR_START_DATE_IS_EMPTY, $rowNum);
            return false;
        //check start date is valid
        } elseif (!$this->validateDate($rowData[self::START_DATE],"Y-m-d")) {
            $this->addRowError(ValidatorInterface::ERROR_INVALID_START_DATE, $rowNum);
            return false;
        }
        
        //check end date is blank
        if (!isset($rowData[self::END_DATE]) || strlen(trim($rowData[self::END_DATE])) === 0) {
            $this->addRowError(ValidatorInterface::ERROR_END_DATE_IS_EMPTY, $rowNum);
            return false;
        //check end date is valid
        } elseif (!$this->validateDate($rowData[self::END_DATE],"Y-m-d")) {
            $this->addRowError(ValidatorInterface::ERROR_INVALID_END_DATE, $rowNum);
            return false;
        }

        //check end date is greater than start date
        if ($this->compareDate($rowData[self::START_DATE], $rowData[self::END_DATE]) === 1) {
            $this->addRowError(ValidatorInterface::ERROR_INVALID_START_END_DATE, $rowNum);
            return false;
        }

        //check status is blank
        if (!isset($rowData[self::STATUS]) || strlen(trim($rowData[self::STATUS])) === 0) {
            $this->addRowError(ValidatorInterface::ERROR_STATUS_IS_EMPTY, $rowNum);
            return false;
        //check status is valid
        } elseif (!$this->isArrayKeyContainsWord($this->statusOptions, $rowData[self::STATUS])){
            $this->addRowError(ValidatorInterface::ERROR_INVALID_STATUS, $rowNum);
            return false;
        }

        //check assign to is blank
        if (!isset($rowData[self::ASSIGN_TO]) || strlen(trim($rowData[self::ASSIGN_TO])) === 0) {
            $this->addRowError(ValidatorInterface::ERROR_ASSIGN_TO_IS_EMPTY, $rowNum);
            return false;
        //check assign to is valid
        } elseif (!$this->isArrayKeyContainsWord($this->assignToOptions, $rowData[self::ASSIGN_TO])){
            $this->addRowError(ValidatorInterface::ERROR_INVALID_ASSIGN_TO, $rowNum);
            return false;
        }

        //check progress is numeric
        if (!is_numeric($rowData[self::PROGRESS])) {
            $this->addRowError(ValidatorInterface::ERROR_PROGRESS_IS_NAN, $rowNum);
            return false;
        //check progress range from 0 to 100
        } elseif ($rowData[self::PROGRESS]<0 || $rowData[self::PROGRESS]>100) {
            $this->addRowError(ValidatorInterface::ERROR_PROGRESS_IS_OUTOFRANGE, $rowNum);
            return false;
        }

        //check priority to is blank
        if (!isset($rowData[self::PRIORITY]) || strlen(trim($rowData[self::PRIORITY])) === 0) {
            $this->addRowError(ValidatorInterface::ERROR_PRIORITY_IS_EMPTY, $rowNum);
            return false;
        //check priority is valid
        } elseif (!$this->isArrayKeyContainsWord($this->priorityOptions, $rowData[self::PRIORITY])){
            $this->addRowError(ValidatorInterface::ERROR_INVALID_PRIORITY, $rowNum);
            return false;
        }

        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    /**
     * check if array value contain the input string
     * 
     * @return bool
     */
    protected function isArrayKeyContainsWord(array $myArray, $word)
    {
        foreach ($myArray as $key=>$value) {
            if ($key == $word) {
                return true;
            }
        }

        return false;
    }

    /**
     * compare time
     * 
     * @param date $date1
     * @param date $date2
     * @return int
     * 1:   date1>date2
     * 0:   date1=date2
     * -1:  date1<date2
     */
    protected function compareDate($date1, $date2)
    {
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        if($time1 > $time2){
            return 1;
        } elseif ($time1 == $time2) {
            return 0;
        } else {
            return -1;
        }
    }

    /**
     * Create Advanced price data from raw data.
     *
     * @throws \Exception
     * @return bool Result of operation.
     */
    protected function _importData()
    {
        if (Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->deleteEntity();
        } elseif (Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->replaceEntity();
        } elseif (Import::BEHAVIOR_APPEND == $this->getBehavior()) {
            $this->saveEntity();
        }

        return true;
    }

    /**
     * Save newsletter subscriber
     *
     * @return $this
     */
    public function saveEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }

    /**
     * Replace newsletter subscriber
     *
     * @return $this
     */
    public function replaceEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }

    /**
     * Deletes newsletter subscriber data from raw data.
     *
     * @return $this
     */
    public function deleteEntity()
    {
        $listId = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $this->validateRow($rowData, $rowNum);
                if (!$this->getErrorAggregator()->isRowInvalid($rowNum)) {
                    $rowId = $rowData[self::TASK_ID];
                    $listId[] = $rowId;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                }
            }
        }

        if ($listId) {
            $this->deleteEntityFinish(array_unique($listId), self::TABLE_ENTITY);
        }

        return $this;
    }

    /**
     * Save and replace newsletter subscriber
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function saveAndReplaceEntity()
    {
        $behavior = $this->getBehavior();
        $listId = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError(ValidatorInterface::ERROR_ID_IS_EMPTY, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }
                $taskId= $rowData[self::TASK_ID];
                $listId[] = $taskId;
                $entityList[$taskId][] = [
                    self::TASK_ID       => $rowData[self::TASK_ID     ],
                    self::TASK_NAME     => $rowData[self::TASK_NAME   ],
                    self::TASK_CONTENT  => $rowData[self::TASK_CONTENT],
                    self::START_DATE    => $rowData[self::START_DATE  ],
                    self::END_DATE      => $rowData[self::END_DATE    ],
                    self::STATUS        => $rowData[self::STATUS      ],
                    self::ASSIGN_TO     => $rowData[self::ASSIGN_TO   ],
                    self::PROGRESS      => $rowData[self::PROGRESS    ],
                    self::DESCRIPTION   => $rowData[self::DESCRIPTION ],
                    self::PRIORITY      => $rowData[self::PRIORITY    ],
                    self::CREATED_AT    => $rowData[self::CREATED_AT  ],
                    self::UPDATED_AT    => $rowData[self::UPDATED_AT  ],
                    self::USER_CREATED  => $rowData[self::USER_CREATED],
                    self::USER_UPDATED  => $rowData[self::USER_UPDATED],
                ];
            }

            if (Import::BEHAVIOR_REPLACE == $behavior) {
                if ($listId) {
                    if ($this->deleteEntityFinish(array_unique($listId), self::TABLE_ENTITY)) {
                        $this->saveEntityFinish($entityList, self::TABLE_ENTITY);
                    }
                }
            } elseif (Import::BEHAVIOR_APPEND == $behavior) {
                $this->saveEntityFinish($entityList, self::TABLE_ENTITY);
            }
        }

        return $this;
    }

    /**
     * Save product prices.
     *
     * @param array $priceData
     * @param string $table
     * @return $this
     */
    protected function saveEntityFinish(array $entityData, $table)
    {        
        if ($entityData) {
            $tableName = $this->_connection->getTableName($table);
            $entityIn = [];

            foreach ($entityData as $id => $entityRows) {
                foreach ($entityRows as $row) {
                    $entityIn[] = $row;
                }
            }

            if ($entityIn) {
                $this->_connection->insertOnDuplicate($tableName, $entityIn,[
                    self::TASK_ID     ,
                    self::TASK_NAME   ,
                    self::TASK_CONTENT,
                    self::START_DATE  ,
                    self::END_DATE    ,
                    self::STATUS      ,
                    self::ASSIGN_TO   ,
                    self::PROGRESS    ,
                    self::DESCRIPTION ,
                    self::PRIORITY    ,
                    self::CREATED_AT  ,
                    self::UPDATED_AT  ,
                    self::USER_CREATED,
                    self::USER_UPDATED,
                ]);
            }
        }
        return $this;
    }
    
    protected function deleteEntityFinish(array $listId, $table)
    {
        if ($table && $listId) {
            try {
                $this->countItemsDeleted += $this->_connection->delete(
                    $this->_connection->getTableName($table),
                    $this->_connection->quoteInto('task_id IN (?)', $listId)
                );
                return true;
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * check date string is valid with input format
     */
    protected function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}