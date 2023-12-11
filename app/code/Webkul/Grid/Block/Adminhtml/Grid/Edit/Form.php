<?php
/**
 * Webkul_Grid Add New Row Form Admin Block.
 * @category    Webkul
 * @package     Webkul_Grid
 * @author      Webkul Software Private Limited
 *
 */
namespace Webkul\Grid\Block\Adminhtml\Grid\Edit;

/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context,
     * @param \Magento\Framework\Registry $registry,
     * @param \Magento\Framework\Data\FormFactory $formFactory,
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
     * @param \Webkul\Grid\Model\Status $options,
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Webkul\Grid\Model\Status $options,
        array $data = []
    ) {
        $this->_options = $options;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $model = $this->_coreRegistry->registry('row_data');
        $form = $this->_formFactory->create(
            ['data' => [
                            'id' => 'edit_form',
                            'enctype' => 'multipart/form-data',
                            'action' => $this->getData('action'),
                            'method' => 'post'
                        ]
            ]
        );

        $form->setHtmlIdPrefix('wkgrid_');
        if ($model->getEntityId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Edit Row Data'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add Row Data'), 'class' => 'fieldset-wide']
            );
        }

        $fieldset->addField(
            'gift_code',
            'text',
            [
                'name' => 'gift_code',
                'label' => __('Gift Code'),
                'id' => 'gift_code',
                'title' => __('Gift Code'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $wysiwygConfig = $this->_wysiwygConfig->getConfig(['tab_id' => $this->getTabId()]);
        
        $fieldset->addField(
            'email_from',
            'text',
            [
                'name' => 'email_from',
                'label' => __('From Email'),
                'id' => 'from',
                'title' => __('From Email'),
                'class' => 'required-entry',
                'required' => true,
              
            ]
        );
         $fieldset->addField(
            'name_from',
            'text',
            [
                'name' => 'name_from',
                'label' => __('Name'),
                'id' => 'from',
                'title' => __('From Name'),
                'class' => 'required-entry',
                'required' => true,
              
            ]
        );
        $fieldset->addField(
            'to_email',
            'text',
            [
                'name' => 'to_email',
                'label' => __('To Email'),
                'id' => 'email',
                'title' => __('To Email'),
                'class' => 'required-entry',
                'required' => true,
              
            ]
        );
         $fieldset->addField(
            'to_mobile',
            'text',
            [
                'name' => 'to_mobile',
                'label' => __('To Mobile'),
                'id' => 'email',
                'title' => __('To Mobile'),
                'class' => 'required-entry',
                'required' => true,
              
            ]
        );
          $fieldset->addField(
            'to_name',
            'text',
            [
                'name' => 'to_name',
                'label' => __('To Name'),
                'id' => 'email',
                'title' => __('To Name'),
                'class' => 'required-entry',
                'required' => true,
              
            ]
        );
         $fieldset->addField(
            'price',
            'text',
            [
                'name' => 'price',
                'label' => __('price'),
                'id' => 'email',
                'title' => __('To'),
                'class' => 'required-entry',
                'required' => true,
              
            ]
        );
          $fieldset->addField(
            'remaining_amt',
            'text',
            [
                'name' => 'remaining_amt',
                'label' => __('Left Amount'),
                'id' => 'remaining_amt',
                'title' => __('Left Amount'),
                'class' => 'required-entry',
                'required' => true,
              
            ]
        );
        $fieldset->addField(
            'quantity',
            'text',
            [
                'name' => 'quantity',
                'label' => __('Quantity'),
                'id' => 'quantity',
                'title' => __('Quantity'),
                'class' => 'required-entry',
                'required' => true,
              
            ]
        );
         $fieldset->addField(
            'message',
            'text',
            [
                'name' => 'message',
                'label' => __('message'),
                'id' => 'remaining_amt',
                'title' => __('message'),
                'class' => 'required-entry',
                'required' => true,
              
            ]
        );
        $fieldset->addField(
            'created_at',
            'date',
            [
                'name' => 'created_at',
                'label' => __('Publish Date'),
                'date_format' => $dateFormat,
                'time_format' => 'H:mm:ss',
                'class' => 'validate-date validate-date-range date-range-custom_theme-from',
                'class' => 'required-entry',
                'style' => 'width:200px',
            ]
        );
        $fieldset->addField(
            'is_active',
            'select',
            [
                'name' => 'is_active',
                'label' => __('Status'),
                'id' => 'is_active',
                'title' => __('Status'),
                'values' => $this->_options->getOptionArray(),
                'class' => 'status',
                'required' => true,
            ]
        );
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
