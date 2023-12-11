<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://magezon.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  Magezon
 * @package   Magezon_LazyLoad
 * @copyright Copyright (C) 2018 Magezon (https://magezon.com)
 */

namespace Magezon\LazyLoad\Model\Template;

class Filter extends \Magento\Widget\Model\Template\Filter
{
	/**
	 * @var \Magezon\LazyLoad\Helper\Data
	 */
	protected $dataHelper;

	/**
	 * @var \Magezon\LazyLoad\Helper\Filter
	 */
	protected $filterHelper;

	/**
	 * @param \Magento\Framework\Stdlib\StringUtils              $string              
	 * @param \Psr\Log\LoggerInterface                           $logger              
	 * @param \Magento\Framework\Escaper                         $escaper             
	 * @param \Magento\Framework\View\Asset\Repository           $assetRepo           
	 * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig         
	 * @param \Magento\Variable\Model\VariableFactory            $coreVariableFactory 
	 * @param \Magento\Store\Model\StoreManagerInterface         $storeManager        
	 * @param \Magento\Framework\View\LayoutInterface            $layout              
	 * @param \Magento\Framework\View\LayoutFactory              $layoutFactory       
	 * @param \Magento\Framework\App\State                       $appState            
	 * @param \Magento\Framework\UrlInterface                    $urlModel            
	 * @param \Pelago\Emogrifier                                 $emogrifier          
	 * @param \Magento\Email\Model\Source\Variables              $configVariables     
	 * @param \Magento\Widget\Model\ResourceModel\Widget         $widgetResource      
	 * @param \Magento\Widget\Model\Widget                       $widget              
	 * @param \Magezon\LazyLoad\Helper\Data                      $dataHelper          
	 * @param \Magezon\LazyLoad\Helper\Filter                    $filterHelper        
	 */
    public function __construct(
        \Magento\Framework\Stdlib\StringUtils $string,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Variable\Model\VariableFactory $coreVariableFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\UrlInterface $urlModel,
        \Pelago\Emogrifier $emogrifier,
        \Magento\Email\Model\Source\Variables $configVariables,
        \Magento\Widget\Model\ResourceModel\Widget $widgetResource,
        \Magento\Widget\Model\Widget $widget,
		\Magezon\LazyLoad\Helper\Data $dataHelper,
		\Magezon\LazyLoad\Helper\Filter $filterHelper
    ) {
        parent::__construct(
            $string,
            $logger,
            $escaper,
            $assetRepo,
            $scopeConfig,
            $coreVariableFactory,
            $storeManager,
            $layout,
            $layoutFactory,
            $appState,
            $urlModel,
            $emogrifier,
            $configVariables,
            $widgetResource,
            $widget
        );
		$this->dataHelper   = $dataHelper;
		$this->filterHelper = $filterHelper;
    }

	public function filter($value)
	{
		$value = parent::filter($value);
		if (is_string($value) && $this->dataHelper->getConfig('general/lazy_load_cms')) {
			$value = $this->filterHelper->filter($value);
		}
		return $value;
	}
}