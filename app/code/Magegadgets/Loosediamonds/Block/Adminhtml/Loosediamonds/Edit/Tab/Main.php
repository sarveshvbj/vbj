<?php

namespace Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Edit\Tab;

/**
 * Loosediamonds edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magegadgets\Loosediamonds\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magegadgets\Loosediamonds\Model\Status $status,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Magegadgets\Loosediamonds\Model\BlogPosts */
        $model = $this->_coreRegistry->registry('loosediamonds');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Diamond Information')]);

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

		
        $fieldset->addField(
            'item_id',
            'text',
            [
                'name' => 'item_id',
                'label' => __('Item Id'),
                'title' => __('Item Id'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
									
						
        $fieldset->addField(
            'shape',
            'select',
            [
                'label' => __('Shape'),
                'title' => __('Shape'),
                'name' => 'shape',
				'required' => true,
                'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray1(),
                'disabled' => $isElementDisabled
            ]
        );
						
						
        $fieldset->addField(
            'carats',
            'text',
            [
                'name' => 'carats',
                'label' => __('Carats'),
                'title' => __('Carats'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'certificate',
            'text',
            [
                'name' => 'certificate',
                'label' => __('Certificate'),
                'title' => __('Certificate'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'certificate_link',
            'text',
            [
                'name' => 'certificate_link',
                'label' => __('Certificate Link'),
                'title' => __('Certificate Link'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'certificate_no',
            'text',
            [
                'name' => 'certificate_no',
                'label' => __('Certificate No'),
                'title' => __('Certificate No'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
									
						
        $fieldset->addField(
            'color',
            'select',
            [
                'label' => __('Color'),
                'title' => __('Color'),
                'name' => 'color',
				'required' => true,
                'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray6(),
                'disabled' => $isElementDisabled
            ]
        );
						
										
						
        $fieldset->addField(
            'clarity',
            'select',
            [
                'label' => __('Clarity'),
                'title' => __('Clarity'),
                'name' => 'clarity',
				'required' => true,
                'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray7(),
                'disabled' => $isElementDisabled
            ]
        );
						
										
						
        $fieldset->addField(
            'cut',
            'select',
            [
                'label' => __('Cut'),
                'title' => __('Cut'),
                'name' => 'cut',
				'required' => true,
                'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray8(),
                'disabled' => $isElementDisabled
            ]
        );
						
										
						
        $fieldset->addField(
            'polish',
            'select',
            [
                'label' => __('Polish'),
                'title' => __('Polish'),
                'name' => 'polish',
				'required' => true,
                'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray9(),
                'disabled' => $isElementDisabled
            ]
        );
						
										
						
        $fieldset->addField(
            'symmetry',
            'select',
            [
                'label' => __('Symmetry'),
                'title' => __('Symmetry'),
                'name' => 'symmetry',
				'required' => true,
                'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray10(),
                'disabled' => $isElementDisabled
            ]
        );
						
										
						
        $fieldset->addField(
            'fluorescence',
            'select',
            [
                'label' => __('Fluorescence'),
                'title' => __('Fluorescence'),
                'name' => 'fluorescence',
				'required' => true,
                'options' => \Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Grid::getOptionArray11(),
                'disabled' => $isElementDisabled
            ]
        );
						
						
        $fieldset->addField(
            'measurement',
            'text',
            [
                'name' => 'measurement',
                'label' => __('Measurement'),
                'title' => __('Measurement'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'table',
            'text',
            [
                'name' => 'table',
                'label' => __('Table'),
                'title' => __('Table'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'depth',
            'text',
            [
                'name' => 'depth',
                'label' => __('Depth'),
                'title' => __('Depth'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'price',
            'text',
            [
                'name' => 'price',
                'label' => __('Price '),
                'title' => __('Price '),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					

        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);
		
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Item Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
    
    public function getTargetOptionArray(){
    	return array(
    				'_self' => "Self",
					'_blank' => "New Page",
    				);
    }
}
