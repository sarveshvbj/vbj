<?php
/**
 * MageGadgets Extensions
 * Stonemanager Extension
 * 
 * @package    		Magegadgets_Stonemanager
 * @copyright  		Copyright (c) 2017 MageGadgets Extensions (http://www.magegadgets.com/)
 * @contactEmail   	support@magegadgets.com
*/

namespace Magegadgets\Stonemanager\Model\Config\Backend;

use Magento\Framework\App\Config\Value;
use Magegadgets\Stonemanager\Api\StonemanagerTableInterface;

class Table extends Value
{
    protected $stonemanagerTableInterface;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        StonemanagerTableInterface $stonemanagerTableInterface,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
        $this->stonemanagerTableInterface = $stonemanagerTableInterface;
    }

    public function afterSave()
    {
        // @codingStandardsIgnoreStart
        if (empty($_FILES['groups']['tmp_name']['general']['fields']['import']['value'])) {
            return $this;
        }

        $csvFile = $_FILES['groups']['tmp_name']['general']['fields']['import']['value'];
        // @codingStandardsIgnoreEnd
		
        $this->stonemanagerTableInterface->saveFromFile($csvFile);

        return parent::afterSave();
    }
}
