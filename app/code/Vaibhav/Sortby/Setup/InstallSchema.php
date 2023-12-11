<?php

namespace Vaibhav\Sortby\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()->addColumn(
            $setup->getTable('quote'),
            'store',
            [
                'type' => 'text',
                'nullable' => false,
                'comment' => 'Store',
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order'),
            'store',
            [
                'type' => 'text',
                'nullable' => false,
                'comment' => 'Store',
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_grid'),
            'store',
            [
                'type' => 'text',
                'nullable' => false,
                'comment' => 'Store',
            ]
        );
        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_grid'),
            'donation',
            [
                'type' => 'text',
                'nullable' => false,
                'comment' => 'Donation',
            ]
        );

        $setup->endSetup();
    }
}

