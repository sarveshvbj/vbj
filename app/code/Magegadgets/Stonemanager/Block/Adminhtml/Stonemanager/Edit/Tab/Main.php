<?php

namespace Magegadgets\Stonemanager\Block\Adminhtml\Stonemanager\Edit\Tab;

/**
 * Stonemanager edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magegadgets\Stonemanager\Model\Status
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
        \Magegadgets\Stonemanager\Model\Status $status,
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
        /* @var $model \Magegadgets\Stonemanager\Model\BlogPosts */
        $model = $this->_coreRegistry->registry('stonemanager');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Stone Information')]);

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

		
        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Display Name'),
                'title' => __('Display Name'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'code',
            'text',
            [
                'name' => 'code',
                'label' => __('Code'),
                'title' => __('Code'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'itemkey',
            'text',
            [
                'name' => 'itemkey',
                'label' => __('Itemkey'),
                'title' => __('Itemkey'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'itemcode',
            'text',
            [
                'name' => 'itemcode',
                'label' => __('ItemCode'),
                'title' => __('ItemCode'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'startcent',
            'text',
            [
                'name' => 'startcent',
                'label' => __('Start Range'),
                'title' => __('Start Range'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'endcent',
            'text',
            [
                'name' => 'endcent',
                'label' => __('End Range'),
                'title' => __('End Range'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'business_price',
            'text',
            [
                'name' => 'business_price',
                'label' => __('Business Price'),
                'title' => __('Business Price'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'price',
            'text',
            [
                'name' => 'price',
                'label' => __('Customer Price'),
                'title' => __('Customer Price'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'stone_type',
            'text',
            [
                'name' => 'stone_type',
                'label' => __('Stone Type'),
                'title' => __('Stone Type'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
									
						
        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
				'required' => true,
                'options' => \Magegadgets\Stonemanager\Block\Adminhtml\Stonemanager\Grid::getOptionArray9(),
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
        return __('Stone Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Stone Information');
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
