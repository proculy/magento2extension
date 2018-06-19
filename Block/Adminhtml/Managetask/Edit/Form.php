<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
 
namespace Vvc\Task\Block\Adminhtml\Managetask\Edit;

/**
 * Adminhtml task edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
 
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    
    protected $_taskFactory;
    protected $_status;
    protected $_assigntoOptions;
    protected $_priorityOptions;
    protected $_statusOptions;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Vvc\Task\Model\Task\Source\AssignTo $assigntoOptions,
        \Vvc\Task\Model\Task\Source\Priority $priorityOptions,
        \Vvc\Task\Model\Task\Source\Status $statusOptions,
        \Vvc\Task\Model\TaskFactory $taskFactory,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;        
        $this->_assigntoOptions = $assigntoOptions;
        $this->_priorityOptions = $priorityOptions;
        $this->_statusOptions = $statusOptions;
        $this->_taskFactory = $taskFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('managetask_form');
        $this->setTitle(__('Task Information'));
    }
 
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $id = $this->getRequest()->getParam('task_id');

        /** @var \Vvc\Task\Model\Task $model */
        $model = $this->_taskFactory->create();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );
 
        $form->setHtmlIdPrefix('task_');
 
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );
 
        if ($id) {
            $model->load($id);
            $fieldset->addField('task_id', 'hidden', ['name' => 'task_id']);
        }

        $fieldset->addField(
            'task_name',
            'text',
            ['name' => 'task_name', 'label' => __('Task Name'), 'title' => __('Task Name'), 'required' => true]
        );

        $fieldset->addField(
            'task_content',
            'text',
            [
                'name' => 'task_content', 
                'label' => __('Task Content'), 
                'title' => __('Task Content'), 
                'required' => false
            ]
        );
        
        $fieldset->addField(
            'start_date',
            'date',
            [
                'name' => 'start_date',
                'label' => __('Start Date'),
                'title' => __('Start Date'),
                'required' => true,
                'date_format' => 'yyyy-MM-dd',
                'class' => 'required-entry validate-date validate-date-range date-range-attribute-from'
            ]
        );

        $fieldset->addField(
            'end_date',
            'date',
            [
                'name' => 'end_date',
                'label' => __('End Date'),
                'title' => __('End Date'),
                'required' => true,
                'date_format' => 'yyyy-MM-dd',
                'class' => 'required-entry validate-date validate-date-range date-range-attribute-to'
            ]
        );

        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'required' => true,
                'options' => $this->_statusOptions->toOptionArray(true),
            ]
        );

        $fieldset->addField(
            'assign_to',
            'select',
            [
                'name' => 'assign_to', 
                'label' => __('Assign To'), 
                'title' => __('Assign To'), 
                'required' => true,
                'options' => $this->_assigntoOptions->toOptionArray(true),
                ]
        );
        
        $fieldset->addField(
            'progress',
            'range',
            [
                'name' => 'progress', 
                'label' => __('Progress'), 
                'title' => __('Progress'), 
                'required' => false,
                'class' => 'validate-digits-range digits-range-0-100'
            ]
        );

        $fieldset->addField(
            'description',
            'text',
            [
                'name' => 'description', 
                'label' => __('Description'), 
                'title' => __('Description'), 
                'required' => false]
        );
            
        $fieldset->addField(
            'priority',
            'select',
            [
                'label' => __('Priority'),
                'title' => __('Priority'),
                'name' => 'priority',
                'required' => true,
                'options' => $this->_priorityOptions->toOptionArray(true),
            ]
        );
        
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
}