<?php   

namespace Als\Testimonials\Setup;

use \Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface {

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {

        $installer = $setup;
        $installer->startSetup();
       /* $tablename = $installer->getTable('notify_stock');
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
                            'product_id', Table::TYPE_INTEGER, null, ['nullable' => true, 'default' => '0'], 'Product Id'
                    )
                    ->addColumn(
                            'email_id', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'EmailId'
                    )
                    ->addColumn(
                            'product_name', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'product name'
                    )
                    ->addColumn(
                            'created_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Created At'
                    )
                    ->setComment('Notify Stock Table')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');*/
        //$installer->getConnection()->createTable($table);
        $tablenameone = $installer->getTable('product_enquiry');
        //if ($installer->getConnection()->isTableExists($tablename)) {
            $table = $installer->getConnection()
                    ->newTable($tablenameone)
                    ->addColumn(
                            'id', Table::TYPE_INTEGER, null, [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                            ], 'ID'
                    )
                    ->addColumn(
                            'product_sku', Table::TYPE_TEXT, null, ['nullable' => true, 'default' => ''], 'Product Id'
                    )
                    ->addColumn(
                            'product_name', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'product name'
                    )
                     ->addColumn(
                            'product_price', Table::TYPE_DECIMAL, null, ['nullable' => false, 'default' => '0.0000'], 'product name'
                    )
                    ->addColumn(
                            'customer_name', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'EmailId'
                    )
                     ->addColumn(
                            'customer_email', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'EmailId'
                    )
                    ->addColumn(
                            'customer_mobile', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'EmailId'
                    )
                    ->addColumn(
                            'created_at', Table::TYPE_DATETIME, null, ['nullable' => false], 'Created At'
                    )
                    ->setComment('Product Enquiry Table')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        //}
        $installer->endSetup();
    }

}
