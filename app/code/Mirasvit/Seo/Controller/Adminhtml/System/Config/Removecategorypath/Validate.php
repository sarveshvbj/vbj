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



namespace Mirasvit\Seo\Controller\Adminhtml\System\Config\Removecategorypath;

abstract class Validate extends \Magento\Backend\App\Action
{
    /** Check category duplicate urls
     *
     * @return \Mirasvit\Seo\Model\Removecategorypath\Validate
     */
    protected function _validate()
    {
        return $this->_objectManager->get('Mirasvit\Seo\Model\Removecategorypath\Validate')
                                    ->checkCategoyUrlsDuplicate();
    }
}
