<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vbj\Goldrateform\Controller\Adminhtml\Vaibhavgoldrateform;

class Edit extends \Vbj\Goldrateform\Controller\Adminhtml\Vaibhavgoldrateform
{

    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('vaibhav_goldrate_form_id');
        $model = $this->_objectManager->create(\Vbj\Goldrateform\Model\VaibhavGoldrateForm::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Vaibhav Goldrate Form no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('vbj_goldrateform_vaibhav_goldrate_form', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Vaibhav Goldrate Form') : __('New Vaibhav Goldrate Form'),
            $id ? __('Edit Vaibhav Goldrate Form') : __('New Vaibhav Goldrate Form')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Vaibhav Goldrate Forms'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Vaibhav Goldrate Form %1', $model->getId()) : __('New Vaibhav Goldrate Form'));
        return $resultPage;
    }
}

