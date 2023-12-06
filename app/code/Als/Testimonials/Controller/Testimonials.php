<?php
 
namespace Als\Testimonials\Controller;
 
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;
use Als\Testimonials\Helper\Data;
use Als\Testimonials\Model\TestimonialsFactory;
 
class Testimonials extends Action
{
   /**
    * @var \Magento\Framework\View\Result\PageFactory
    */
   protected $_pageFactory;
 
   /**
    * @var \Als\Testimonials\Helper\Data
    */
   protected $_dataHelper;
 
   /**
    * @var \Als\Testimonials\Model\TestimonialsFactory
    */
   protected $_testimonialsFactory;
 
   /**
    * @param Context $context
    * @param PageFactory $pageFactory
    * @param Data $dataHelper
    * @param TestimonialsFactory $testimonialsFactory
    */
   public function __construct(
      Context $context,
      PageFactory $pageFactory,
      Data $dataHelper,
      TestimonialsFactory $testimonialsFactory
   ) {
      parent::__construct($context);
      $this->_pageFactory = $pageFactory;
      $this->_dataHelper = $dataHelper;
      $this->_testimonialsFactory = $testimonialsFactory;
   }
    public function execute()
    {
    
    }
   /**
     * Dispatch request
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
       // Check this module is enabled in frontend
      if ($this->_dataHelper->isEnabledInFrontend()) {
         $result = parent::dispatch($request);
          return $result;
      } else {
         $this->_forward('noroute');
      }
    }
}