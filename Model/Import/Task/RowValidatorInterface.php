<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vvc\Task\Model\Import\Task;

interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
    const ERROR_INVALID_ID = 'InvalidValueID';
    const ERROR_ID_IS_EMPTY = 'EmptyID';
    const ERROR_TASK_NAME_IS_EMPTY = 'EmptyTaskName';
    const ERROR_START_DATE_IS_EMPTY = 'EmptyStartDate';
    const ERROR_INVALID_START_DATE = 'InvalidStartDate';
    const ERROR_END_DATE_IS_EMPTY = 'EmptyEndDate';
    const ERROR_INVALID_END_DATE = 'InvalidEndDate';
    const ERROR_INVALID_START_END_DATE = 'InvalidStartEndDate';
    const ERROR_STATUS_IS_EMPTY = 'EmptyStatus';
    const ERROR_INVALID_STATUS = 'InvalidStatus';
    const ERROR_ASSIGN_TO_IS_EMPTY = 'EmptyAssignTo';
    const ERROR_INVALID_ASSIGN_TO = 'InvalidAssignTo';
    const ERROR_PROGRESS_IS_NAN = 'NANProgress';
    const ERROR_PROGRESS_IS_OUTOFRANGE = 'OutOfRangeProgress';
    const ERROR_PRIORITY_IS_EMPTY = 'EmptyPriority';
    const ERROR_INVALID_PRIORITY = 'InvalidPriority';

    /**
     * Initialize validator
     *
     * @return $this
     */
    public function init($context);
}