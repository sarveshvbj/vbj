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
 * StoreOptimization image helper.
 */
class Image extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Webkul\StoreOptimization\Logger\Logger
     */
    protected $optimizerLogger;

    /**
     * __constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Webkul\StoreOptimization\Logger\Logger $optimizerLogger
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->optimizerLogger = $optimizerLogger;
    }

    /**
     * get is image optimization enabled
     *
     * @return boolean
     */
    public function getIsImageOptimizationEnable()
    {
        return $this->scopeConfig->getValue(
            'wkoptimization/image_optimization/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * get image compression type
     *
     * @return boolean
     */
    public function getImageCompressionType()
    {
        return $this->scopeConfig->getValue(
            'wkoptimization/image_optimization/type',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * get is image responsive pixels enabled
     *
     * @return boolean
     */
    public function getIsImageSrcSetEnable()
    {
        return $this->scopeConfig->getValue(
            'wkoptimization/image_optimization/enable_src_set',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * get is image responsive pixels
     *
     * @return boolean
     */
    public function getIsImagePixels()
    {
        return $this->scopeConfig->getValue(
            'wkoptimization/image_optimization/pixels',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function createLog($log)
    {
        $this->optimizerLogger->info($log);
    }
}
