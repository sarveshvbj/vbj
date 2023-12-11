<?php 

namespace Retailinsights\ConfigProducts\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class GoToCheckout implements ObserverInterface
{
    protected $uri;
    protected $responseFactory;
    protected $_urlinterface;
    protected $redirect;
    protected $_storeManager;

 public function __construct(
        \Zend\Validator\Uri $uri,
        \Magento\Framework\UrlInterface $urlinterface,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
         \Magento\Store\Model\StoreManagerInterface $storeManager,
         \Magento\Framework\App\RequestInterface $request
    ) {
        $this->uri = $uri;
        $this->_urlinterface = $urlinterface;
        $this->responseFactory = $responseFactory;
        $this->_request = $request;
        $this->redirect = $redirect;
        $this->_storeManager = $storeManager;
    }

    public function execute(Observer $observer)
    {
        /*$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/addtocartobs.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);*/
        $storeUrl = $this->_storeManager->getStore()->getBaseUrl();
        /*$logger->info($storeUrl);*/
        $observer->getRequest()->setParam('return_url', $this->_urlinterface->getUrl('checkout/index/index'));
        return $this;
    }
}