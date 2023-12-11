<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_GiftCard
 * @author    Webkul Software Private Limited
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\GiftCard\Model;
 
use Magento\Framework\Model\AbstractModel;
 
class GiftDetail extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Webkul\GiftCard\Model\ResourceModel\GiftDetail');
    }
}
