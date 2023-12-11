<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Als\Testimonials\Controller\Adminhtml\Testimonials;

use Als\Testimonials\Controller\Adminhtml\Testimonials;

class MassDelete extends Testimonials {

    /**
     * Mass Delete  action
     *
     * @return void
     */
    public function execute() {
        $testimonialsId = $this->getRequest()->getParam('testimonials');
        foreach ($testimonialsId as $testimonialId) {
            try {
                // Delete testimonials
                $model = $this->_testimonialsFactory->create();
                $model->load($testimonialId)->delete();
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        if (count($testimonialsId)) {
            $this->messageManager->addSuccess(
                    __('A total of %1 record(s) were deleted.', count($testimonialsId))
            );
        }

        $this->_redirect('*/*/index');
    }

}
