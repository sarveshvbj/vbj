<?php

namespace Magegadgets\Loosediamonds\Setup;

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
            $installer->getTable('loose_diamonds')
        )->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true,'auto_increment' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'item_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Item Id'
        )->addColumn(
            'shape',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            '11',
            ['nullable' => false],
            'Shape'
        )->addColumn(
            'carats',
            \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
            '11',
            ['nullable' => false],
            'Carats'
        )->addColumn(
            'certificate',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            ['nullable' => false],
            'Certificate'
        )->addColumn(
            'certificate_link',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            ['nullable' => true],
            'Certificate Link'
        )->addColumn(
            'certificate_no',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            ['nullable' => false],
            'Certificate No'
        )->addColumn(
            'color',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            '11',
            ['nullable' => true],
            'Color'
        )->addColumn(
            'clarity',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            '11',
            ['nullable' => true],
            'Clarity'
		)->addColumn(
            'cut',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            '11',
            ['nullable' => true],
            'Cut'
		)->addColumn(
            'polish',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            '11',
            ['nullable' => true],
            'Polish'
		)->addColumn(
            'symmetry',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            '11',
            ['nullable' => true],
            'Symmetry'
		)->addColumn(
            'fluorescence',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            '11',
            ['nullable' => true],
            'Fluorescence'
		)->addColumn(
            'measurement',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            ['nullable' => true],
            'Measurement'
		)->addColumn(
            'table',
            \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
            '11',
            ['nullable' => true],
            'Table'
		)->addColumn(
            'depth',
            \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
            '11',
            ['nullable' => true],
            'Depth'
		)->addColumn(
            'price',
            \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
            '11',
            ['nullable' => true],
            'Price'
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