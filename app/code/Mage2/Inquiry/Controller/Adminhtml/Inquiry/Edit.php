<?php
/**
 * Copyright © Mage2 Developer, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mage2\Inquiry\Controller\Adminhtml\Inquiry;

use Mage2\Inquiry\Controller\Adminhtml\Inquiry;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Mage2\Inquiry\Model\InquiryFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Inquiry implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var InquiryFactory
     */
    private $inquiryFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        InquiryFactory $inquiryFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->inquiryFactory = $inquiryFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit inquiry
     *
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('inquiry_id');
        $model = $this->inquiryFactory->create();

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This inquiry no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('mage2_inquiry', $model);

        // 5. Build edit form
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Inquiry') : __('New Inquiry'),
            $id ? __('Edit Inquiry') : __('New Inquiry')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Inquiry'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getName() : __('New Inquiry'));
        return $resultPage;
    }
}
