<?php
namespace Retailinsights\Reports\Setup;

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
               $installer->getTable('quote'),
               'name',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   /*'nullable' => true,*/
                   'comment' => 'name'
               ]

           );
           $installer->getConnection()->addColumn(
               $installer->getTable('quote'),
               'sku',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   /*'nullable' => true,*/
                   'comment' => 'Sku'
               ]
           );
            $installer->getConnection()->addColumn(
               $installer->getTable('quote'),
               'telephone',
               [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                   'length' => 255,
                   /*'nullable' => true,*/
                   'comment' => 'Customer Telephone'
               ]
           );
       }
      if (version_compare($context->getVersion(), '1.0.2', '<')) {

         $tablename = $installer->getTable('abondoned_customer_cart');
        //if ($installer->getConnection()->isTableExists($tablename)) {
            $table = $installer->getConnection()
                    ->newTable($tablename)
                    ->addColumn(
                            'id', Table::TYPE_INTEGER, null, [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                            ], 'ID'
                    )
                    ->addColumn(
                            'name', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Name'
                    )
                    ->addColumn(
                            'email', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Email'
                    )
                    ->addColumn(
                            'mobile', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Mobile'
                    )
                    ->addColumn(
                            'sku', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'SKU List'
                    )
                    ->addColumn(
                            'products', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Products'
                    )
                    ->addColumn(
                            'last_mail_sent', Table::TYPE_DATETIME, null, ['nullable' => false], 'Last Email Sent'
                    )
                    ->addColumn(
                            'updated_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Updated at'
                    )
                    ->setComment('Abondoned Cart Mail')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);

      }

       if (version_compare($context->getVersion(), '1.0.3', '<')) {

         $tablename = $installer->getTable('zoho_abondoned_cart');
        //if ($installer->getConnection()->isTableExists($tablename)) {
            $table = $installer->getConnection()
                    ->newTable($tablename)
                    ->addColumn(
                            'id', Table::TYPE_INTEGER, null, [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                            ], 'ID'
                    )
                    ->addColumn(
                            'name', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Name'
                    )
                    ->addColumn(
                            'email', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Email'
                    )
                    ->addColumn(
                            'mobile', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Mobile'
                    )
                    ->addColumn(
                            'sku', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'SKU List'
                    )
                    ->addColumn(
                            'status', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Status'
                    )
                    ->addColumn(
                            'last_pass_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Last Zoho Sent'
                    )
                    ->addColumn(
                            'updated_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Updated at'
                    )
                    ->setComment('Abondoned Cart Mail')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);

      }


		  
		$installer->endSetup();
	}
}