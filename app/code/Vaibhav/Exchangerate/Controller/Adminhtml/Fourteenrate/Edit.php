<?php
declare(strict_types=1);

namespace Vaibhav\Exchangerate\Controller\Adminhtml\Fourteenrate;

class Edit extends \Vaibhav\Exchangerate\Controller\Adminhtml\Fourteenrate
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
        $id = $this->getRequest()->getParam('fourteenrate_id');
        $model = $this->_objectManager->create(\Vaibhav\Exchangerate\Model\Fourteenrate::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Fourteenrate no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('vaibhav_exchangerate_fourteenrate', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Fourteenrate') : __('New Fourteenrate'),
            $id ? __('Edit Fourteenrate') : __('New Fourteenrate')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Fourteenrates'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Fourteenrate %1', $model->getId()) : __('New Fourteenrate'));
        return $resultPage;
    }
}

