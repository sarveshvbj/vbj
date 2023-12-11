<?php
namespace Magegadgets\Stonemanager\Block\Adminhtml\Stonemanager;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magegadgets\Stonemanager\Model\stonemanagerFactory
     */
    protected $_stonemanagerFactory;

    /**
     * @var \Magegadgets\Stonemanager\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magegadgets\Stonemanager\Model\stonemanagerFactory $stonemanagerFactory
     * @param \Magegadgets\Stonemanager\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magegadgets\Stonemanager\Model\StonemanagerFactory $StonemanagerFactory,
        \Magegadgets\Stonemanager\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_stonemanagerFactory = $StonemanagerFactory;
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
        $collection = $this->_stonemanagerFactory->create()->getCollection();
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
						'header' => __('Display Name'),
						'index' => 'name',
					]
				);
				
				$this->addColumn(
					'code',
					[
						'header' => __('Code'),
						'index' => 'code',
					]
				);
				
				$this->addColumn(
					'itemkey',
					[
						'header' => __('Itemkey'),
						'index' => 'itemkey',
					]
				);
				
				$this->addColumn(
					'itemcode',
					[
						'header' => __('ItemCode'),
						'index' => 'itemcode',
					]
				);
				
				$this->addColumn(
					'startcent',
					[
						'header' => __('Start Range'),
						'index' => 'startcent',
					]
				);
				
				$this->addColumn(
					'endcent',
					[
						'header' => __('End Range'),
						'index' => 'endcent',
					]
				);
				
				$this->addColumn(
					'business_price',
					[
						'header' => __('Business Price'),
						'index' => 'business_price',
					]
				);
				
				$this->addColumn(
					'price',
					[
						'header' => __('Customer Price'),
						'index' => 'price',
					]
				);
				
				$this->addColumn(
					'stone_type',
					[
						'header' => __('Stone Type'),
						'index' => 'stone_type',
					]
				);
				
						
						$this->addColumn(
							'status',
							[
								'header' => __('Status'),
								'index' => 'status',
								'type' => 'options',
								'options' => \Magegadgets\Stonemanager\Block\Adminhtml\Stonemanager\Grid::getOptionArray9()
							]
						);
						
						


		
        $this->addColumn(
            'edit',
            [
                'header' => __('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit'
                        ],
                        'field' => 'id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
		

		
		   $this->addExportType($this->getUrl('stonemanager/*/exportCsv', ['_current' => true]),__('CSV'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

	
    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('id');
        //$this->getMassactionBlock()->setTemplate('Magegadgets_Stonemanager::stonemanager/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('stonemanager');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('stonemanager/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_status->getOptionArray();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('stonemanager/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses
                    ]
                ]
            ]
        );


        return $this;
    }
		

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('stonemanager/*/index', ['_current' => true]);
    }

    /**
     * @param \Magegadgets\Stonemanager\Model\stonemanager|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return $this->getUrl(
            'stonemanager/*/edit',
            ['id' => $row->getId()]
        );
		
    }

	
		static public function getOptionArray9()
		{
            $data_array=array(); 
			$data_array[0]='Enabled';
			$data_array[1]='Disabled';
            return($data_array);
		}
		static public function getValueArray9()
		{
            $data_array=array();
			foreach(\Magegadgets\Stonemanager\Block\Adminhtml\Stonemanager\Grid::getOptionArray9() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}