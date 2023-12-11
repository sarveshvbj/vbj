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



namespace Mirasvit\SeoSitemap\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Mirasvit\SeoSitemap\Model\Config
     */
    protected $config;

    /**
     * @var \Mirasvit\SeoSitemap\Service\SeoSitemapUrlService
     */
    protected $seoSitemapUrlService;

    /**
     * @var \Magento\Framework\App\Helper\Context
     */
    protected $context;

    /**
     * @var \Mirasvit\SeoSitemap\Model\Config\LinkSitemapConfig
     */
    protected $linkSitemapConfig;
    /**
     * @param \Mirasvit\SeoSitemap\Model\Config $config
     * @param \Mirasvit\SeoSitemap\Service\SeoSitemapUrlService $seoSitemapUrlService
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Mirasvit\SeoSitemap\Model\Config $config,
        \Mirasvit\SeoSitemap\Service\SeoSitemapUrlService $seoSitemapUrlService,
        \Mirasvit\SeoSitemap\Model\Config\LinkSitemapConfig $linkSitemapConfig,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->config               = $config;
        $this->seoSitemapUrlService = $seoSitemapUrlService;
        $this->context              = $context;
        $this->linkSitemapConfig    = $linkSitemapConfig;

        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getSitemapTitle()
    {
        return $this->config->getFrontendSitemapH1();
    }

    /**
     * @return string
     */
    public function getSitemapUrl()
    {
        return $this->seoSitemapUrlService->getBaseUrl();
    }

    /**
     * @param string     $stringVal
     * @param array      $patternArr
     * @param bool|false $caseSensativeVal
     *
     * @return bool
     */
    public function checkArrayPattern($stringVal, $patternArr, $caseSensativeVal = false)
    {
        if (!is_array($patternArr)) {
            return false;
        }
        foreach ($patternArr as $patternVal) {
            if ($this->checkPattern($stringVal, $patternVal, $caseSensativeVal)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function checkIsUrlExcluded($url)
    {
        $excludedLinks = $this->linkSitemapConfig->getExcludeLinks();
        return $this->checkArrayPattern($url, $excludedLinks);
    }

    /**
     * @param string $urlWithHost
     *
     * @return mixed|string
     */
    public function removeHostUrl($urlWithHost)
    {
        $parts = parse_url($urlWithHost);
        $url   = $parts['path'];
        $url   = str_replace('index.php/', '', $url);
        $url   = str_replace('index.php', '', $url);

        if (strpos($url, '/') !== 0) {
            $url = '/' . $url; //need this so exclude patterns will work the same way for Frontend and XML sitemaps
        }

        if (isset($parts['query'])) {
            $url .= '?' . $parts['query'];
        }

        return $url;
    }

    /**
     * @param string     $url
     * @param string     $pattern
     * @param bool|false $caseSensative
     *
     * @return bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity) 
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function checkPattern($url, $pattern, $caseSensative = false)
    {
        $string = $this->removeHostUrl($url);

        if (!$caseSensative) {
            $string  = strtolower($string);
            $pattern = strtolower($pattern);
        }

        $parts = explode('*', $pattern);
        $index = 0;

        $shouldBeFirst = true;

        foreach ($parts as $part) {
            if ($part == '') {
                $shouldBeFirst = false;
                continue;
            }

            $index = strpos($string, $part, $index);

            if ($index === false) {
                return false;
            }

            if ($shouldBeFirst && $index > 0) {
                return false;
            }

            $shouldBeFirst = false;
            $index         += strlen($part);
        }

        if (count($parts) == 1) {
            return $string == $pattern;
        }

        $last = end($parts);
        if ($last == '') {
            return true;
        }

        if (strrpos($string, $last) === false) {
            return false;
        }

        if (strlen($string) - strlen($last) - strrpos($string, $last) > 0) {
            return false;
        }

        return true;
    }
}
