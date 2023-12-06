<?php

namespace Als\Testimonials\Controller\Adminhtml;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Als\Testimonials\Model\TestimonialsFactory;
use Magento\Framework\Event\ManagerInterface;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Testimonials extends Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    protected $_resultPageFactory;
   protected $_testimonialsFactory;
   protected $_eventManager;
 
    public function __construct(
            Context $context,
            Registry $coreRegistry,
            PageFactory $resultPageFactory,TestimonialsFactory $testimonialsFactory,
            ManagerInterface $eventManager
            ) {
        parent::__construct($context);
        $this->_coreRegistry  = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_testimonialsFactory = $testimonialsFactory;
        $this->_eventManager = $eventManager;
        
    }

    public function execute()
    {
        
    }
    /**
     * Testimonials access rights checking
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Als_Testimonials::manage_testimonials');
    }
}

