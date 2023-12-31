<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_GiftCard
 * @author    Webkul Software Private Limited
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\GiftCard\Observer;
 
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
 
class BeforeCreateCreditMemo implements ObserverInterface
{
 
    /** @var \Magento\Framework\Logger\Monolog */
    protected $_logger;

    /** @var Magento\Framework\App\RequestInterface */
    protected $_request;

    /**
     * @param \Psr\Log\LoggerInterface               $loggerInterface
     * @param RequestInterface                       $requestInterface
     */
    public function __construct(
        \Psr\Log\LoggerInterface $loggerInterface,
        RequestInterface $requestInterface,
        \Magento\Framework\Message\Manager $messageManager,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\Http $redirect
    ) {
        $this->_logger = $loggerInterface;
        $this->_request = $requestInterface;
        $this->_messageManager = $messageManager;
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_redirect = $redirect;
    }
    
    /**
     * This is the method that fires when the event runs.
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        $controller = $observer->getControllerAction();
        $creditmemo = $observer->getData('creditmemo');
        foreach ($creditmemo->getAllItems() as $creditmemoItem) {
            $orderItem = $creditmemoItem->getOrderItem();
            if ($orderItem->getData()['product_type'] == 'giftcard') {
                try {
                    throw new LocalizedException(__('Cannot refund the items of type giftcard'));
                } catch (\Exception $e) {
                    $this->_messageManager->addError(__("dadasdasdasd"));
                    $RedirectUrl= $this->_url->getUrl('sales/order/view/order_id/'.$this->_request->getParam('order_id'));
                    $this->_redirect->setRedirect($RedirectUrl);
                    // $this->_responseFactory->create()->setRedirect($RedirectUrl)->sendResponse();
                    // exit(0);
                }
            }
        }
    }
}
