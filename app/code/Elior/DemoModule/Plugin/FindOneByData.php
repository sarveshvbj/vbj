<?php

namespace Elior\DemoModule\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class FindOneByData
 * @package Aureatelabs\DemoModule\Plugin
 */
class FindOneByData
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * FindOneByData constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param \Magento\UrlRewrite\Model\Storage\AbstractStorage $subject
     * @param $result
     * @param array $data
     * @return mixed
     */
    public function afterFindOneByData(
        \Magento\UrlRewrite\Model\Storage\AbstractStorage $subject,
        $result,
        array $data
    ) {
        if(!empty($result) && !(bool)$this->scopeConfig->getValue('catalog/seo/product_use_categories')
            && $result->getEntityType() == 'product'
            && (strpos(trim($result->getRequestPath(), '/'), '/') !== false)
        ) {
            $requestPathArr = explode('/', $result->getRequestPath());
            if (count($requestPathArr) > 1) {
                $newRequestPath = end($requestPathArr);
                $result->setTargetPath($newRequestPath);
                $result->setRedirectType(301);
            }
        }
        return $result;
    }
}