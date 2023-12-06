<?php
namespace Iksula\Complaint\Setup;

use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Customer\Model\Customer;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class InstallData implements InstallDataInterface
{
    
    /**
     * Init
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(\Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory,
             AttributeSetFactory $attributeSetFactory)
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }
        /**
     * A central function to return the attributes that need to be created
     *
     * Updated on 15 June 2016, following Anamika comment
     **/
    

    /**
     * Installs DB schema for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

         $customerSetup->addAttribute(Customer::ENTITY, 'mobile', [
            'type' => 'varchar',
            'label' => 'Mobile',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 1000,
            'position' => 1000,
            'system' => 0,
        ]);
         $usedInForms = ['adminhtml_customer','adminhtml_customer_address','customer_account_create', 'customer_account_edit', 'checkout_register'];
        //add attribute to attribute set
        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'mobile')
        ->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer'],
        ]);
        $attribute->save();	
    }
    
    /**
     * Add customer attributes to customer forms
     *
     * @param EavSetup $eavSetup
     *
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function installCustomerForms(EavSetup $eavSetup)
    {
        $customer = (int)$eavSetup->getEntityTypeId(\Magento\Customer\Model\Customer::ENTITY);
        /**
         * @var ModuleDataSetupInterface $setup
         */
        $setup = $eavSetup->getSetup();
 
        $attributeIds = [];
        $select = $setup->getConnection()->select()->from(
            ['ea' => $setup->getTable('eav_attribute')],
            ['entity_type_id', 'attribute_code', 'attribute_id']
        )->where(
            'ea.entity_type_id IN(?)',
            [$customer]
        );
        foreach ($eavSetup->getSetup()->getConnection()->fetchAll($select) as $row) {
            $attributeIds[$row['entity_type_id']][$row['attribute_code']] = $row['attribute_id'];
        }
 
        $data = [];
        $attributes = $this->getNewAttributes();
        foreach ($attributes as $attributeCode => $attribute) {
            $attributeId = $attributeIds[$customer][$attributeCode];
            $attribute['system'] = isset($attribute['system']) ? $attribute['system'] : true;
            $attribute['visible'] = isset($attribute['visible']) ? $attribute['visible'] : true;
            if ($attribute['system'] != true || $attribute['visible'] != false) {
                $usedInForms = ['customer_account_create', 'customer_account_edit', 'checkout_register'];
                if (!empty($attribute['adminhtml_only'])) {
                    $usedInForms = ['adminhtml_customer'];
                } else {
                    $usedInForms[] = 'adminhtml_customer';
                }
                if (!empty($attribute['admin_checkout'])) {
                    $usedInForms[] = 'adminhtml_checkout';
                }
                foreach ($usedInForms as $formCode) {
                    $data[] = ['form_code' => $formCode, 'attribute_id' => $attributeId];
                }
            }
        }
 
        if ($data) {
            $setup->getConnection()
                ->insertOnDuplicate($setup->getTable('customer_form_attribute'), $data);
        }
    }
}