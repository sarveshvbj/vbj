<?php
namespace Magegadgets\Loosediamonds\Block\Adminhtml\Loosediamonds\Edit;

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
        $this->setId('loosediamonds_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Diamond Information'));
    }
}