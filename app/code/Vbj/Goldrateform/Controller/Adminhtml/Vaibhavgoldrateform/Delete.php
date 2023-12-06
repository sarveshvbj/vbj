<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vbj\Goldrateform\Controller\Adminhtml\Vaibhavgoldrateform;

class Delete extends \Vbj\Goldrateform\Controller\Adminhtml\Vaibhavgoldrateform
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
        $id = $this->getRequest()->getParam('vaibhav_goldrate_form_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Vbj\Goldrateform\Model\VaibhavGoldrateForm::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Vaibhav Goldrate Form.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['vaibhav_goldrate_form_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Vaibhav Goldrate Form to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

