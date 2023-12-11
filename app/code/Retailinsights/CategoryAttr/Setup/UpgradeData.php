<?php
namespace Retailinsights\CategoryAttr\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeData implements UpgradeDataInterface
{

    public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory)
    {
            $this->eavSetupFactory = $eavSetupFactory;
    }
     
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
            $setup->startSetup();
     
           
        if (version_compare($context->getVersion(), '1.29.3', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
 
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'mobile_banner', [
                    'type'          => 'varchar',
                    'label'         => 'Mobile Banner',
                    'input'         => 'image',
                    'required'  => false,
                    'sort_order'  => 9,
                    'backend'   => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group'     => 'General Information',
                ]
            );
             $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'mainbanner_alttext', [
                    'type'          => 'varchar',
                    'label'         => 'Main Banner Alt Text',
                    'input'         => 'text',
                    'required'  => false,
                    'sort_order'  => 10,
                    'backend'   => '',
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group'     => 'General Information',
                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'promobanner_alttext', [
                    'type'          => 'varchar',
                    'label'         => 'Promo Banner Alt Text',
                    'input'         => 'text',
                    'required'  => false,
                    'sort_order'  => 11,
                    'backend'   => '',
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group'     => 'General Information',
                ]
            );
             $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'promobanner_alttext2', [
                    'type'          => 'varchar',
                    'label'         => 'Promo Banner Alt Text 2',
                    'input'         => 'text',
                    'required'  => false,
                    'sort_order'  => 11,
                    'backend'   => '',
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group'     => 'General Information',
                ]
            );
             $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'promobanner_alttext3', [
                    'type'          => 'varchar',
                    'label'         => 'Promo Banner Alt Text 3',
                    'input'         => 'text',
                    'required'  => false,
                    'sort_order'  => 11,
                    'backend'   => '',
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group'     => 'General Information',
                ]
            );
             $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'promobanner_alttext4', [
                    'type'          => 'varchar',
                    'label'         => 'Promo Banner Alt Text 4',
                    'input'         => 'text',
                    'required'  => false,
                    'sort_order'  => 11,
                    'backend'   => '',
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group'     => 'General Information',
                ]
            );
             $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'discount', [
                    'type'          => 'varchar',
                    'label'         => 'Discount',
                    'input'         => 'text',
                    'required'  => false,
                    'sort_order'  => 11,
                    'backend'   => '',
                    'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group'     => 'General Information',
                ]
            );
             $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'category_discount',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Category Discount',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );
        }
            $setup->endSetup();
    }
}

?>