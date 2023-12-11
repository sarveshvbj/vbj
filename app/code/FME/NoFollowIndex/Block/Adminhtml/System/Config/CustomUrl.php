<?php

/**
 * Class for NoFollowIndex CustomUrl
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Block\Adminhtml\System\Config;

class CustomUrl extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{

    protected $_groupRenderer;
    protected $_selectRenderer;
    protected $_isenableselectRenderer;
    protected $_noarchiveselectRenderer;
    protected $_columns = [];
    protected $_addAfter = true;

    protected function _getGroupRenderer()
    {
        if (!$this->_groupRenderer) {
            $this->_groupRenderer = $this->getLayout()->createBlock(
                \FME\NoFollowIndex\Block\Adminhtml\Form\Field\Fieldsgroup::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_groupRenderer->setClass('formfields_group_select');
        }
        return $this->_groupRenderer;
    }

    protected function _getSelectRenderer()
    {
        if (!$this->_selectRenderer) {
            $this->_selectRenderer = $this->getLayout()->createBlock(
                \FME\NoFollowIndex\Block\Adminhtml\Form\Field\SelectGroup::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_selectRenderer->setClass('formfields_option_select');
        }
        return $this->_selectRenderer;
    }

    protected function _getEnableSelectRenderer()
    {
        if (!$this->_isenableselectRenderer) {
            $this->_isenableselectRenderer = $this->getLayout()->createBlock(
                \FME\NoFollowIndex\Block\Adminhtml\Form\Field\IsEnableSelect::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_isenableselectRenderer->setClass('formfields_isenable_select');
        }
        return $this->_isenableselectRenderer;
    }

    protected function _getNoArchiveSelectRenderer()
    {
        if (!$this->_noarchiveselectRenderer) {
            $this->_noarchiveselectRenderer = $this->getLayout()->createBlock(
                \FME\NoFollowIndex\Block\Adminhtml\Form\Field\NoArchiveSelect::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_noarchiveselectRenderer->setClass('formfields_noarchive_select');
        }
        return $this->_noarchiveselectRenderer;
    }

    protected function _prepareToRender()
    {
        $this->addColumn('label', ['label' => __('URL'), 'style' => 'width:125px;']);
        $this->addColumn('type', ['label' => __('Follow Value'), 'renderer' => $this->_getGroupRenderer()]);
        $this->addColumn('required', ['label' => __('Index Value'), 'renderer' => $this->_getSelectRenderer()]);
        $this->addColumn('noarchive', ['label' => __('No Archive'), 'renderer' => $this->_getNoArchiveSelectRenderer()]);
        $this->addColumn('enable', ['label' => __('Enable'), 'renderer' => $this->_getEnableSelectRenderer()]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    public function renderCellTemplate($columnName)
    {
        if ($columnName == "type") {
            $this->_columns[$columnName]['style'] = 'width:150px';
        }
        return parent::renderCellTemplate($columnName);
    }

    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $optionExtraAttr = [];
        $optionExtraAttr['option_' . $this->_getGroupRenderer()->calcOptionHash($row->getData('type'))] =
            'selected="selected"';
        $optionExtraAttr['option_' . $this->_getSelectRenderer()->calcOptionHash($row->getData('required'))] =
            'selected="selected"';
        $optionExtraAttr['option_' . $this->_getEnableSelectRenderer()->calcOptionHash($row->getData('enable'))] =
            'selected="selected"';
        $optionExtraAttr['option_' . $this->_getNoArchiveSelectRenderer()->calcOptionHash($row->getData('noarchive'))] =
            'selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }
}
