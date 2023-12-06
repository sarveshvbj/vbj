<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Als\Testimonials\Controller\Adminhtml\Testimonials;

use Als\Testimonials\Controller\Adminhtml\Testimonials;


class Save extends Testimonials{

    protected $_uploadedFileName;
    
    public function setUploadedProfiePicName($uploadedFileName)
    {
        $this->_uploadedFileName=$uploadedFileName;
    }
    public function getUploadedProfiePicName()
    {
        return $this->_uploadedFileName;
    }
    /**
     * Create new  action
     *
     * @return void
     */
    public function execute() {
        $isPost = $this->getRequest()->getPost();
        if ($isPost) {
            $model = $this->_testimonialsFactory->create();
            $testimonialsId = $this->getRequest()->getParam('id');
            if ($testimonialsId) {
                $model->load($testimonialsId);
            }
            $formData = $this->getRequest()->getParam('testimonials');
            $model->addData($formData);
            try {
                // Save testimonials
                $model->save();
                // Display success message
                $this->messageManager->addSuccess(__('The testimonial has been saved.'));

                // Check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                    return;
                }

                // Go to grid page
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
            $this->_getSession()->setFormData($formData);
            $this->_redirect('*/*/edit', ['id' => $testimonialsId]);
        }
    }

}
