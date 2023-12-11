<?php
namespace Magegadgets\Videoform\Block\Adminhtml\Videoform\Edit;

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
        $this->setId('videoform_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Videoform Information'));
    }
}