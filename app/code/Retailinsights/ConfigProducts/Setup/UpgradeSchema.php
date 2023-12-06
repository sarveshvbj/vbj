<?php
namespace Retailinsights\ConfigProducts\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
	public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
		$installer = $setup;

		$installer->startSetup();

       	if (version_compare($context->getVersion(), '1.0.1', '<')) {
         $installer->getConnection()->addColumn(
               $installer->getTable('quote_item'),
               'config_id',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   /*'nullable' => true,*/
                   'comment' => 'Custom Config Id'
               ]

           );
           $installer->getConnection()->addColumn(
               $installer->getTable('quote_item'),
               'ring_value',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   /*'nullable' => true,*/
                   'comment' => 'Selected Ring Value'
               ]
           );
            $installer->getConnection()->addColumn(
               $installer->getTable('quote_item'),
               'purity_value',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   /*'nullable' => true,*/
                   'comment' => 'Selected Purity Value'
               ]
           );
            $installer->getConnection()->addColumn(
               $installer->getTable('quote_item'),
               'diamond_value',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   /*'nullable' => true,*/
                   'comment' => 'Selected Diamond Value'
               ]
           );
            $installer->getConnection()->addColumn(
               $installer->getTable('quote_item'),
               'custom_price_flag',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   /*'nullable' => true,*/
                   'comment' => 'Custom Price Flag'
               ]
           );
            $installer->getConnection()->addColumn(
               $installer->getTable('quote'),
               'custom_price_flag',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   /*'nullable' => true,*/
                   'comment' => 'Custom Price Flag'
               ]
           );
       }
       
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $installer->getConnection()->addColumn(
               $installer->getTable('custom_config_products'),
               'making_charge',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   'after'     => 'diamond_rate',
                   'nullable' => false,
                   'comment' => 'Making Charge Per Gram'
               ]
           );
       }

        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $installer->getConnection()->addColumn(
               $installer->getTable('quote_item'),
               'custom_final_price',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   'after'     => 'config_id',
                   'nullable' => false,
                   'comment' => 'Final Price of Custom Product'
               ]
           );

             $installer->getConnection()->addColumn(
               $installer->getTable('quote_item'),
               'custom_weight',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   'after'     => 'config_id',
                   'nullable' => false,
                   'comment' => 'Custom Product Weight'
               ]
           );
       }

         if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $installer->getConnection()->addColumn(
               $installer->getTable('quote_item'),
               'product_sku',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   'after'     => 'config_id',
                   'nullable' => false,
                   'comment' => 'Main Product SKU'
               ]
           );
       }
		  
		$installer->endSetup();
	}
}