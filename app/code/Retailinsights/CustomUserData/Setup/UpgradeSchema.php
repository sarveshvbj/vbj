<?php
namespace Retailinsights\CustomUserData\Setup;

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

  if (version_compare($context->getVersion(), '1.9.0') < 0)  {
            if (!$installer->tableExists('product_details_data')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('product_details_data')
                )
                    ->addColumn(
                        'sno',
                        Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary'  => true,
                            'unsigned' => true,
                        ],
                        'Sno'
                    )
                    ->addColumn(
                        'brand',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Brand'
                    )                   
                    ->addColumn(
                        'categories',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Categories'
                    )
                    ->addColumn(
                        'category_ids',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Category Ids'
                    )   
                    ->addColumn(
                        'event',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Event'
                    )   
                    ->addColumn(
                        'gross_weight',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Gross Weight'
                    )                    
                    ->addColumn(
                        'image_url',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Image Url'
                    ) 
                    ->addColumn(
                        'name',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Name'
                    ) 
                     ->addColumn(
                        'net_weight',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Net Weight'
                    ) 
                     ->addColumn(
                        'Price',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'price'
                    ) 
                     ->addColumn(
                        'product_url',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Product Url'
                    ) 
                     ->addColumn(
                        'purity',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Purity'
                    )
                     ->addColumn(
                        'sku',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'SKU'
                    )
                     ->addColumn(
                        'style',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Style'
                    )
                     ->addColumn(
                        'weight',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Weight'
                    )
                           
                    ->addColumn(
                            'created_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Created At'
                    )
                    
                    ->setComment('Product Details Data');
                $installer->getConnection()->createTable($table);
            }
        }
         if (version_compare($context->getVersion(), '1.23.0', '<')) {
            $installer->getConnection()->addColumn(
               $installer->getTable('product_details_data'),
               'gcm_image_url',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Gcm Image url'
               ]
           );
           
       }
       

		$installer->endSetup();
	}
}