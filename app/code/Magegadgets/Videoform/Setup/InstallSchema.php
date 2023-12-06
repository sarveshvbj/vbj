<?php   

namespace Magegadgets\Videoform\Setup;

use \Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface {

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {

        $installer = $setup;
        $installer->startSetup();
        $tablename = $installer->getTable('video_form');
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
                            'language', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Language'
                    )
                    ->addColumn(
                            'takedate', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Date'
                    )
                    ->addColumn(
                            'time', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Time'
                    )
                    ->addColumn(
                            'created_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Created At'
                    )
                    ->setComment('Video from Table')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        //}
        $installer->endSetup();
    }

}
