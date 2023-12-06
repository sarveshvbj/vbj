<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Retailinsights\ConfigProducts\Block\Product\View\Options\Type;

/**
 * Product options text type block
 *
 * @api
 * @since 100.0.2
 */
class CustomSelect extends \Magento\Catalog\Block\Product\View\Options\AbstractOptions
{
    /**
     * Return html for control element
     *
     * @return string
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function afterGetValuesHtml(\Magento\Catalog\Block\Product\View\Options\Type\Select $subject, $result)
    {
        $_option = $this->getOption();
        $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());
        $store = $this->getProduct()->getStore();
        $product=$this->getProduct();
            $purity_title= \Retailinsights\ConfigProducts\Helper\Data::PURITY_TITLE;
    $d_quality_title = \Retailinsights\ConfigProducts\Helper\Data::DIAMOND_TITLE;
    $ring_title = \Retailinsights\ConfigProducts\Helper\Data::RINGS_TITLE;

    $purity_attr= \Retailinsights\ConfigProducts\Helper\Data::PURITY_ATTR;
    $d_quality_attr = \Retailinsights\ConfigProducts\Helper\Data::DIAMOND_ATTR;
    $ring_attr = \Retailinsights\ConfigProducts\Helper\Data::RINGS_ATTR;
    
      $default_purity = $product->getResource()->getAttribute($purity_attr)->getFrontend()->getValue($product);
    $default_d_quality = $product->getResource()->getAttribute($d_quality_attr)->getFrontend()->getValue($product);
    $default_ringsize = $product->getResource()->getAttribute($ring_attr)->getFrontend()->getValue($product);



        $this->setSkipJsReloadPrice(1);
        // Remove inline prototype onclick and onchange events

        if ($_option->getType() == \Magento\Catalog\Api\Data\ProductCustomOptionInterface::OPTION_TYPE_DROP_DOWN ||
            $_option->getType() == \Magento\Catalog\Api\Data\ProductCustomOptionInterface::OPTION_TYPE_MULTIPLE
        ) {
            $require = $_option->getIsRequire() ? ' required' : '';
            $extraParams = '';
            $select = $this->getLayout()->createBlock(
                \Magento\Framework\View\Element\Html\Select::class
            )->setData(
                [
                    'id' => 'select_' . $_option->getId(),
                    'class' => $require . ' product-custom-option admin__control-select '
                ]
            );
            if ($_option->getType() == \Magento\Catalog\Api\Data\ProductCustomOptionInterface::OPTION_TYPE_DROP_DOWN) {
                $select->setName('options[' . $_option->getId() . ']')->addOption('', __('-- Please Select --'));
            } else {
                $select->setName('options[' . $_option->getId() . '][]');
                $select->setClass('multiselect admin__control-multiselect' . $require . ' product-custom-option');
            }
            foreach ($_option->getValues() as $_value) {
                $priceStr = $this->_formatPrice(
                    [
                        'is_percent' => $_value->getPriceType() == 'percent',
                        'pricing_value' => $_value->getPrice($_value->getPriceType() == 'percent'),
                    ],
                    false
                );
                 $checked ='';
                if(str_replace(' ','_', strtolower($_option->getTitle()))=='ring_sizes') {       
               $checked = $default_ringsize == $_value->getTitle() ? 'selected' : '';
            }
                if($checked != '') {
                    $select->addOption(
                    $_value->getOptionTypeId(),
                    $_value->getTitle() . ' ' . strip_tags($priceStr) . '',
                    ['price' => $this->pricingHelper->currencyByStore($_value->getPrice(true), $store, false),'selected'=>'selected','data-value'=>$_value->getTitle()]
                ); 
                } else {
                     $select->addOption(
                    $_value->getOptionTypeId(),
                    $_value->getTitle() . ' ' . strip_tags($priceStr) . '',
                    ['price' => $this->pricingHelper->currencyByStore($_value->getPrice(true), $store, false),'data-value'=>$_value->getTitle()]
                );  
                }
               
            }
            if ($_option->getType() == \Magento\Catalog\Api\Data\ProductCustomOptionInterface::OPTION_TYPE_MULTIPLE) {
                $extraParams = ' multiple="multiple"';
            }
            if (!$this->getSkipJsReloadPrice()) {
                $extraParams .= ' onchange="opConfig.reloadPrice()"';
            }
            $extraParams .= ' data-selector="' . $select->getName() . '"';
           $extraParams .= ' data-bind="value:'.str_replace(' ','_', strtolower($_option->getTitle())).', event: { change: GetSelected }"';

            $select->setExtraParams($extraParams);
            
            if ($configValue) {
                $select->setValue($configValue);
            }

            return $select->getHtml();
        }

        if ($_option->getType() == \Magento\Catalog\Api\Data\ProductCustomOptionInterface::OPTION_TYPE_RADIO ||
            $_option->getType() == \Magento\Catalog\Api\Data\ProductCustomOptionInterface::OPTION_TYPE_CHECKBOX
        ) {
            $selectHtml = '<div class="options-list nested" id="options-' . $_option->getId() . '-list">';
            $require = $_option->getIsRequire() ? ' required' : '';
            $arraySign = '';
            switch ($_option->getType()) {
                case \Magento\Catalog\Api\Data\ProductCustomOptionInterface::OPTION_TYPE_RADIO:
                    $type = 'radio';
                    $class = 'radio admin__control-radio';
                    if (!$_option->getIsRequire()) {
                        $selectHtml .= '<div class="field choice admin__field admin__field-option">' .
                            '<input type="radio" id="options_' .
                            $_option->getId() .
                            '" class="' .
                            $class .
                            ' product-custom-option" data-attr-value="'. $_option->getTitle() .'" name="options[' .
                            $_option->getId() .
                            ']"' .
                            ' data-selector="options[' . $_option->getId() . ']"' .
                            ($this->getSkipJsReloadPrice() ? '' : ' onclick="opConfig.reloadPrice()"') .
                            ' value="" checked="checked" /><label class="label admin__field-label" for="options_' .
                            $_option->getId() .
                            '"><span>' .
                            __('None') . '</span></label></div>';
                    }
                    break;
                case \Magento\Catalog\Api\Data\ProductCustomOptionInterface::OPTION_TYPE_CHECKBOX:
                    $type = 'checkbox';
                    $class = 'checkbox admin__control-checkbox';
                    $arraySign = '[]';
                    break;
            }
            $count = 1;
            foreach ($_option->getValues() as $_value) {
                $count++;

                $priceStr = $this->_formatPrice(
                    [
                        'is_percent' => $_value->getPriceType() == 'percent',
                        'pricing_value' => $_value->getPrice($_value->getPriceType() == 'percent'),
                    ]
                );

                $htmlValue = $_value->getOptionTypeId();
                if ($arraySign) {
                    $checked = is_array($configValue) && in_array($htmlValue, $configValue) ? 'checked' : '';
                } else {
                    //$checked = $configValue == $htmlValue ? 'checked' : '';
                    if(str_replace(' ','_', strtolower($_option->getTitle()))=='purity')
                    $checked = strtolower($default_purity) == strtolower($_value->getTitle()) ? 'checked' : '';
                    elseif(str_replace(' ','_', strtolower($_option->getTitle()))=='diamond_quality')
                    $checked = strtolower($default_d_quality) == strtolower($_value->getTitle()) ? 'checked' : '';
                    else
                    $checked = $configValue == $htmlValue ? 'checked' : '';
                }

                $dataSelector = 'options[' . $_option->getId() . ']';
                if ($arraySign) {
                    $dataSelector .= '[' . $htmlValue . ']';
                }

                $selectHtml .= '<div class="field choice admin__field admin__field-option' .
                    $require .
                    '">' .
                    '<input data-bind="checked: '.str_replace(' ','_', strtolower($_option->getTitle())).', click:'.str_replace(' ','_', strtolower($_option->getTitle())).'Click " type="' .
                    $type .
                    '" class="' .
                    $class .
                    ' ' .
                    $require .
                    ' product-custom-option"' .
                    ($this->getSkipJsReloadPrice() ? '' : ' onclick="opConfig.reloadPrice()"') .
                    ' data-value="' .$_value->getTitle().'" name="options[' .
                    $_option->getId() .
                    ']' .
                    $arraySign .
                    '" id="' .str_replace(' ','_', strtolower($_option->getTitle())).'_'.$_value->getTitle().'" value="' .
                    $htmlValue .
                    '" ' .
                    $checked .
                    ' data-selector="' . $dataSelector . '"' .
                    ' price="' .
                    $this->pricingHelper->currencyByStore($_value->getPrice(true), $store, false) .
                    '" />' .
                    '<label class="label admin__field-label" for="' .str_replace(' ','_', strtolower($_option->getTitle())).'_'.$_value->getTitle().
                    '"><span>' .
                    $_value->getTitle() .
                    '</span> ' .
                    $priceStr .
                    '</label>';
                $selectHtml .= '</div>';
            }
            $selectHtml .= '</div>';

            return $selectHtml;
        }
    }
}
