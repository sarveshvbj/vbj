<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_AutoRelated
 * @copyright   Copyright (c) 2017-2018 Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\AutoRelated\Block\Adminhtml\Rule\Edit\Tab\General;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Magento\Store\Model\System\Store;
use Mageplaza\AutoRelated\Model\ResourceModel\RuleFactory;
use Mageplaza\AutoRelated\Model\Rule\LocationOptionsProvider;

/**
 * Class General
 * @package Mageplaza\AutoRelated\Block\Adminhtml\Rule\Edit\Tab\General
 */
class General extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $systemStore;

    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected $_groupRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Convert\DataObject
     */
    protected $_objectConverter;

    /**
     * @var \Mageplaza\AutoRelated\Model\Rule\LocationOptionsProvider
     */
    protected $locationOptions;

    /**
     * @var \Mageplaza\AutoRelated\Model\ResourceModel\RuleFactory
     */
    protected $resourceModelRuleFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Convert\DataObject $objectConverter
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Mageplaza\AutoRelated\Model\Rule\LocationOptionsProvider $locationOptions
     * @param \Mageplaza\AutoRelated\Model\ResourceModel\RuleFactory $resourceModelRuleFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataObject $objectConverter,
        Store $systemStore,
        LocationOptionsProvider $locationOptions,
        RuleFactory $resourceModelRuleFactory,
        array $data = []
    )
    {
        $this->systemStore              = $systemStore;
        $this->_groupRepository         = $groupRepository;
        $this->_searchCriteriaBuilder   = $searchCriteriaBuilder;
        $this->_objectConverter         = $objectConverter;
        $this->locationOptions          = $locationOptions;
        $this->resourceModelRuleFactory = $resourceModelRuleFactory;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @inheritdoc
     */
    protected function _prepareForm()
    {
        $model         = $this->_coreRegistry->registry('autorelated_rule');
        $resourceModel = $this->resourceModelRuleFactory->create();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('general_rule_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Rule Information')]);

        if ($model->getId()) {
            $fieldset->addField('rule_id', 'hidden', ['name' => 'rule_id']);
        }

        $fieldset->addField('name', 'text', [
                'name'     => 'name',
                'label'    => __('Rule Name'),
                'title'    => __('Rule Name'),
                'note'     => __('Enter the rule\'s name here, it\'s only visible in the backend.'),
                'required' => true
            ]
        );

        $fieldset->addField('location', 'select', [
                'name'   => 'location',
                'label'  => __('Location'),
                'title'  => __('Location'),
                'values' => $this->locationOptions->toOptionArray(),
                'note'   => __('Select the position to display block.')
            ]
        );

        $fieldset->addField('is_active', 'select', [
                'label'    => __('Status'),
                'title'    => __('Status'),
                'name'     => 'is_active',
                'required' => true,
                'options'  => [
                    '1' => __('Active'),
                    '0' => __('Inactive')
                ],
                'note'     => __('Select Active to enable the rule.')
            ]
        );
        if (!$model->getId()) {
            $model->setData('is_active', 1);
        }

        if (!$this->_storeManager->isSingleStoreMode()) {
            /** @var \Magento\Framework\Data\Form\Element\Renderer\RendererInterface $rendererBlock */
            $rendererBlock = $this->getLayout()->createBlock('Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element');
            $fieldset->addField('store_ids', 'multiselect', [
                'name'     => 'store_ids',
                'label'    => __('Store Views'),
                'title'    => __('Store Views'),
                'required' => true,
                'values'   => $this->systemStore->getStoreValuesForForm(false, true)
            ])->setRenderer($rendererBlock);
        } else {
            $fieldset->addField('store_ids', 'hidden', [
                'name'  => 'store_ids',
                'value' => $this->_storeManager->getStore()->getId()
            ]);
        }
        $model->setData('store_ids', $resourceModel->getStoresByRuleId($model->getId()));

        $model->setData('customer_group_ids', $resourceModel->getCustomerGroupByRuleId($model->getId()));
        $customerGroups = $this->_groupRepository->getList($this->_searchCriteriaBuilder->create())->getItems();
        $fieldset->addField('customer_group_ids', 'multiselect', [
                'name'     => 'customer_group_ids[]',
                'label'    => __('Customer Groups'),
                'title'    => __('Customer Groups'),
                'required' => true,
                'values'   => $this->_objectConverter->toOptionArray($customerGroups, 'id', 'code'),
                'note'     => __('Select customer group(s) to display the block to')
            ]
        );

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $fieldset->addField('from_date', 'date', [
                'name'         => 'from_date',
                'label'        => __('From'),
                'title'        => __('From'),
                'input_format' => DateTime::DATE_INTERNAL_FORMAT,
                'date_format'  => $dateFormat
            ]
        );
        $fieldset->addField('to_date', 'date', [
                'name'         => 'to_date',
                'label'        => __('To'),
                'title'        => __('To'),
                'input_format' => DateTime::DATE_INTERNAL_FORMAT,
                'date_format'  => $dateFormat
            ]
        );

        $fieldset->addField('sort_order', 'text', [
                'name'  => 'sort_order',
                'label' => __('Priority'),
                'note'  => __('Enter a number to set priority for the rule. A lower number represents a higher priority.')
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare content for tab
     *
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabLabel()
    {
        return __('Rule Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('Rule Information');
    }

    /**
     * Returns status flag about this tab can be showed or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function isHidden()
    {
        return false;
    }
}
