<?php
namespace Retailinsights\Coupons\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
	public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
		$installer = $setup;

		$installer->startSetup();

		if(version_compare($context->getVersion(), '1.2.1', '<')) {
			$installer->getConnection()->addColumn(
	            $installer->getTable('salesrule'),
	            'phone_number',
	            [
	                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
	                'length' => 32,
	                'nullable' => true,
	                'default' => '',
	                'comment' => 'Phone Number'
	            ]
	        	
			);
		}
		$installer->endSetup();
	}
}