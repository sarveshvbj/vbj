<?php

namespace Magegadgets\Personalizejewellery\Controller\Adminhtml\customizeexistingdesign;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPagee;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magegadgets_Personalizejewellery::personalizejewellery');
        $resultPage->addBreadcrumb(__('Magegadgets'), __('Magegadgets'));
        $resultPage->addBreadcrumb(__('Manage item'), __('Personalize Your Jewellery: Customize Existing Design'));
        $resultPage->getConfig()->getTitle()->prepend(__('Personalize Your Jewellery: Customize Existing Design'));

        return $resultPage;
    }
}
?>