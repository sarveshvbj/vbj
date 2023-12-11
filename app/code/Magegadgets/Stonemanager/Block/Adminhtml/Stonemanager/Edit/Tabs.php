<?php
namespace Magegadgets\Stonemanager\Block\Adminhtml\Stonemanager\Edit;

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
        $this->setId('stonemanager_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Stone Information'));
    }
}