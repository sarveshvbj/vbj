<?php

namespace Webkul\GiftCard\Controller\Adminhtml\GiftUser;

use \Magento\Backend\App\Action;
use \Magento\Framework\App\Filesystem\DirectoryList;
use \Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\Filesystem;
use \Magento\MediaStorage\Model\File\UploaderFactory;

class Savebulkitem extends Action
{
    protected $fileSystem;

    protected $uploaderFactory;

    protected $allowedExtensions = ['csv'];

    protected $fileId = 'fileToUpload';

    public function __construct(
        Action\Context $context,
        Filesystem $fileSystem,
        \Webkul\GiftCard\Helper\Data $helper,
        \Webkul\GiftCard\Model\GiftDetail $giftcard,
        \Webkul\GiftCard\Model\GiftUser $giftuser,
        UploaderFactory $uploaderFactory,
        \Magento\Framework\File\Csv $csvProcessor) {
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->csvProcessor = $csvProcessor;
        $this->helper = $helper;
        $this->giftcard = $giftcard;
        $this->giftuser = $giftuser;
        parent::__construct($context);
    }

    public function execute()
    {

        $destinationPath = $this->getDestinationPath();
        try {

            $date = date('d/m/y H:i:s');
            $strtotime = strtotime($date);
            $uploader = $this->uploaderFactory->create(['fileId' => $this->fileId])
            ->setAllowCreateFolders(true)
            ->setAllowedExtensions($this->allowedExtensions)
            ->addValidateCallback('validate', $this, 'validateFile');

            if (!$uploader->save($destinationPath,$strtotime.'.csv')) {
                throw new LocalizedException(
                    __('File cannot be saved to path: $1', $destinationPath)
                    );
            }     

            $importProductRawData = $this->csvProcessor->getData($destinationPath.'/'.$uploader->getUploadedFilename());
            $dataArray = array();

            $connectionGiftCard = $this->giftcard->getResource()->getConnection();
            $tableNameGC = $this->giftcard->getResource()->getMainTable();
            $connectionGiftCard->truncateTable($tableNameGC);

            $connectionGiftUser = $this->giftuser->getResource()->getConnection();
            $tableNameGU = $this->giftuser->getResource()->getMainTable();
            $connectionGiftUser->truncateTable($tableNameGU);


            foreach ($importProductRawData as $key=>$value) {
            	if($key>0){
                    if(!in_array($value[0],$dataArray)){
                    $insertData = array(
                        'code'=>$value[0],
                        'email'=>$value[1],
                        'amount'=>$value[2],
                        'remaining_amt'=>$value[3]);
                    $this->helper->saveGiftVoucher($insertData); 
                }
                array_push($dataArray,$value[0]);
            }
        }
        
        $this->messageManager->addSuccess('File has been successfully uploaded.');
    }catch (\Exception $e) {
        $this->messageManager->addError(__($e->getMessage()));
    }
    return $this->_redirect('*/*/index');
}


public function getDestinationPath()
{
    return $this->fileSystem
    ->getDirectoryWrite(DirectoryList::TMP)
    ->getAbsolutePath('/');
}
}