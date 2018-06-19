<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Form text element
 *
 * @author      phung quoc vuong <vuong.pq@vn.vinx.asia>
 */
namespace Vvc\Task\Data\Form\Element;

use Magento\Framework\Escaper;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\CollectionFactory;

class Range extends AbstractElement
{
    /**
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param array $data
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        $data = []
    ) 
    {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->setType('text');
        $this->setExtType('textfield');
    }

    /**
     * Get the after element html.
     *
     * @return mixed
     */
    public function getAfterElementHtml()
    {
        return '<br><input type="range" id="progress_class" class="progress_class" min="0" max="100" step="10">';
    }

    /**
     * Get the HTML
     *
     * @return mixed
     */
    public function getHtml()
    {
        //$this->addClass('input-text');
        return parent::getHtml();
    }

    /**
     * Get the attributes
     *
     * @return string[]
     */
    public function getHtmlAttributes()
    {
        return [
            'type',
            'title',
            'class',
            'style',
            'onclick',
            'onchange',
            'onkeyup',
            'disabled',
            'readonly',
            'maxlength',
            'tabindex',
            'placeholder',
            'data-form-part',
            'data-role',
            'data-action',
            'min',
            'max',
            'step',
            'digits'
        ];
    }
}
