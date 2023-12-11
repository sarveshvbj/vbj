<?php

namespace Magegadgets\Videoform\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class DellPost
 * @package Magegadgets\Videoform\Controller\Index
 */
class AddToCart extends \Magento\Framework\App\Action\Action
{
    /**
     * Result page factory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory;
     */
    protected $_rawResultFactory;
    protected $formKey;
    protected $cart;
    protected $product;
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\Product $product
    )
    {
        parent::__construct($context);
        $this->_rawResultFactory = $context->getResultFactory();
        $this->formKey = $formKey;
        $this->cart = $cart;
        $this->product = $product;
    }
    public function execute()
    {
        $resultRaw = $this->_rawResultFactory->create(ResultFactory::TYPE_RAW);
        $response = '-failure';
        /*$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/customaddtocart.log');
                               $logger = new \Zend\Log\Logger();
                               $logger->addWriter($writer);
                               $logger->info('beforeloop');*/
        $params = $this->getRequest()->getParams();
        $productId = isset($params['product']) ? (int)$params['product'] : 0;
        if($productId > 0)
        {
            /*$logger->info('inside_product'.$productId);*/
            try {
                /*$logger->info('insidetry');*/
              //  $params['form_key'] = $this->formKey->getFormKey();
               // $logger->info('insidetry'.$params['form_key']);
                $_product = $this->product->load($productId);
                $this->cart->addProduct($_product, $params);
                $this->cart->save();
                /*$logger->info('added success');*/
                $response = '-success';
                // $logger->info('Cart saved');
               // $response = array('status'=>'success','message'=>'Product added successfully');
            }
            catch(\Exception $e) {
                $response = '-failure';
                 /*$logger->info('failure');*/
               // $response = array('status'=>'error','message'=>$e->getMessage());
                 /*$logger->info('Exception'.$e->getMessage());*/
            }
        }

        $resultRaw->setHeader('Content-Type', 'text/plain');
        $resultRaw->setContents($response);

        //return $resultJson->setData($response);
        return $resultRaw;
    }
}