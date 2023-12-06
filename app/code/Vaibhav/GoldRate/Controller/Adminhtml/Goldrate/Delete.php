<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vaibhav\GoldRate\Controller\Adminhtml\Goldrate;

class Delete extends \Vaibhav\GoldRate\Controller\Adminhtml\Goldrate
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('goldrate_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Vaibhav\GoldRate\Model\Goldrate::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Goldrate.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['goldrate_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Goldrate to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

