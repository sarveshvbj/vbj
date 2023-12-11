<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_GiftCard
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\GiftCard\Model\Product\Type;

class GiftCard extends \Magento\Catalog\Model\Product\Type\AbstractType
{
    const TYPE_ID = "giftcard";
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
    }
}
