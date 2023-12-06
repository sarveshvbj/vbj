<?php

namespace Magegadgets\Personalizejewellery\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

		$table = $installer->getConnection()->newTable(
            $installer->getTable('personalize_jewellery')
        )->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'fullname',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Full Name'
        )->addColumn(
            'email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            ['nullable' => false],
            'Email'
        )->addColumn(
            'type',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false],
            'type'
        )->addColumn(
            'details',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '1M',
            ['nullable' => false],
            'Details'
        )->addColumn(
            'image',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            ['nullable' => true],
            'Image'
        )->addColumn(
            'mobile',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            ['nullable' => false],
            'Mobile'
        )->addColumn(
            'budget',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            ['nullable' => true],
            'Budget'
        )->addColumn(
            'product_sku',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            ['nullable' => true],
            'Product Sku'
        )->addIndex(
            $installer->getIdxName('magegadgets_personalize_jewellery', ['id']),
            ['id']
        )->setComment(
            'Magegadgets Personalize Jewellery'
        );
        $installer->getConnection()->createTable($table);
	
        $installer->endSetup();

    }
}