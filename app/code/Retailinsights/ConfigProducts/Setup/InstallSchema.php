<?php

namespace Retailinsights\ConfigProducts\Setup;

use \Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;
        $installer->startSetup();
        $maintable = $installer->getTable('custom_config_products');
        $main_price_table = $installer->getTable('custom_config_products_price');

        if (!$installer->tableExists('custom_config_products'))
        {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('custom_config_products'))
                ->addColumn('id', Table::TYPE_INTEGER, null, ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true, ], 'ID')
                ->addColumn('sku', Table::TYPE_TEXT, 255, ['nullable => false'], 'SKU')
                ->addColumn('size', Table::TYPE_TEXT, 255, ['nullable => false'], 'Size Of Product')
                ->addColumn('diamond_color', Table::TYPE_TEXT, 255, ['nullable => false'], 'Diamond Color')
                ->addColumn('diamond_quality', Table::TYPE_TEXT, 255, ['nullable => false'], 'Diamond Quality')
                ->addColumn('purity', Table::TYPE_TEXT, 255, ['nullable => false'], 'Purity')
                ->addColumn('gross_weight', Table::TYPE_TEXT, 255, ['nullable => false'], 'Gross Weight')
                ->addColumn('net_weight', Table::TYPE_TEXT, 255, ['nullable => false'], 'Net Weight')
                ->addColumn('diamond_caret', Table::TYPE_TEXT, 255, ['nullable => false'], 'Diamond Caret Weight')
                ->addColumn('stone_weight', Table::TYPE_TEXT, 255, ['nullable => false'], 'Stone Weight')
                ->addColumn('wastage', Table::TYPE_TEXT, 255, ['nullable => false'], 'Wastage of Product')
                ->addColumn('metal_rate', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Current Metal rate')
                ->addColumn('stone_rate', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Amount Of Stone')
                ->addColumn('diamond_Rate', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Diamond Rate')
                ->addColumn('dis_metal_price', Table::TYPE_INTEGER, 11, ['nullable => false'], 'Metal Discount percent')
                ->addColumn('dis_making_price', Table::TYPE_INTEGER, 11, ['nullable => false'], 'Making Discount percent')
                ->addColumn('dis_wastage_price', Table::TYPE_INTEGER, 11, ['nullable => false'], 'Wastage Discount percent')
                ->addColumn('dis_diamond_price', Table::TYPE_INTEGER, 11, ['nullable => false'], 'Daimond Discount percent')
                ->addColumn('created_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Created At')
                ->addColumn('updated_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Updated At')
                ->setComment('Config Table');
            $installer->getConnection()
                ->createTable($table);
        }

        if (!$installer->tableExists('custom_config_products_price') && $installer->tableExists('custom_config_products'))
        {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('custom_config_products_price'))
                ->addColumn('id', Table::TYPE_INTEGER, null, ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true, ], 'ID')
                ->addColumn('metal_price', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Product Metal Price')
                ->addColumn('after_dis_metal_price', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Discounted Metal Price')
                ->addColumn('wastage_price', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Wastage Metal Price')
                ->addColumn('after_dis_wastage_price', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Discounted Wastage Price')
                ->addColumn('making_price', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Making Price')
                ->addColumn('after_dis_making_price', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Discounted Making Price')
                ->addColumn('diamond_price', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Diamond Price')
                ->addColumn('after_dis_diamond_price', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Discounted Diamond Price')
                ->addColumn('stone_price', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Stone Price')
                ->addColumn('final_price', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Final Price')
                ->addColumn('special_price', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Special Price')
                ->addColumn('tax', Table::TYPE_DECIMAL, '12,4', ['nullable => false'], 'Tax of Product')
                ->addForeignKey($installer->getFkName('custom_config_products_price', 'id', 'custom_config_products', 'id') , 'id', $installer->getTable('custom_config_products') , 'id', \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE)
                ->setComment('Config Price Table');
            $installer->getConnection()
                ->createTable($table);
        }

        $installer->endSetup();
    }

}

