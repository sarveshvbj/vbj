<?php
namespace Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magegadgets\Loosediamonds\Model\loosediamondsFactory
     */
    protected $_loosediamondsFactory;

    /**
     * @var \Magegadgets\Loosediamonds\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magegadgets\Loosediamonds\Model\loosediamondsFactory $loosediamondsFactory
     * @param \Magegadgets\Loosediamonds\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magegadgets\Loosediamonds\Model\LoosediamondsFactory $LoosediamondsFactory,
        \Magegadgets\Loosediamonds\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_loosediamondsFactory = $LoosediamondsFactory;
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
        $collection = $this->_loosediamondsFactory->create()->getCollection();
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
					'item_id',
					[
						'header' => __('Item Id'),
						'index' => 'item_id',
					]
				);
				
						
						$this->addColumn(
							'shape',
							[
								'header' => __('Shape'),
								'index' => 'shape',
								'type' => 'options',
								'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray1()
							]
						);
						
						
				$this->addColumn(
					'carats',
					[
						'header' => __('Carats'),
						'index' => 'carats',
					]
				);
				
				$this->addColumn(
					'certificate',
					[
						'header' => __('Certificate'),
						'index' => 'certificate',
					]
				);
				
				$this->addColumn(
					'certificate_link',
					[
						'header' => __('Certificate Link'),
						'index' => 'certificate_link',
					]
				);
				
				$this->addColumn(
					'certificate_no',
					[
						'header' => __('Certificate No'),
						'index' => 'certificate_no',
					]
				);
				
						
						$this->addColumn(
							'color',
							[
								'header' => __('Color'),
								'index' => 'color',
								'type' => 'options',
								'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray6()
							]
						);
						
						
						
						$this->addColumn(
							'clarity',
							[
								'header' => __('Clarity'),
								'index' => 'clarity',
								'type' => 'options',
								'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray7()
							]
						);
						
						
						
						$this->addColumn(
							'cut',
							[
								'header' => __('Cut'),
								'index' => 'cut',
								'type' => 'options',
								'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray8()
							]
						);
						
						
						
						$this->addColumn(
							'polish',
							[
								'header' => __('Polish'),
								'index' => 'polish',
								'type' => 'options',
								'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray9()
							]
						);
						
						
						
						$this->addColumn(
							'symmetry',
							[
								'header' => __('Symmetry'),
								'index' => 'symmetry',
								'type' => 'options',
								'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray10()
							]
						);
						
						
						
						$this->addColumn(
							'fluorescence',
							[
								'header' => __('Fluorescence'),
								'index' => 'fluorescence',
								'type' => 'options',
								'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray11()
							]
						);
						
						
				$this->addColumn(
					'measurement',
					[
						'header' => __('Measurement'),
						'index' => 'measurement',
					]
				);
				
				$this->addColumn(
					'table',
					[
						'header' => __('Table'),
						'index' => 'table',
					]
				);
				
				$this->addColumn(
					'depth',
					[
						'header' => __('Depth'),
						'index' => 'depth',
					]
				);
				
				$this->addColumn(
					'price',
					[
						'header' => __('Price '),
						'index' => 'price',
					]
				);
				


		
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
		

		
		   $this->addExportType($this->getUrl('loosediamonds/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('loosediamonds/*/exportExcel', ['_current' => true]),__('Excel XML'));

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
        //$this->getMassactionBlock()->setTemplate('Magegadgets_Loosediamonds::loosediamonds/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('loosediamonds');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('loosediamonds/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_status->getOptionArray();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('loosediamonds/*/massStatus', ['_current' => true]),
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
        return $this->getUrl('loosediamonds/*/index', ['_current' => true]);
    }

    /**
     * @param \Magegadgets\Loosediamonds\Model\loosediamonds|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return $this->getUrl(
            'loosediamonds/*/edit',
            ['id' => $row->getId()]
        );
		
    }

	
		static public function getOptionArray1()
		{
            $data_array=array(); 
			$data_array[0]='Round';
			$data_array[1]='Cushion';
			$data_array[2]='Emerald';
			$data_array[3]='Heart';
			$data_array[4]='Oval';
			$data_array[5]='Perl';
			$data_array[6]='Marquise';
			$data_array[7]='Pear';
			$data_array[8]='Radiant';
			$data_array[9]='Triangle';
			$data_array[10]='Princess';
			$data_array[11]='Asscher';
            return($data_array);
		}
		static public function getValueArray1()
		{
            $data_array=array();
			foreach(\Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray1() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		static public function getOptionArray6()
		{
            $data_array=array(); 
			$data_array[0]='D';
			$data_array[1]='E';
			$data_array[2]='F';
			$data_array[3]='FY';
			$data_array[4]='G';
			$data_array[5]='H';
			$data_array[6]='I';
			$data_array[7]='J';
			$data_array[8]='K';
			$data_array[9]='L';
			$data_array[10]='M';
			$data_array[11]='N';
			$data_array[12]='Y-Z';
            return($data_array);
		}
		static public function getValueArray6()
		{
            $data_array=array();
			foreach(\Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray6() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		static public function getOptionArray7()
		{
            $data_array=array(); 
			$data_array[0]='FL';
			$data_array[1]='I1';
			$data_array[2]='IF';
			$data_array[3]='SI';
			$data_array[4]='SI1';
			$data_array[5]='SI2';
			$data_array[6]='VS';
			$data_array[7]='VS1';
			$data_array[8]='VS2';
			$data_array[9]='VVS1';
			$data_array[10]='VVS2';
            return($data_array);
		}
		static public function getValueArray7()
		{
            $data_array=array();
			foreach(\Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray7() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		static public function getOptionArray8()
		{
            $data_array=array(); 
			$data_array[0]='Excellent';
			$data_array[1]='Good';
			$data_array[2]='Very Good';
            return($data_array);
		}
		static public function getValueArray8()
		{
            $data_array=array();
			foreach(\Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray8() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		static public function getOptionArray9()
		{
            $data_array=array(); 
			$data_array[0]='Excellent';
			$data_array[1]='Good';
			$data_array[2]='Very Good';
            return($data_array);
		}
		static public function getValueArray9()
		{
            $data_array=array();
			foreach(\Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray9() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		static public function getOptionArray10()
		{
            $data_array=array(); 
			$data_array[0]='Excellent';
			$data_array[1]='Good';
			$data_array[2]='Very Good';
            return($data_array);
		}
		static public function getValueArray10()
		{
            $data_array=array();
			foreach(\Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray10() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		static public function getOptionArray11()
		{
            $data_array=array(); 
			$data_array[0]='None';
			$data_array[1]='Excellent';
			$data_array[2]='Faint';
			$data_array[3]='Fluorescence';
			$data_array[4]='Good';
			$data_array[5]='Medium';
			$data_array[6]='Slight';
			$data_array[7]='Strong';
			$data_array[8]='Very Good';
			$data_array[9]='Very Slight';
            return($data_array);
		}
		static public function getValueArray11()
		{
            $data_array=array();
			foreach(\Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray11() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}