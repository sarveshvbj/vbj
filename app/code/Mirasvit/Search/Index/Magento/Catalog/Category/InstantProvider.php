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
 * @package   mirasvit/module-search-ultimate
 * @version   2.0.94
 * @copyright Copyright (C) 2023 Mirasvit (https://mirasvit.com/)
 */


declare(strict_types=1);

namespace Mirasvit\Search\Index\Magento\Catalog\Category;

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;
use Mirasvit\Search\Index\AbstractInstantProvider;
use Mirasvit\Search\Service\IndexService;

class InstantProvider extends AbstractInstantProvider
{
    private $categoryFactory;

    private $storeManager;

    public function __construct(
        CategoryFactory $categoryFactory,
        StoreManagerInterface $storeManager,
        IndexService $indexService
    ) {
        $this->categoryFactory    = $categoryFactory;
        $this->storeManager       = $storeManager;

        parent::__construct($indexService);
    }

    public function getItems(int $storeId, int $limit, int $page = 0): array
    {
        $items = [];

        /** @var \Magento\Catalog\Model\Category $category */
        foreach ($this->getCollection($limit) as $category) {
            $items[] = $this->mapCategory($category, $storeId);
        }

        return $items;
    }

    /**
     * @param \Magento\Catalog\Model\Category $category
     * @param int                             $storeId
     *
     * @return array
     */
    private function mapCategory($category, int $storeId): array
    {
        $category = $this->categoryFactory->create()->setStoreId($storeId)
            ->load($category->getId());

        return [
            'name' => $this->getFullPath($category, $storeId),
            'url'  => $category->getUrl(),
        ];
    }

    private function getFullPath(CategoryInterface $category, int $storeId): string
    {
        $store  = $this->storeManager->getStore($storeId);
        $rootId = $store->getRootCategoryId();

        $result = [
            $category->getName(),
        ];

        do {
            if (!$category->getParentId()) {
                break;
            }
            $category = $this->categoryFactory->create()->setStoreId($storeId)->load($category->getParentId());

            if (!$category->getIsActive() && $category->getId() != $rootId) {
                break;
            }

            if ($category->getId() != $rootId) {
                $result[] = $category->getName();
            }
        } while ($category->getId() != $rootId);

        $result = array_reverse($result);

        return implode('<i>›</i>', $result);
    }

    public function getSize(int $storeId): int
    {
        return $this->getCollection(0)->getSize();
    }

    public function map(array $documentData, int $storeId): array
    {
        foreach ($documentData as $entityId => $itm) {
            $entity = ObjectManager::getInstance()->create('\Magento\Catalog\Model\Category')
                ->load($entityId);

            $map = $this->mapCategory($entity, $storeId);

            $documentData[$entityId]['_instant'] = $map;
        }

        return $documentData;
    }
}
