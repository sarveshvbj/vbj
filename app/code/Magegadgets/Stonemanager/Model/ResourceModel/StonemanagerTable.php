<?php
/**
 * MageGadgets Extensions
 * Stonemanager Extension
 * 
 * @package    		Magegadgets_Stonemanager
 * @copyright  		Copyright (c) 2017 MageGadgets Extensions (http://www.magegadgets.com/)
 * @contactEmail   	support@magegadgets.com
*/

namespace Magegadgets\Stonemanager\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\StoreManagerInterface;

class StonemanagerTable extends AbstractDb
{
    protected $storeManager;
	protected $_customerSession;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
		\Magento\Customer\Model\Session $customerSession,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->storeManager = $storeManager;
		$this->_customerSession = $customerSession;
    }

    protected function _construct()
    {
        $this->_init('stone_manager', 'id');
    }

    
	public function getGroupId(){
		if($this->_customerSession->isLoggedIn()){
			return $this->_customerSession->getCustomer()->getGroupId();
		}else{
			return 0;
		}
	}

    /**
     * Get table as array
     *
     * @return array
     */
    public function getTableAsArray()
    {
        $table = $this->getMainTable();

        $connection = $this->getConnection();
        $qry = $connection->select()
            ->from($table, '*');

        // @codingStandardsIgnoreStart
        return $connection->fetchAll($qry);
        // @codingStandardsIgnoreEnd
    }

    /**
     * Populate table from array
     *
     * @param array $data
     */
    public function populateFromArray(array $data)
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();

        $table = $this->getMainTable();

        $connection->delete($table);
        foreach ($data as $dataRow) {
            $connection->insert($table, $dataRow);
        }

        $connection->commit();
    }

    /**
     * Get rows count
     *
     * @return int
     */
    public function getRowsCount()
    {
        return count($this->getTableAsArray());
    }
}
