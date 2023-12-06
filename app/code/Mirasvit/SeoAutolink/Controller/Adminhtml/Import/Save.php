<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-seo
 * @version   2.6.1
 * @copyright Copyright (C) 2023 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\SeoAutolink\Controller\Adminhtml\Import;

class Save extends \Mirasvit\SeoAutolink\Controller\Adminhtml\Import
{
    /** @var string[] */
    private $targets = ['_self', '_blank', '_parent', '_top'];

    /**
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
        $uploader = $this->fileUploaderFactory->create(['fileId' => 'file']);
        $uploader->setAllowedExtensions(['csv']);
        $uploader->setAllowRenameFiles(true);
        $path = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
                ->getAbsolutePath().'/import';
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        try {
            $existingStoreIds = [0];
            $stores = $this->storeManager->getStores();
            foreach ($stores as $store) {
                $existingStoreIds[] = $store->getId();
            }

            $result = $uploader->save($path);
            $fullPath = $result['path'].'/'.$result['file'];

            $file = new \Magento\Framework\Filesystem\Driver\File;
            $file->isFile($fullPath);
            $csv = new \Magento\Framework\File\Csv($file);
            $data = $csv->getData($fullPath);

            $items = [];
            if (count($data) > 1) {
                for ($i = 1; $i < count($data); ++$i) {
                    $item = [];
                    for ($j = 0; $j < count($data[0]); ++$j) {
                        if (isset($data[$i][$j]) && trim($data[$i][$j]) != '') {
                            $item[strtolower($data[0][$j])] = $data[$i][$j];
                        }
                    }
                    $items[] = $item;
                }
            }

            $resource = $this->resource;
            $writeConnection = $resource->getConnection('core_write');
            $tableLink = $resource->getTableName('mst_seoautolink_link');
            $tableLinkStore = $resource->getTableName('mst_seoautolink_link_store');
            $i = 0;
            foreach ($items as $item) {
                if (!isset($item['keyword'])) {
                    continue;
                }

                $item   = new \Magento\Framework\DataObject($item);
                $target = $item->getUrlTarget() && in_array($item->getUrlTarget(), $this->targets)
                    ? $item->getUrlTarget()
                    : '_self';

		        $query = "REPLACE {$tableLink} SET
		            link_id = '".(int)$item->getLinkId()."',
                    keyword = '".addslashes((string)$item->getKeyword())."',
                    url = '".addslashes((string)$item->getUrl())."',
                    url_title = '".addslashes((string)$item->getUrlTitle())."',
                    url_target = '".$target."',
                    is_nofollow = '".(int) ($item->getIsNofollow())."',
                    max_replacements = '".(int) ($item->getMaxReplacements())."',
                    sort_order = '".(int) ($item->getSortOrder())."',
                    occurence = '".(int) ($item->getOccurence())."',
                    is_active = '".(int) ($item->getIsActive())."',
                    created_at = '".(date('Y-m-d H:i:s'))."',
                    updated_at = '".(date('Y-m-d H:i:s'))."';
                ";

		        $writeConnection->query($query);

                $lastInsertId = $item->getLinkId() ?: $writeConnection->lastInsertId();
                $storeIds     = ($item->getStoreId()) ? explode('/', (string)$item->getStoreId()) : [0];
                $storeIds     = array_intersect($storeIds, $existingStoreIds);

                if (!count($storeIds) || in_array(0, $storeIds)) {
                        $storeIds = [0];
                }

                foreach ($storeIds as $storeId) {
                    $query = "REPLACE {$tableLinkStore} SET
                            store_id = '" . $storeId . "',
                            link_id = " . $lastInsertId . ";";
                    $writeConnection->query($query);
                }

                ++$i;
            }

            $this->messageManager->addSuccess(''.$i.' records were inserted or updated');
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}
