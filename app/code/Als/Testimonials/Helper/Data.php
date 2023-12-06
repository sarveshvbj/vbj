<?php
namespace Als\Testimonials\Helper;
 
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
 
class Data extends AbstractHelper
{
   const XML_PATH_ENABLED      = 'testimonials/general/enable_in_frontend';
   
 
   /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
       Context $context,
       ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager     
    ) {
       parent::__construct($context);
       $this->_scopeConfig = $scopeConfig;
       $this->_storeManager = $storeManager;
    }
 
   /**
     * Check for module is enabled in frontend
     *
     * @return bool
     */
   public function isEnabledInFrontend($store = null)
   {
      return $this->_scopeConfig->getValue(
         self::XML_PATH_ENABLED,
         ScopeInterface::SCOPE_STORE
      );
   }
 
   /**
     * Get head title for testimonials list page
     *
     * @return string
     */
    public function getBaseUrl()
    {
        $mediaUrl = $this->_storeManager->getStore()
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }
}