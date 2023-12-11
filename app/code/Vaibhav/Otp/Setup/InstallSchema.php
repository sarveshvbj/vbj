<?php   

namespace Vaibhav\Otp\Setup;

use \Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface {

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {

        $installer = $setup;
        $installer->startSetup();
        $tablename = $installer->getTable('vaibhav_otp');
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
                            'mobile', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Mobile'
                    )
                    ->addColumn(
                            'otp', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'OTP'
					)
					->addColumn(
                            'created_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Created At'
                    )
                    ->setComment('Otp Table')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
            $installer->endSetup();
    }

}
