<?php

namespace Webkul\GiftCard\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $setup->startSetup();
        $version = $context->getVersion();
        
        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('salesrule'),
                'is_gift_voucher',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => false,
                    'default' => '0',
                    'comment' => 'Is Gift Voucher'
                ]
            );
        }

        if (version_compare($context->getVersion(), '2.0.2', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('quote'),
                'gift_voucher_amt',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length' => '12,4',
                    'nullable' => false,
                    'default' => '0.0000',
                    'comment' => 'Gift Voucher Amount'
                ]
            );

            $installer->getConnection()->addColumn(
                $installer->getTable('sales_order'),
                'gift_voucher_amt',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length' => '12,4',
                    'nullable' => false,
                    'default' => '0.0000',
                    'comment' => 'Gift Voucher Amount'
                ]
            );
            
            $installer->getConnection()->addColumn(
                $installer->getTable('quote'),
                'gift_voucher_code',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Gift Voucher Code'
                ]
            );

            $installer->getConnection()->addColumn(
                $installer->getTable('sales_order'),
                'gift_voucher_code',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Gift Voucher Code'
                ]
            );
        }

        if (version_compare($context->getVersion(), '2.0.3', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable($installer->getTable('wk_gift')),
                'expiry_date',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                    'nullable' => true,
                    'default' => null,
                    'comment' => 'Expiry Date'
                ]
            );
        }
        $setup->endSetup();
    }
}   