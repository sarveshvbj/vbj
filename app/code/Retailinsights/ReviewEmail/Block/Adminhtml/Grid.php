<?php 

namespace Retailinsights\ReviewEmail\Block\Adminhtml;

class Grid extends \Magento\Review\Block\Adminhtml\Grid
{
    /**
     * @return $this|\Magento\Backend\Block\Widget\Grid
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->addColumn(
            'email',
            [
                'header'       => __('email'),
                'filter_index' => 'rdt.email',
                'index'        => 'email',
                'type'         => 'text',
                'truncate'     => 50,
                'escape'       => true,
            ]
        );

        return $this;
     }
 }