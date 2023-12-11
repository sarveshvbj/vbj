<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Als\Testimonials\Controller\Adminhtml\Testimonials;
use Als\Testimonials\Controller\Adminhtml\Testimonials;

class Edit extends Testimonials
{
    /**
     * Create new  action
     *
     * @return void
     */
   public function execute()
   {
       $testimonialsId = $this->getRequest()->getParam('id');
       $model = $this->_testimonialsFactory->create();
 
        if ($testimonialsId) {
            $model->load($testimonialsId);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This testimonial no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
 
        // Restore previously entered form data from session
        $data = $this->_session->getTestimonialData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        
        $this->_coreRegistry->register('testimonials_testimonials', $model);
 
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Als_Testimonials::testimonial_content');
        $resultPage->getConfig()->getTitle()->prepend(__('Testimonials'));
 
        return $resultPage;
   }
}