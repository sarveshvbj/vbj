<?php

namespace Magegadgets\Pricemanger\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
//use Magento\Framework\App\Filesystem\DirectoryList;

class Index extends \Magento\Backend\App\Action
{
    public function execute()
    {
		
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
	}
}

?>