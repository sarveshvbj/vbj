<?php

/**
 * Class for NoFollowIndex InstallData
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Setup;

use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
    protected $_helper;

    public function __construct(
      EavSetupFactory $eavSetupFactory,
      \FME\NoFollowIndex\Helper\Data $helper
    )
    {
        $this->_helper = $helper;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'nofollowindex_enable',
            [
              'group' => 'nofollowindex',
              'label' => 'Enable',
              'type'  => 'int',
              'input' => 'select',
              'source' => Boolean::class,
              'required' => false,
              'sort_order' => 10000,
              'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
              'used_in_product_listing' => true,
              'visible_on_front' => false
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'nofollowindex_followvalue',
            [
                'group' => 'nofollowindex',
                'label' => 'Follow Value',
                'type'  => 'text',
                'input' => 'select',
                'source' => 'FME\NoFollowIndex\Model\Config\Source\Followvalue',
                'required' => false,
                'sort_order' => 10011,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => false
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'nofollowindex_indexvalue',
            [
              'group' => 'nofollowindex',
              'label' => 'Index Value',
              'type'  => 'text',
              'input' => 'select',
              'source' => 'FME\NoFollowIndex\Model\Config\Source\Indexvalue',
              'required' => false,
              'sort_order' => 10022,
              'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
              'used_in_product_listing' => true,
              'visible_on_front' => false
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'nofollowindex_enableonproducts',
            [
              'group' => 'nofollowindex',
              'label' => 'Enable On Products',
              'type'  => 'int',
              'input' => 'select',
              'source' => Boolean::class,
              'required' => false,
              'sort_order' => 10033,
              'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
              'used_in_product_listing' => true,
              'visible_on_front' => false
            ]
        );
        $eavSetup->addAttribute(
           \Magento\Catalog\Model\Category::ENTITY,
           'nofollowindex_priority',
           [
               'group' => 'nofollowindex',
               'label' => 'Priority (In case of Enable On Products)',
               'input' => 'text',
               'type' => 'varchar',
               'required' => false,
               'sort_order' => 10044,
               'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
               'used_in_product_listing' => true,
               'visible_on_front' => false
           ]
        );
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $setup->endSetup();
    }
}
