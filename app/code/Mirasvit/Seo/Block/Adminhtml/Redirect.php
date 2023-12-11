<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-seo
 * @version   2.6.1
 * @copyright Copyright (C) 2023 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Seo\Block\Adminhtml;

class Redirect extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_redirect';
        $this->_blockGroup = 'Mirasvit_Seo';
        $this->_headerText = __('Redirect Manager');
        $this->_addButtonLabel = __('Add New Redirect');

        $this->buttonList->add('import', [
            'label' => __('Import/Export Redirects'),
            'onclick' => "setLocation('" . $this->getUrl('seo/redirectImportExport/index') . "')",
        ]);

        parent::_construct();
    }

    /**
     * @return string
     */
    public function getCreateUrl()
    {
        return $this->getUrl('*/*/add');
    }

}
