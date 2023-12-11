<?php
namespace Retailinsights\CategoryScript\Setup;

use \Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
	public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
		$installer = $setup;

		$installer->startSetup();

		$connection = $installer->getConnection();	

        if (version_compare($context->getVersion(), '1.8.0') < 0)  {
            if (!$installer->tableExists('category_script')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('category_script')
                )
                    ->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary'  => true,
                            'unsigned' => true,
                        ],
                        'Id'
                    )
                    ->addColumn(
                        'category_id',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Category Id'
                    )                   
                    ->addColumn(
                        'category_content',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Category Content'
                    )
                   
                    ->addColumn(
                            'created_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Created At'
                    )
                    ->addColumn(
                            'updated_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Updated At'
                    )
                    ->setComment('Category Script');
                $installer->getConnection()->createTable($table);
            }
        }

          
		$installer->endSetup();
	}
}