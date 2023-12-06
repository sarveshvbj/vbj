<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category  BSS
 * @package   Bss_OneStepCheckout
 * @author    Extension Team
 * @copyright Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license   http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\OneStepCheckout\Plugin\Model\Sales;

/**
 * Class CreditmemoRepository
 *
 * @package Bss\OneStepCheckout\Plugin\Model\Sales
 */
class CreditmemoRepository
{
    /**
     * @var \Bss\OneStepCheckout\Helper\Data
     */
    private $dataHelper;

    /**
     * @var ExtensionAttributeHelper
     */
    private $attributeHelper;

    /**
     * CreditmemoRepository constructor.
     *
     * @param \Bss\OneStepCheckout\Helper\Data $dataHelper
     * @param ExtensionAttributeHelper $attributeHelper
     */
    public function __construct(
        \Bss\OneStepCheckout\Helper\Data $dataHelper,
        ExtensionAttributeHelper $attributeHelper
    ) {
        $this->dataHelper = $dataHelper;
        $this->attributeHelper = $attributeHelper;
    }

    /**
     * After get entity
     *
     * @param \Magento\Sales\Api\CreditmemoRepositoryInterface $subject
     * @param \Magento\Sales\Api\Data\CreditmemoInterface $entity
     * @return Object
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        \Magento\Sales\Api\CreditmemoRepositoryInterface $subject,
        \Magento\Sales\Api\Data\CreditmemoInterface $entity
    ) {
        $entity = $this->attributeHelper->executeSet($entity, $this->dataHelper);
        return $entity;
    }
}
