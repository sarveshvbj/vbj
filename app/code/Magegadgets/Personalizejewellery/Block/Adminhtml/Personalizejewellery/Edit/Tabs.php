<?php
namespace Magegadgets\Personalizejewellery\Block\Adminhtml\Personalizejewellery\Edit;

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
        $this->setId('personalizejewellery_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Personalizejewellery Information'));
    }
}