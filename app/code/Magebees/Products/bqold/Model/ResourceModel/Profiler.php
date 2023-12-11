<?php
namespace Magebees\Products\Model\ResourceModel;

class Profiler extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	protected function _construct()
	{
		$this->_init('cws_product_import_profiler', 'profiler_id');
	
	}
	public function truncate() {
		$this->_getConnection('write')->query('TRUNCATE TABLE '.$this->getMainTable());
		return $this;
	}
	public function insertMultipleProduct($rows){
        $this->_getConnection('write')->insertMultiple($this->getMainTable(), $rows);
		
	}	
}