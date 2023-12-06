<?php
 
namespace Magegadgets\Stoneattr\Model\Config\Source;
 
use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;
 
/**
 * Custom Attribute Renderer
 *
 * @author      Webkul Core Team <support@webkul.com>
 */
class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var OptionFactory
     */
    protected $optionFactory;
	
	
	protected $_stonemanagerFactory;
 
    /**
     * @param OptionFactory $optionFactory
     */
    /*public function __construct(OptionFactory $optionFactory)
    {
        $this->optionFactory = $optionFactory;  
        //you can use this if you want to prepare options dynamically  
    }*/
 
    /**
     * Get all options
     *
     * @return array
     */
	public function __construct(
        \Magegadgets\Stonemanager\Model\StonemanagerFactory $StonemanagerFactory
    ) {
        $this->_stonemanagerFactory = $StonemanagerFactory;
    }
	
	
    public function getAllOptions()
    {
		
		$collection = $this->_stonemanagerFactory->create()->getCollection()->addFieldToSelect('*');
		
		$this->_options = [
            ['label'=>'Select Stone', 'value'=>'']
        ];
		
		
		foreach($collection as $key =>  $stoneData){
			$this->_options[$key]['label'] = $stoneData->getName();
			$this->_options[$key]['value'] = $stoneData->getId();
		}
		
		
        return $this->_options;
    }
 
    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string|bool
     */
    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
 
    /**
     * Retrieve flat column definition
     *
     * @return array
     */
    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        return [
            $attributeCode => [
                'unsigned' => false,
                'default' => null,
                'extra' => null,
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Custom Attribute Options  ' . $attributeCode . ' column',
            ],
        ];
    }
}