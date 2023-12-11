<?php
/**
 * MageGadgets Extensions
 * Stonemanager Extension
 * 
 * @package    		Magegadgets_Stonemanager
 * @copyright  		Copyright (c) 2017 MageGadgets Extensions (http://www.magegadgets.com/)
 * @contactEmail   	support@magegadgets.com
*/

namespace Magegadgets\Stonemanager\Block\Adminhtml\Form\Field;

use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\Escaper;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Export extends AbstractElement
{
    protected $_backendUrl;

    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        UrlInterface $backendUrl,
        array $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->_backendUrl = $backendUrl;
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        /** @var \Magento\Backend\Block\Widget\Button $buttonBlock  */
        $buttonBlock = $this->getForm()->getParent()->getLayout()->createBlock('Magento\Backend\Block\Widget\Button');

        $params = [];

        $url = $this->_backendUrl->getUrl("stonemanager/table/export", $params);
        $data = [
            'label' => __('Export CSV'),
            'onclick' => "setLocation('" . $url . "')",
            'class' => '',
        ];

        $html = $buttonBlock->setData($data)->toHtml();
        return $html;
    }
}
