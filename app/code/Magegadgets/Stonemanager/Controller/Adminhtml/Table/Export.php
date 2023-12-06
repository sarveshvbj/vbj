<?php
/**
 * MageGadgets Extensions
 * Stonemanager Extension
 * 
 * @package    		Magegadgets_Stonemanager
 * @copyright  		Copyright (c) 2017 MageGadgets Extensions (http://www.magegadgets.com/)
 * @contactEmail   	support@magegadgets.com
*/

namespace Magegadgets\Stonemanager\Controller\Adminhtml\Table;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Backend\App\Response\Http\FileFactory;
use Magegadgets\Stonemanager\Api\StonemanagerTableInterface;

class Export extends Action
{
    protected $stonemanagerTableInterface;
    protected $fileFactory;

    public function __construct(
        Action\Context $context,
        StonemanagerTableInterface $stonemanagerTableInterface,
        FileFactory $fileFactory
    ) {
        parent::__construct($context);

        $this->stonemanagerTableInterface = $stonemanagerTableInterface;
        $this->fileFactory = $fileFactory;
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
		
        $csvFile = $this->stonemanagerTableInterface->getTableAsCsv();
        return $this->fileFactory->create(
            'stone-information.csv',
            $csvFile,
            DirectoryList::VAR_DIR,
            'text/csv',
            null
        );
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Payment::payment');
    }
}
