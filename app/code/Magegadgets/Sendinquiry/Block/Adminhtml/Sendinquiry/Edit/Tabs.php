<?php
namespace Magegadgets\Sendinquiry\Block\Adminhtml\Sendinquiry\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('sendinquiry_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Sendinquiry Information'));
    }
}