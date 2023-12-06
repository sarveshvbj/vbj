<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Als\Testimonials\Controller\Adminhtml\Testimonials;
use Als\Testimonials\Controller\Adminhtml\Testimonials;

class NewAction extends Testimonials
{
    /**
     * Create new news action
     *
     * @return void
     */
   public function execute()
   {
      $this->_forward('edit');
   }
}