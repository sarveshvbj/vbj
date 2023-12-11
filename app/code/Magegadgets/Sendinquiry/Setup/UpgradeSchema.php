<?php

namespace Magegadgets\Sendinquiry\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;

use Magento\Framework\Setup\ModuleContextInterface;

use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements  UpgradeSchemaInterface

{

public function upgrade(SchemaSetupInterface $setup,

ModuleContextInterface $context){

$setup->startSetup();

if (version_compare($context->getVersion(), '1.0.0') < 0) {

// Get module table

$tableName = $setup->getTable('send_inquiry');

// Check if the table already exists

if ($setup->getConnection()->isTableExists($tableName) == true) {

// Declare data

$columns = [

'takedate' => [

'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,

'nullable' => true,

'comment' => 'Date',

],

];

$connection = $setup->getConnection();

foreach ($columns as $name => $definition) {

$connection->addColumn($tableName, $name, $definition);

}

 
}

}

 
$setup->endSetup();

}

}