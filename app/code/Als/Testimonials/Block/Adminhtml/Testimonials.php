<?php
 
namespace Als\Testimonials\Block\Adminhtml;
 
use Magento\Backend\Block\Widget\Grid\Container;
 
class Testimonials extends Container
{
   /**
     * Constructor
     *
     * @return void
     */
   protected function _construct()
    {
        $this->_controller = 'adminhtml_testimonials';
        $this->_blockGroup = 'Als_Testimonials';
        $this->_headerText = __('Manage Testimonials');
        $this->_addButtonLabel = __('Add Testimonial');
        parent::_construct();
    }
}