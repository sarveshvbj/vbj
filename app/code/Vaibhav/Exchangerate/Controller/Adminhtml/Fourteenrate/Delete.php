<?php
declare(strict_types=1);

namespace Vaibhav\Exchangerate\Controller\Adminhtml\Fourteenrate;

class Delete extends \Vaibhav\Exchangerate\Controller\Adminhtml\Fourteenrate
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
        $id = $this->getRequest()->getParam('fourteenrate_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Vaibhav\Exchangerate\Model\Fourteenrate::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Fourteenrate.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['fourteenrate_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Fourteenrate to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

