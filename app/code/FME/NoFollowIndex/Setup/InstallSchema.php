<?php

/**
 * Class for NoFollowIndex InstallSchema
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $table = $setup->getConnection()->newTable(
            $setup->getTable('fme_nofollowindex')
        )->addColumn(
            'Id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'unsigned' => true, 'primary' => true],
            'Id'
        )->addColumn(
            'nofollowindex_itemid',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'No Follow Index Item Id'
        )->addColumn(
            'nofollowindex_itemtype',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'No Follow Index Item Type'
        )->addColumn(
            'nofollowindex_itemfollowvalue',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'No Follow Index Item Follow Value'
        )->addColumn(
            'nofollowindex_itemindexvalue',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'No Follow Index Item Index Value'
        )->addColumn(
            'nofollowindex_itemenablevalue',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'No Follow Index Item Enable Value'
        )->setComment(
            'FME No Follow Index Table'
        );
        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }
}
