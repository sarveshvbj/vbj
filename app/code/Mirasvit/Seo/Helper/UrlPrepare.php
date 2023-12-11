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



namespace Mirasvit\Seo\Helper;

class UrlPrepare extends \Magento\Framework\App\Helper\AbstractHelper
{

    const REPLACEMENT = '[http]';
    /**
     * @param string $url
     * @return string
     */
    public function deleteDoubleSlash($url)
    {
        $url = str_replace('://', self::REPLACEMENT, $url);
        $url = str_replace('//', '/', $url);
        $url = str_replace(self::REPLACEMENT, '://', $url);

        return $url;
    }
}
