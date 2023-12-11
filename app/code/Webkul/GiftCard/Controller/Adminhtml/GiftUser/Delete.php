<?php
namespace Webkul\GiftCard\Controller\Adminhtml\GiftUser;

// namespace Iksula\Pricing\Controller\Adminhtml\Location;

use Magento\Backend\App\Action\Context;
// use Iksula\Pricing\Model\ResourceModel\Pricing\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ResponseInterface;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, \Webkul\GiftCard\Helper\Data $helper
        // CollectionFactory $collectionFactory
        )
    {
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if($id){
            $response = $this->helper->deleteGiftVoucher($id);
        }


        if($response){
            if($response['is_error']){
                $this->messageManager->addError(__($response['message']));
            }else{
                $this->messageManager->addSuccess(__($response['message']));
            }
            return $this->_redirect($response['return_url']);
        }
        return $this->_redirect('*/*/index');
    }
}