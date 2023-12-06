<?php

namespace Magegadgets\Sendinquiry\Block\Adminhtml\Sendinquiry\Edit\Tab;

/**
 * Sendinquiry edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magegadgets\Sendinquiry\Model\Status
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
        \Magegadgets\Sendinquiry\Model\Status $status,
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
        /* @var $model \Magegadgets\Sendinquiry\Model\BlogPosts */
        $model = $this->_coreRegistry->registry('sendinquiry');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

		
        $fieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('Email'),
                'title' => __('Email'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'contact',
            'text',
            [
                'name' => 'contact',
                'label' => __('Contact'),
                'title' => __('Contact'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'area',
            'text',
            [
                'name' => 'area',
                'label' => __('Area'),
                'title' => __('Area'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'details',
            'text',
            [
                'name' => 'details',
                'label' => __('Details'),
                'title' => __('Details'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'productname',
            'text',
            [
                'name' => 'productname',
                'label' => __('Product Name'),
                'title' => __('Product Name'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'productcode',
            'text',
            [
                'name' => 'productcode',
                'label' => __('Product Code'),
                'title' => __('Product Code'),
				
                'disabled' => $isElementDisabled
            ]
        );
									
						
        $fieldset->addField(
            'type',
            'select',
            [
                'label' => __('Type'),
                'title' => __('Type'),
                'name' => 'type',
				
                'options' => \Magegadgets\Sendinquiry\Block\Adminhtml\Sendinquiry\Grid::getOptionArray8(),
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
        return __('Item Information');
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
