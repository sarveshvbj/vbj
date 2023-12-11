<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_AutoRelated
 * @copyright   Copyright (c) 2017-2018 Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\AutoRelated\Helper;

use Magento\Catalog\Model\SessionFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Core\Helper\AbstractData;

/**
 * Class Data
 * @package Mageplaza\FrequentlyBought\Helper
 */
class Data extends AbstractData
{
    const CONFIG_MODULE_PATH = 'autorelated';

    /**
     * @var \Magento\Catalog\Model\SessionFactory
     */
    protected $catalogSession;

    /**
     * Data constructor.
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param SessionFactory $catalogSession
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        SessionFactory $catalogSession
    )
    {
        $this->catalogSession = $catalogSession;

        parent::__construct($context, $objectManager, $storeManager);
    }

    /**
     * @param $click
     * @param $impression
     * @return string
     */
    public function getCtr($click, $impression)
    {
        $ctr = $click / $impression * 100;

        return sprintf('%.2f', $ctr) . '%';
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getConfigDisplay($storeId = null)
    {
        return $this->getConfigGeneral('display_style', $storeId);
    }

    /**
     * @param $layout
     * @param $params
     * @param bool $isAjax
     * @return bool|string
     */
    public function getRelatedProduct($layout, $params, $isAjax = true)
    {
        if (!$this->isEnabled()) {
            return false;
        }
        //Get $pageType, $id, $ruleIds
        $result               = [];
        $autoRelatedModelRule = $this->objectManager->create('Mageplaza\AutoRelated\Model\RuleFactory');
        $ruleIds              = [];
        $id                   = '';

        if ($params['module'] == 'catalog' && $params['controller'] == 'product' && $params['action'] == 'view' && $params['product_id']) {
            $pageType = 'product';
            $id       = $params['product_id'];
        } else if ($params['module'] == 'catalog' && $params['controller'] == 'category' && $params['action'] == 'view' && $params['category_id']) {
            $pageType = 'category';
            $id       = $params['category_id'];
        } else if ($params['module'] == 'checkout' && $params['controller'] == 'cart' && $params['action'] == 'index') {
            $pageType = 'cart';
        } else {
            $pageType = 'cms';
            if (isset($params['ruleIds'])) {
                $ruleIds = array_filter(explode(',', $params['ruleIds']));
                foreach ($ruleIds as $ruleId) {
                    $ruleParam = $autoRelatedModelRule->create()->load($ruleId);
                    if ($ruleParam->hasChild()) {
                        $ruleChild = $ruleParam->getChild();
                        $ruleIds[] = $ruleChild['rule_id'];
                    }
                    if ($ruleParam->getParentId()) {
                        $ruleIds[] = $ruleParam->getParentId();
                    }
                }
            }
        }
        try {
            /** @var \Mageplaza\AutoRelated\Model\ResourceModel\Rule $autoRelatedRule */
            $autoRelatedRule = $autoRelatedModelRule->create()->getResource();
            $data            = $autoRelatedRule->getProductList($pageType, $id, $ruleIds);

            if (!empty($data)) {
                $i = 0;
                foreach ($data as $location => $infos) {
                    $html                     = '';
                    $result['data'][$i]['id'] = $location;

                    foreach ($infos as $info) {
                        $productIds = $info['product_ids'];
                        $rule       = $info['rule'];
                        $ruleId     = $rule['rule_id'];
                        if (!empty($productIds)) {
                            if(!$isAjax || ($isAjax && isset($params['isAjax']))) {
                                $html .= $layout->createBlock('Mageplaza\AutoRelated\Block\Product\ProductList\ProductList')
                                    ->setTemplate('Mageplaza_AutoRelated::product/list/items.phtml')
                                    ->setProductIds($productIds)
                                    ->setRequestDefault($params)
                                    ->setRule($rule)
                                    ->toHtml();
                            }

                            if($isAjax) {
                                $autoRelatedRule->updateImpression($pageType, $id, $ruleId);
                            }
                        }
                    }
                    $result['data'][$i]['content'] = $html;
                    $i++;
                }
            }
            if (!empty($result)) {
                $result['status'] = true;
                $this->catalogSession->create()->unsAutoRelated();
            }
        } catch (\Exception $e) {
            $result['status'] = false;
            $this->_logger->critical($e);
        }

        return self::jsonEncode($result);
    }
}
