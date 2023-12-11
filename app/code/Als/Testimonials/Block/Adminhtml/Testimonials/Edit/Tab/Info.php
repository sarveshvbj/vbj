<?php

namespace Als\Testimonials\Block\Adminhtml\Testimonials\Edit\Tab;
 
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;

 
class Info extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;
 
    /**
     * @var \Tutorial\SimpleNews\Model\Config\Status
     */
    
   /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param Status $testimonialsStatus
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
       /** @var $model \Als\Testimonials\Model\Testimonials */
        $model = $this->_coreRegistry->registry('testimonials_testimonials');
 
        $file_name = $model->getProfilePic();
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('testimonials_');
        $form->setFieldNameSuffix('testimonials');
 
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General')]
        );
 
        if ($model->getId()) {
            $fieldset->addField(
                'id',
                'hidden',
                ['name' => 'id']
            );
        }
        $fieldset->addField(
            'product_sku',
            'text',
            [
                'name'        => 'product_id',
                'label'    => __('product Id'),
                'required'     => true
            ]
        );
        $fieldset->addField(
            'product_name',
            'text',
            [
                'name'        => 'product_name',
                'label'    => __('product Name'),
                'required'     => true
            ]
        );
          $fieldset->addField(
            'product_price',
            'decimal',
            [
                'name'        => 'product_price',
                'label'    => __('product Price'),
                'required'     => true
            ]
        );
        $fieldset->addField(
            'customer_name',
            'text',
            [
                'name'        => 'customer_name',
                'label'    => __('Customer Name'),
                'required'     => true
            ]
        );
         $fieldset->addField(
            'customer_email',
            'text',
            [
                'name'        => 'customer_email',
                'label'    => __('Customer Email'),
                'required'     => true
            ]
        );
       
        $fieldset->addField(
            'customer_mobile',
            'text',
            [
                'name'        => 'customer_mobile',
                'label'    => __('Customer Mobile'),
                'required'     => true
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
 
    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Stock Notify Info');
    }
 
    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Stock Notify Info');
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
}