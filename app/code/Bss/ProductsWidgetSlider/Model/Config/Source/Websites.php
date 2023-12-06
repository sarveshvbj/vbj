<?php
/**
 * Bss Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   Bss
 * @package    Bss_ProductsWidgetSlider
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 Bss Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\ProductsWidgetSlider\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Websites implements ArrayInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface;
     */
    protected $storeManager;

    /**
     * Websites constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $storeManagerDataList = $this->storeManager->getWebsites();
        $options = [];
        $options[] = ['label' => __('All Website'), 'value' => 0];
        foreach ($storeManagerDataList as $key => $value) {
            $options[] = ['label' => __($value['name']), 'value' => $key];
        }

        return $options;
    }
}
