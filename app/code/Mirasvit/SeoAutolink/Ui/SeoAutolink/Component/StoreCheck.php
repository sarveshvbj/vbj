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


namespace Mirasvit\SeoAutolink\Ui\SeoAutolink\Component;

use Mirasvit\SeoAutolink\Model\ResourceModel\Link\Collection as LinkCollection;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;

class StoreCheck
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var LinkCollection
     */
    private $linkCollection;
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     * @param LinkCollection $linkCollection
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        RequestInterface $request,
        LinkCollection $linkCollection,
        StoreManagerInterface $storeManager
    ) {
        $this->request = $request;
        $this->linkCollection = $linkCollection;
        $this->storeManager = $storeManager;
    }

    /**
     * Check if applied for all stores
     *
     * @return bool
     */
    public function isAppliedAllStores()
    {
        if ($id = $this->request->getParam('id')) {
            return $this->isAllStoresSelected($id);
        } elseif (($stores = $this->storeManager->getStores())
            && is_array($stores)
            && count($stores) < 2) {
                return true;
        }

        return false;
    }

    /**
     * Check if all stores (store_id = 0)
     *
     * @param int $id
     * @return bool
     */
    protected function isAllStoresSelected($id)
    {
        $item = $this->linkCollection
            ->addStoreColumn()
            ->addFieldToFilter('link_id', $id)
            ->getFirstItem();

        $storeIds = $item->getData('store_id');
        if ((is_array($storeIds) && count($storeIds) == 1
                && isset($storeIds[0]) && $storeIds[0] == 0)
            || ($storeIds == 0)) {
            return true;
        }

        return false;
    }
}
