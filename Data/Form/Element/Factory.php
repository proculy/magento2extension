<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Vvc\Task\Data\Form\Element;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Factory extends \Magento\Framework\Data\Form\Element\Factory
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Standard library element types
     *
     * @var string[]
     */
    protected $_standardTypes = [
        'button',
        'checkbox',
        'checkboxes',
        'column',
        'date',
        'editablemultiselect',
        'editor',
        'fieldset',
        'file',
        'gallery',
        'hidden',
        'image',
        'imagefile',
        'label',
        'link',
        'multiline',
        'multiselect',
        'note',
        'obscure',
        'password',
        'radio',
        'radios',
        'reset',
        'select',
        'submit',
        'text',
        'textarea',
        'time',
        'range'
    ];

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        parent::__construct($objectManager);
    }

    /**
     * Factory method
     *
     * @param string $elementType Standard element type or Custom element class
     * @param array $config
     * @return AbstractElement
     * @throws \InvalidArgumentException
     */
    public function create($elementType, array $config = [])
    {
        if (in_array($elementType, $this->_standardTypes)) {
            if ($elementType == 'range') {
                $className = 'Vvc\Task\Data\Form\Element\\' . ucfirst($elementType);
            } else {
                $className = 'Magento\Framework\Data\Form\Element\\' . ucfirst($elementType);
            }
        } else {
            $className = $elementType;
        }

        $element = $this->_objectManager->create($className, $config);
        if (!$element instanceof AbstractElement) {
            throw new \InvalidArgumentException(
                $className . ' doesn\'n extend \Magento\Framework\Data\Form\Element\AbstractElement'
            );
        }

        return $element;
    }
}
