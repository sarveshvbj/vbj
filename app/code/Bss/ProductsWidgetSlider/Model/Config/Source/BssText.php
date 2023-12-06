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

Class BssText extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $elementFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    // @codingStandardsIgnoreFile
    /**
     * BssText constructor.
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
        $this->elementFactory = $elementFactory;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
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
        $input->setClass("widget-option input-text admin__control-text");
        if ($element->getRequired()) {
            $input->addClass('required-entry');
        }
        $addHtml = '';
        if ($this->getRequest()->isXmlHttpRequest()) {
            $addHtml =  '<script type="text/javascript">
            require(["jquery", "jquery/ui"], function ($) {
                $(document).ready(function () {                   
                    $(".control-value").hide();
                    $("select[name=\'parameters[show_as_slider]\']").change(function () {
                        var selectedParams;
                        selectedParams = $("select[name=\'parameters[show_as_slider]\']").val();
                        if (selectedParams == 1) {                               
                         $("input[name=\'parameters[products_per_page]\']").parent().parent().parent().hide();
                         $("input[name=\'parameters[products_per_page]\']").parent().parent().hide();
                         $("input[name=\'parameters[products_per_page]\']").hide();
                         $("input[name=\'parameters[products_per_page]\']").addClass("ignore-validate");
                         }
                         else {
                         var selectedParams2;
                         selectedParams2 = $("select[name=\'parameters[show_pager]\']").val();
                             if(selectedParams2 == 1){                                    
                                 $("input[name=\'parameters[products_per_page]\']").parent().parent().parent().show();
                                 $("input[name=\'parameters[products_per_page]\']").parent().parent().show();
                                 $("input[name=\'parameters[products_per_page]\']").show();
                                 $("input[name=\'parameters[products_per_page]\']").removeClass("ignore-validate");
                             }
                         }
                    });    
                    $("select[name=\'parameters[show_pager]\']").change(function () {
                        var selectedParams;
                        selectedParams = $("select[name=\'parameters[show_pager]\']").val();
                        if (selectedParams == 0) {                               
                            $("input[name=\'parameters[products_per_page]\']").parent().parent().parent().hide();
                            $("input[name=\'parameters[products_per_page]\']").parent().parent().hide();                  
                         }
                         else {                                 
                             $("input[name=\'parameters[products_per_page]\']").parent().parent().parent().show();
                             $("input[name=\'parameters[products_per_page]\']").parent().parent().show();                                 
                         }                         
                    });
                });       
                var selectedParams;                    
                selectedParams = $("select[name=\'parameters[show_pager]\']").val();
                if (selectedParams == 0) {
                     $("input[name=\'parameters[products_per_page]\']").parent().parent().parent().hide();
                     $("input[name=\'parameters[products_per_page]\']").parent().parent().hide();
                }
            });          
            </script>';
        }
        else {
            $addHtml .= '<script type="text/x-magento-init">{"*":{"Bss_ProductsWidgetSlider/js/text":{}}}</script>';
        }
        $html = $element->setData('after_element_html', $input->getElementHtml().$addHtml);
        return $html;
    }
}
