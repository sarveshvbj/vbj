<?php
namespace Vaibhav\Tryathome\Setup;

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

		if(version_compare($context->getVersion(), '1.0.2', '<')) {
			 if ($installer->tableExists('vaibhav_tryathome')) {
                    $columns = [
                        'source'       => [
                            'type'    => Table::TYPE_TEXT,
                            'length'  => 255,
                            'nullable' => false,
                            'default' => '',
                            'comment' => 'Source'
                        ]
                    ];

                    $tagTable = $installer->getTable('vaibhav_tryathome');
                    foreach ($columns as $name => $definition) {
                        $connection->addColumn($tagTable, $name, $definition);
                    }
                }
		}

		$installer->endSetup();
	}
}