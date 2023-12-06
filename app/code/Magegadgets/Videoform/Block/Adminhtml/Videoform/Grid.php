<?php
namespace Magegadgets\Videoform\Block\Adminhtml\Videoform;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magegadgets\Videoform\Model\videoformFactory
     */
    protected $_videoformFactory;

    /**
     * @var \Magegadgets\Videoform\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magegadgets\Videoform\Model\videoformFactory $videoformFactory
     * @param \Magegadgets\Videoform\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magegadgets\Videoform\Model\VideoformFactory $VideoformFactory,
        \Magegadgets\Videoform\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_videoformFactory = $VideoformFactory;
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
        $collection = $this->_videoformFactory->create()->getCollection();
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
					'mobile',
					[
						'header' => __('Mobile'),
						'index' => 'mobile',
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
					'language',
					[
						'header' => __('Language'),
						'index' => 'language',
					]
				);
               /* $this->addColumn(
                    'date',
                    [
                        'header' => __('Date'),
                        'index' => 'date',
                    ]
                );*/
                 $this->addColumn(
                    'takedate',
                    [
                        'header' => __('Date'),
                        'index' => 'takedate',
                    ]
                );
                 $this->addColumn(
                    'time',
                    [
                        'header' => __('Time'),
                        'index' => 'time',
                    ]
                );
				


		

		
		   $this->addExportType($this->getUrl('videoform/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('videoform/*/exportExcel', ['_current' => true]),__('Excel XML'));

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
        return $this->getUrl('videoform/*/index', ['_current' => true]);
    }

    /**
     * @param \Magegadgets\Videoform\Model\videoform|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		return '#';
    }

	

}