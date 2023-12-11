<?php
namespace Retailinsights\ProductGamooga\Setup;

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

       
  if (version_compare($context->getVersion(), '1.16.0') < 0)  {
            if (!$installer->tableExists('pricemanager_old')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('pricemanager_old')
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
                        '24K',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        '24K'
                    )  

                    ->addColumn(
                        '22K',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        '22K'
                    )  
                     ->addColumn(
                        '18K',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        '18K'
                    )  
                    ->addColumn(
                        '14K',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        '14K'
                    )  
                    ->addColumn(
                        'hundred',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Hundred'
                    )  
                    ->addColumn(
                        'ninety_two',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Ninety two'
                    )  
                    ->addColumn(
                        'ninety_one',
                         Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Ninety one'
                    )  
                    ->addColumn(
                        'p_hundred',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'p hundred'
                    )  
                    ->addColumn(
                        'ninety_nine',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Ninety nine'
                    )   
                    ->addColumn(
                        'ninety_five',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'ninety five'
                    )  
                    ->addColumn(
                        'usd',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'usd'
                    )  
                    ->addColumn(
                        'tax_customer',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Ninety nine'
                    )  
                    ->addColumn(
                        'tax_customer',
                         Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Ninety nine'
                    )     
                     ->addColumn(
                        'tax_business',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'tax_business'
                    )      
                    ->addColumn(
                            'updated_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Updated At'
                    )
                    
                    ->setComment('Price Manager Old');
                $installer->getConnection()->createTable($table);
            }
        }
        

		$installer->endSetup();
	}
}