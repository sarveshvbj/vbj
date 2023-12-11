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



namespace Mirasvit\SearchMysql\SearchAdapter\Field;

interface FieldInterface
{
    const TYPE_FLAT = 1;
    const TYPE_FULLTEXT = 2;

    /**
     * Get type of index.
     *
     * @return int
     */
    public function getType();

    /**
     * Get ID of attribute.
     *
     * @return int
     */
    public function getAttributeId();

    /**
     * Get field nam.
     *
     * @return string
     */
    public function getColumn();
}
