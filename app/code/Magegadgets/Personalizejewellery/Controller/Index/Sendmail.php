<?php
namespace Magegadgets\Personalizejewellery\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Request\DataPersistorInterface;


class Sendmail extends \Magento\Framework\App\Action\Action
{	
	
/**
* Recipient email config path
*/
const XML_PATH_EMAIL_RECIPIENT = 'contact/email/recipient_email';
/**
* @var \Magento\Framework\Mail\Template\TransportBuilder
*/
protected $_transportBuilder;

/**
* @var \Magento\Framework\Translate\Inline\StateInterface
*/
protected $inlineTranslation;

/**
* @var \Magento\Framework\App\Config\ScopeConfigInterface
*/
protected $scopeConfig;

/**
* @var \Magento\Store\Model\StoreManagerInterface
*/
protected $storeManager;
/**
* @var \Magento\Framework\Escaper
*/
protected $_escaper;

/**
* @param \Magento\Framework\App\Action\Context $context
* @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
* @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
* @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
* @param \Magento\Store\Model\StoreManagerInterface $storeManager
*//*
public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
    \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\Escaper $escaper
) {
    //parent::__construct($context);
	parent::__construct($context,$transportBuilder,$inlineTranslation, $scopeConfig );
    $this->_transportBuilder = $transportBuilder;
    $this->inlineTranslation = $inlineTranslation;
    $this->scopeConfig = $scopeConfig;
    $this->storeManager = $storeManager;
    $this->_escaper = $escaper;
}*/
	
	public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
   		\Magento\Framework\Escaper $escaper
    ) {
        parent::__construct($context);
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
		$this->storeManager = $storeManager;
    	$this->_escaper = $escaper;

    }

/**
 * Post user question
 *
 * @return void
 * @throws \Exception
 */
public function execute()
{
    $post = $this->getRequest()->getPostValue();
    if (!$post) {
        $this->_redirect('*/*/');
        return;
    }

    $this->inlineTranslation->suspend();

    try {
        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($post);
        $error = false;

        $sender = [
            'name' => $this->_escaper->escapeHtml($post['fname']),
            'email' => $this->_escaper->escapeHtml($post['email']),
        ];

        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE; 
        $transport = $this->_transportBuilder
            ->setTemplateIdentifier(1) // this code we have mentioned in the email_templates.xml
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($sender)
            ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
            ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
            $this->messageManager->addSuccess(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );
		echo "Thanks";
            //$this->_redirect('*/*/');
            //return;
    } catch (\Exception $e) {
		echo "hello";
        $this->inlineTranslation->resume();
        $this->messageManager->addError(__('We can\'t process your request right now. Sorry, that\'s all we know.'.$e->getMessage())
        );
        //$this->_redirect('*/*/');
        return;
    }
}

}


