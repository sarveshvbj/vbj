<?php

namespace Als\Testimonials\Controller\Adminhtml\Testimonials;

use Als\Testimonials\Controller\Adminhtml\Testimonials;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Grid extends Testimonials
{
    public function execute()
    {
       
        return $resPage = $this->_resultPageFactory->create();
    }
}

