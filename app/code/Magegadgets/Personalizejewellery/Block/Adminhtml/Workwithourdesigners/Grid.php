<?php
namespace Magegadgets\Personalizejewellery\Block\Adminhtml\Workwithourdesigners;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magegadgets\Personalizejewellery\Model\personalizejewelleryFactory
     */
    protected $_personalizejewelleryFactory;

    /**
     * @var \Magegadgets\Personalizejewellery\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magegadgets\Personalizejewellery\Model\personalizejewelleryFactory $personalizejewelleryFactory
     * @param \Magegadgets\Personalizejewellery\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magegadgets\Personalizejewellery\Model\PersonalizejewelleryFactory $PersonalizejewelleryFactory,
        \Magegadgets\Personalizejewellery\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_personalizejewelleryFactory = $PersonalizejewelleryFactory;
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
        $collection = $this->_personalizejewelleryFactory->create()->getCollection();
		$collection->addFieldToFilter('type', 3);
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
        /*$this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );*/


		
				$this->addColumn(
					'fullname',
					[
						'header' => __('Full Name'),
						'index' => 'fullname',
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
					'mobile',
					[
						'header' => __('Mobile'),
						'index' => 'mobile',
					]
				);
				
				 $this->addColumn(
					'details',
					[
						'header' => __('Details'),
						'index' => 'details',
					]
				);  
				
		/*		$this->addColumn(
					'product_sku',
					[
						'header' => __('Product'),
						'index' => 'product_sku',
					]
				);
				
*/

		
        //$this->addColumn(
            //'edit',
            //[
                //'header' => __('Edit'),
                //'type' => 'action',
                //'getter' => 'getId',
                //'actions' => [
                    //[
                        //'caption' => __('Edit'),
                        //'url' => [
                            //'base' => '*/*/edit'
                        //],
                        //'field' => 'id'
                    //]
                //],
                //'filter' => false,
                //'sortable' => false,
                //'index' => 'stores',
                //'header_css_class' => 'col-action',
                //'column_css_class' => 'col-action'
            //]
        //);
		

		
		   $this->addExportType($this->getUrl('personalizejewellery/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('personalizejewellery/*/exportExcel', ['_current' => true]),__('Excel XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

	
    /**
     * @return $this
     */
    
		

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('personalizejewellery/*/index', ['_current' => true]);
    }

    /**
     * @param \Magegadgets\Personalizejewellery\Model\personalizejewellery|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		return false;
        return $this->getUrl(
            'personalizejewellery/*/edit',
            ['id' => $row->getId()]
        );
		
    }

	

}