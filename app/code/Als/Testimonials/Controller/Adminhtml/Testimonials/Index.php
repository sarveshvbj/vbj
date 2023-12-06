<?php

namespace Als\Testimonials\Controller\Adminhtml\Testimonials;

use Als\Testimonials\Controller\Adminhtml\Testimonials;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Index extends Testimonials
{
    public function execute()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
        $resPage = $this->_resultPageFactory->create();
        //$modelTestimonials = $this->_modelTestimonialsFactory->create();
        //$testimonialsCollection = $modelTestimonials->getCollection();
        ///var_dump($testimonialsCollection->getData());
        $resPage->setActiveMenu('Als_Testimonials::testimonial_content');
        $resPage->getConfig()->getTitle()->prepend(__("Testimonials"));
        return $resPage;
    }
}

