<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_StoreOptimization
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\StoreOptimization\Helper;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Customer\Model\Session;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Directory\Model\CurrencyConfig;
use Magento\Directory\Helper\Data as DirectoryHelper;

/**
 * StoreOptimization data helper.
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    
    /**
     * __constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
    }

    /**
     * get is defer load js enabled
     *
     * @return boolean
     */
    public function getIsDeferLoadingEnable()
    {
        return (boolean) $this->scopeConfig->getValue(
            'wkoptimization/defer_js/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * get is infinite scroller enabled
     *
     * @return boolean
     */
    public function getIsScrollerEnable()
    {
        return (boolean) $this->scopeConfig->getValue(
            'wkoptimization/infinite_scroll/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * get is image lazy load enabled
     *
     * @return boolean
     */
    public function getIsLazyLoadingEnable()
    {
        return (boolean) $this->scopeConfig->getValue(
            'wkoptimization/lazyload/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
