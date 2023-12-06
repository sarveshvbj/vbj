<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Als\Testimonials\Controller\Adminhtml\Testimonials;

use Als\Testimonials\Controller\Adminhtml\Testimonials;

class Delete extends Testimonials {

    /**
     * Create new  action
     *
     * @return void
     */
    public function execute() {
        $testimonialsId = $this->getRequest()->getParam('id');
       
        if ($testimonialsId) {
            $model = $this->_testimonialsFactory->create();
             $model->load($testimonialsId);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This testimonial no longer exists.'));
            }else
            {
                
            }try {
                  // Delete news
                  $model->delete();
                  $this->messageManager->addSuccess(__('The testimonial has been deleted.'));
 
                  // Redirect to grid page
                  $this->_redirect('*/*/');
                  return;
               } catch (\Exception $e) {
                   $this->messageManager->addError($e->getMessage());
                   $this->_redirect('*/*/edit', ['id' => $model->getId()]);
               }

        }
    }

}
