<?php

namespace Vaibhav\Otp\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    public function upgrade(\Magento\Framework\Setup\ModuleDataSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context) {
        $setup->startSetup();
        if(version_compare($context->getVersion(),'1.0.1')<0)
        {
            $tableName = $setup->getTable('vaibhav_otp');
            if($setup->getConnection()->isTableExists($tableName)== true)
            {
                $data = [
                    [
                        'mobile' => '7715878743',
                        'otp'=>'123456',
                        'created_at' => date('Y-m-d H:i:s')                        
                    ]
                ];
                
                foreach($data as $record)
                {
                    $setup->getConnection()->insert($tableName, $record);
                }
            }
            $setup->endSetup();
                    
        }
    }
}