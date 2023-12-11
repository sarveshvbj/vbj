<?php
/**
 * Created by PhpStorm.
 * User: yogesh
 * Date: 5/3/19
 * Time: 12:17 PM
 */
namespace Mage2\Inquiry\Helper;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Api\ProductRepositoryInterface as ProductRepository;
use Magento\Framework\Mail\Template\TransportBuilder as TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface as InlineTranslation;
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;
use Magento\Store\Model\StoreManagerInterface as StoreManager;

/**
 * Class Data
 * @package Mage2\Inquiry\Helper
 */
class Data extends AbstractHelper
{
    const CUSTOM_SENDER_EMAIL = 'mage2_product_inquiry_section/general/senderemail';
    const CUSTOM_SENDER_NAME  = 'mage2_product_inquiry_section/general/sendername';
    const CONTACT_SENDER_EMAIL = 'contact/email/recipient_email';
    const CONTACT_SENDER_NAME = 'contact/email/sender_email_identity';
    const QUESTION_DISPLAY_SETTING = 'mage2_product_inquiry_section/general/questionenabled';
    const EMAIL_SEND_SETTING_ADMIN  = 'mage2_product_inquiry_section/general/sendemailtoadmin';
    const QUESTION_DISPLAY_COUNT  = 'mage2_product_inquiry_section/general/questioncount';
    const DEFAULT_QUESTION_COUNT = 5;

    /**
     * @var ScopeConfig
     */
    protected $scopeConfig;

    /**
     * @var StoreManager
     */
    protected $storeManager;

    /**
     * @var ProductRepository $productRepository
     */
    protected $productRepository;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var InlineTranslation
     */
    protected $inlineTranslation;

    /**
     * Data constructor.
     * @param Context $context
     * @param ProductRepository $productRepository
     * @param ScopeConfig $scopeConfig
     * @param StoreManager $storeManager
     * @param TransportBuilder $transportBuilder
     * @param InlineTranslation $inlineTranslation
     */
    public function __construct(
        Context $context,
        ProductRepository $productRepository,
        ScopeConfig $scopeConfig,
        StoreManager $storeManager,
        TransportBuilder $transportBuilder,
        InlineTranslation $inlineTranslation
    ) {
    
        parent::__construct($context);
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
    }

    /**
     * @return mixed
     */
    public function getSenderEmail()
    {
        $customEmail = $this->scopeConfig->getValue(
            self::CUSTOM_SENDER_EMAIL,
            ScopeInterface::SCOPE_STORE
        );

        $contactEmail = $this->scopeConfig->getValue(
            self::CONTACT_SENDER_EMAIL,
            ScopeInterface::SCOPE_STORE
        );

        return ($customEmail) ? $customEmail : $contactEmail ;
    }

    /**
     * @return mixed
     */
    public function getSenderName()
    {
        $customSenderName = $this->scopeConfig->getValue(
            self::CUSTOM_SENDER_NAME,
            ScopeInterface::SCOPE_STORE
        );

        $contactSenderName = $this->scopeConfig->getValue(
            self::CONTACT_SENDER_NAME,
            ScopeInterface::SCOPE_STORE
        );

        return ($customSenderName) ? $customSenderName : $contactSenderName ;
    }

    /**
     * @return int|mixed
     */
    public function getQuestionCount()
    {
        $questionCount =  $this->scopeConfig->getValue(
            self::QUESTION_DISPLAY_COUNT,
            ScopeInterface::SCOPE_STORE
        );

        return ($questionCount) ? $questionCount : self::DEFAULT_QUESTION_COUNT ;
    }

    /**
     * @return mixed
     */
    public function getQuestionDisplaySetting()
    {
        return $this->scopeConfig->getValue(
            self::QUESTION_DISPLAY_SETTING,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function isEmailSendToAdmin()
    {
        return $this->scopeConfig->getValue(
            self::EMAIL_SEND_SETTING_ADMIN,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $sku
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    public function getProductBySku($sku)
    {
        return $this->productRepository->get($sku);
    }

    /**
     * @param $post
     * @throws NoSuchEntityException
     * @throws MailException
     */
    public function sendCustomerEmail($post)
    {
        $product = $this->getProductBySku($post['sku']);
        $templateOptions = array('area' => Area::AREA_FRONTEND, 'store' => $this->storeManager->getStore()->getId());
        $templateVars = array(
            'customer_name' => $post['name'],
            'message'   => $post['message'],
            'product_name' =>  $product->getName(),
            'product_url' =>   $product->getProductUrl()
        );

        // Send Mail
        $from = array('email' => $this->getSenderEmail(), 'name' => $this->getSenderName());
        $this->inlineTranslation->suspend();
        $to = array($post['email']);
        $templateIdentifier = "product_inquiry_form";

        $this->notify($templateOptions, $templateVars, $from, $to, $templateIdentifier);
    }

    /**
     * @param $post
     * @throws NoSuchEntityException
     * @throws MailException
     */
    public function sendAdminEmail($post)
    {
        $product = $this->getProductBySku($post['sku']);
        $templateOptions = array('area' => Area::AREA_FRONTEND, 'store' => $this->storeManager->getStore()->getId());
        $templateVars = array(
            'customer_name' => $post['name'],
            'message'   => $post['message'],
            'product_name' =>  $product->getName(),
            'product_url' =>   $product->getProductUrl(),
            'product_sku' =>   $post['sku'],
            'email' => $post['email'],
            'mobile_number' => $post['mobile_number']
        );

        // Send Mail
        $from = array('email' => $this->getSenderEmail(), 'name' => $this->getSenderName());
        $this->inlineTranslation->suspend();
        $to = array($this->getSenderEmail());
        $templateIdentifier = "product_inquiry_form_admin";

        $this->notify($templateOptions, $templateVars, $from, $to, $templateIdentifier);
    }

    /**
     * @param $post
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function sendAdminReplyEmail($post)
    {
        $product = $this->getProductBySku($post['sku']);
        $templateOptions = array('area' => Area::AREA_FRONTEND, 'store' => $this->storeManager->getStore()->getId());
        $templateVars = array(
            'customer_name' => $post['name'],
            'message'   => $post['message'],
            'product_name' =>  $product->getName(),
            'product_url' =>   $product->getProductUrl(),
            'admin_message' =>   $post['admin_message']
        );

        // Send Mail
        $from = array('email' => $this->getSenderEmail(), 'name' => $this->getSenderName());
        $this->inlineTranslation->suspend();
        $to = array($post['email']);
        $templateIdentifier = "inquiry_reply";

        $this->notify($templateOptions, $templateVars, $from, $to, $templateIdentifier);
    }

    /**
     * @param $templateOptions
     * @param $templateVars
     * @param $from
     * @param $to
     * @param $templateIdentifier
     * @throws MailException
     */
    public function notify($templateOptions, $templateVars, $from, $to, $templateIdentifier)
    {
        $transport = $this->transportBuilder->setTemplateIdentifier($templateIdentifier)
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom($from)
            ->addTo($to)
            ->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
}
