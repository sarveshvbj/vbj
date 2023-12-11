<?php

namespace Magegadgets\Personalizejewellery\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class Post extends \Magento\Framework\App\Action\Action
{
	public function execute()
    {
		$data = $this->getRequest()->getPostValue();
		unset($data['submit']);
		//var_dump($data);die;
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		if ($data) {

			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

			$model = $objectManager->create('Magegadgets\Personalizejewellery\Model\Personalizejewellery');
			
			
			try{
				$uploader = $this->_objectManager->create(
					'Magento\MediaStorage\Model\File\Uploader',
					['fileId' => 'image']
				);
				$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
				/** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
				$imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
				$uploader->setAllowRenameFiles(true);
				$uploader->setFilesDispersion(true);
				/** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
				$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
					->getDirectoryRead(DirectoryList::MEDIA);
				$result = $uploader->save($mediaDirectory->getAbsolutePath('personalizejewellery_personalizejewellery'));
					if($result['error']==0)
					{
						$data['image'] = 'personalizejewellery_personalizejewellery' . $result['file'];
					}
			} catch (\Exception $e) {
				//unset($data['image']);
            }
			
			$model->setData($data);
			
			try {
				
				$model->save();

				$this->messageManager->addSuccess(__('The Purpose details has been submitted successfully.'));

					$resultRedirect = $this->resultRedirectFactory->create();
					$resultRedirect->setRefererOrBaseUrl();
					return $resultRedirect; 
				} catch (\Magento\Framework\Exception\LocalizedException $e) {
					$this->messageManager->addError($e->getMessage());
				} catch (\RuntimeException $e) {
					$this->messageManager->addError($e->getMessage());
				} catch (\Exception $e) {
					$this->messageManager->addException($e, __('Something went wrong while saving the Purposedata.'));
				}
		}else{
				
			 		$resultRedirect = $this->resultRedirectFactory->create();
					$resultRedirect->setRefererOrBaseUrl();
					return $resultRedirect; 
		}
	}
	
}