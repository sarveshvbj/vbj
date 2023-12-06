<?php
/**
 * Bss Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   Bss
 * @package    Bss_ProductsWidgetSlider
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 Bss Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */

namespace Bss\ProductsWidgetSlider\Model\Config\Source;

class DatePicker extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $elementFactory;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * DatePicker constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
            parent::__construct($context, $data);
            $this->elementFactory = $elementFactory;
            $this->coreRegistry = $coreRegistry;
    }

    /**
     * Prepare chooser element HTML
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element Form Element
     * @return \Magento\Framework\Data\Form\Element\AbstractElement
     */

    public function prepareElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $input = $this->elementFactory->create("text", ['data' => $element->getData()]);
        $input->setId($element->getId());
        $input->setForm($element->getForm());
        $input->setClass("admin__control-text input-text no-changes");
        if ($element->getRequired()) {
            $input->addClass('required-entry');
        }
        if (!$this->coreRegistry->registry('datepicker_loaded')) {
            $this->coreRegistry->registry('datepicker_loaded', 1);
        }

        $id = '#'.$element->getHtmlId();
        $addHtml = '';
        if ($this->getRequest()->isXmlHttpRequest()) {
            $addHtml .= '<script type="text/javascript">
                require(["jquery", "jquery/ui", "mage/calendar"], function () {
                    $(document).ready(function () {                    
                        $("#'.$element->getHtmlId().'").calendar({
                            dateFormat:"yy-mm-dd",
                            showButtonPanel: true,
                            changeMonth: true,
                            changeYear: true,
                            yearRange: "-90:+00"
                        });
                        $(".ui-datepicker-trigger").removeAttr("style");
                        $(".control-value").hide();                                    
                        $(".ui-datepicker-trigger").click(function(){
                            $(this).prev().focus();                        
                        });                     
                    });                
                });            
            </script>'
            ;
        } else {
            $addHtml .= '<script type="text/x-magento-init">
                {"*": {"Bss_ProductsWidgetSlider/js/datepicker":{"id":"'.$id.'"}}}
                </script>';
        }
        $html = $element->setData('after_element_html', $input->getElementHtml().$addHtml);
        return $html;
    }
}
