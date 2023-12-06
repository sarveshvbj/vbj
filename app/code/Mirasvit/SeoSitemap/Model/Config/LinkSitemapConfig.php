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



namespace Mirasvit\SeoSitemap\Model\Config;

class LinkSitemapConfig
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * LinkSitemapConfig constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param null|string $store
     * @return array
     */
    public function getAdditionalLinks($store = null)
    {
        $conf = (string)$this->scopeConfig->getValue(
            'seositemap/frontend/additional_links',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
        $links = [];
        $ar = explode("\n", $conf);
        foreach ($ar as $v) {
            $p = explode(',', $v);
            if (isset($p[0]) && isset($p[1])) {
                $links[] = new \Magento\Framework\DataObject([
                    'url' => trim($p[0]),
                    'title' => trim($p[1]),
                ]);
            }
        }

        return $links;
    }

    /**
     * @param null|string $store
     * @return array
     */
    public function getExcludeLinks($store = null)
    {
        $conf = (string)$this->scopeConfig->getValue(
            'seositemap/frontend/exclude_links',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );

        $links = explode("\n", trim($conf));
        $links = array_map('trim', $links);

        $links = array_diff($links, [0, null]);

        return $links;
    }
}
