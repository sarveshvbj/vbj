<?php
namespace Magegadgets\Sendinquiry\Block\Adminhtml\Sendinquiry;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magegadgets\Sendinquiry\Model\sendinquiryFactory
     */
    protected $_sendinquiryFactory;

    /**
     * @var \Magegadgets\Sendinquiry\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magegadgets\Sendinquiry\Model\sendinquiryFactory $sendinquiryFactory
     * @param \Magegadgets\Sendinquiry\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magegadgets\Sendinquiry\Model\SendinquiryFactory $SendinquiryFactory,
        \Magegadgets\Sendinquiry\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_sendinquiryFactory = $SendinquiryFactory;
        $this->_status = $status;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('postGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $this->setVarNameFilter('post_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_sendinquiryFactory->create()->getCollection();
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );


		
				$this->addColumn(
					'name',
					[
						'header' => __('Name'),
						'index' => 'name',
					]
				);
				
				$this->addColumn(
					'email',
					[
						'header' => __('Email'),
						'index' => 'email',
					]
				);
				
				$this->addColumn(
					'contact',
					[
						'header' => __('Contact'),
						'index' => 'contact',
					]
				);
				
				$this->addColumn(
					'area',
					[
						'header' => __('Area'),
						'index' => 'area',
					]
				);
				
				$this->addColumn(
					'details',
					[
						'header' => __('Details'),
						'index' => 'details',
					]
				);
				
				$this->addColumn(
					'productname',
					[
						'header' => __('Product Name'),
						'index' => 'productname',
					]
				);
				
				$this->addColumn(
					'productcode',
					[
						'header' => __('Product Code'),
						'index' => 'productcode',
					]
				);
				
						
						$this->addColumn(
							'type',
							[
								'header' => __('Type'),
								'index' => 'type',
								'type' => 'options',
								'options' => \Magegadgets\Sendinquiry\Block\Adminhtml\Sendinquiry\Grid::getOptionArray8()
							]
						);
						
						


		

		
		   $this->addExportType($this->getUrl('sendinquiry/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('sendinquiry/*/exportExcel', ['_current' => true]),__('Excel XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

	

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('sendinquiry/*/index', ['_current' => true]);
    }

    /**
     * @param \Magegadgets\Sendinquiry\Model\sendinquiry|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		return '#';
    }

	
		static public function getOptionArray8()
		{
            $data_array=array(); 
			$data_array[0]='International Shipment';
			$data_array[1]='Out of Stock';
            return($data_array);
		}
		static public function getValueArray8()
		{
            $data_array=array();
			foreach(\Magegadgets\Sendinquiry\Block\Adminhtml\Sendinquiry\Grid::getOptionArray8() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}