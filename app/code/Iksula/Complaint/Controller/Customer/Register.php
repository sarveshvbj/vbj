<?php 
namespace Iksula\Complaint\Controller\Customer;
use Magento\Framework\Mail\Template\TransportBuilder;  
class Register extends \Magento\Framework\App\Action\Action { 

	protected $customerSession;

	public function __construct(
		\Magento\Framework\App\Action\Context $context, 
		\Magento\Customer\Model\Session $customerSession, 
		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
		TransportBuilder $transportBuilder
		) {
		$this->resultPageFactory = $resultPageFactory;
		$this->customerSession = $customerSession;
		$this->_transportBuilder = $transportBuilder;
		parent::__construct($context);
	}
	public function execute() {
       
       //echo "bfjkdsfhgjkhj";
		
    }
     
	}  
?>       