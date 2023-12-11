<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_StoreOptimization
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\StoreOptimization\Plugin\Catalog\Block\Product\ProductList;

class Toolbar
{

    public function beforeGetTemplateFile(\Magento\Catalog\Block\Product\ProductList\Toolbar $subject, $template = null)
    {
        if ($template == 'Magento_Catalog::product/list/toolbar/limiter.phtml') {
            $template = 'Webkul_StoreOptimization/catalog/product/lazyloader.phtml';
        }
        return [$template];
    }
}
