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



namespace Mirasvit\Seo\Api\Repository;

use Magento\Framework\DataObject;
use Mirasvit\Seo\Api\Data\CanonicalRewriteInterface;

interface CanonicalRewriteRepositoryInterface
{
    /**
     * @return CanonicalRewriteInterface[]|\Mirasvit\Seo\Model\ResourceModel\CanonicalRewrite\Collection
     */
    public function getCollection();

    /**
     * @return CanonicalRewriteInterface
     */
    public function create();

    /**
     * @param int $id
     * @return CanonicalRewriteInterface|DataObject|false
     */
    public function get($id);

    /**
     * @param CanonicalRewriteInterface $model
     * @return CanonicalRewriteInterface
     */
    public function save(CanonicalRewriteInterface $model);

    /**
     * @param CanonicalRewriteInterface $model
     * @return bool
     */
    public function delete(CanonicalRewriteInterface $model);
}