<?php
namespace Retailinsights\Settings\Controller\Index;

class Display extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
		if(isset($_GET["product_id"])){
			$product_id = $_GET["product_id"];
			$_SESSION["diamond_settings_diamond"] = $product_id;
		}


		$resultPage = $this->_pageFactory->create();
        return $resultPage;
	}
}