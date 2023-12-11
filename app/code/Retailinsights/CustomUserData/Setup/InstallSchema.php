<?php
namespace Retailinsights\CustomUserData\Setup;

use \Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface {

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {

        $installer = $setup;
        $installer->startSetup();
        $tablename = $installer->getTable('custom_userdata');
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
                            'mobile', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Mobile'
                    )
                    ->addColumn(
                            'email', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Email'
                    )
                     ->addColumn(
                            'category', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Category'
                    )
                    ->addColumn(
                            'product_name', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Product Name'
                    )
                    ->addColumn(
                            'price', Table::TYPE_DECIMAL, '12,4', ['nullable' => false], 'Price'
                    )
                    ->addColumn(
                            'date_of_purchase', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Date of Purchase'
                    )
                     ->addColumn(
                            'group', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Event Group'
                    )
                    ->addColumn(
                            'flag', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Flag Y or U'
                    )
                    ->setComment('Custom User Data')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        //}
        $installer->endSetup();
    }

}
