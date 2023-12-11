<?php
namespace Magegadgets\Videoform\Setup;

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

		if(version_compare($context->getVersion(), '1.0.1', '<')) {
			 if ($installer->tableExists('video_form')) {
                    $columns = [
                        'remarks'       => [
                            'type'    => Table::TYPE_TEXT,
                            'length'  => 255,
                            'nullable' => false,
                            'default' => '',
                            'comment' => 'Remarks'
                        ],
                        'product'      => [
                            'type'    => Table::TYPE_TEXT,
                            'length'  => 255,
                            'nullable' => false,
                            'default' => '',
                            'comment' => 'Request Products'
                        ]
                    ];

                    $tagTable = $installer->getTable('video_form');
                    foreach ($columns as $name => $definition) {
                        $connection->addColumn($tagTable, $name, $definition);
                    }
                }
		}

        if (version_compare($context->getVersion(), '1.0.2') < 0)  {
            if (!$installer->tableExists('zoho_api')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('zoho_api')
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
                        'ID'
                    )
                    ->addColumn(
                        'refresh_token',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Refresh Token'
                    )                   
                    ->addColumn(
                        'access_token',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Access Token'
                    )                   
                    ->addColumn(
                            'created_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Created At'
                    )
                    ->addColumn(
                            'updated_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Updated At'
                    )
                    ->setComment('Zoho Table');
                $installer->getConnection()->createTable($table);
            }
        }

		$installer->endSetup();
	}
}