<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
 
namespace Vvc\Task\Block\Adminhtml\Managetask;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    protected $_taskFactory;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Vvc\Task\Model\TaskFactory $taskFactory,
        array $data = []
    ) {
        $this->_taskFactory = $taskFactory;
        parent::__construct($context, $data);
    }
 
    /**
     * Initialize manage task edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'task_id';
        $this->_blockGroup = 'Vvc_Task';
        $this->_controller = 'adminhtml_managetask';
        $id = $this->getRequest()->getParam('task_id');
        parent::_construct();
 
        if ($this->_isAllowedAction('Vvc_Task::save')) {
            $this->buttonList->update('save', 'label', __('Save Task'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }
 
        if ($this->_isAllowedAction('Vvc_Task::managetask_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Task'));
        } else {
            $this->buttonList->remove('delete');
        }
 
        if ($id) {
            $this->buttonList->remove('reset');
        }
    }
 
    /**
     * Retrieve text for header element depending on loaded blocklist
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $id = $this->getRequest()->getParam('task_id');

        if ($id) {
            return __("Edit Task '%1'", $this->escapeHtml($this->_taskFactory->getTitle()));
        } else {
            return __('New Task');
        }
    }
 
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
 
    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('task/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}