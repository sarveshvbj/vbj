<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_StoreOptimization
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\StoreOptimization\Observer;

use Magento\Framework\Event\ObserverInterface;
use Webkul\StoreOptimization\Helper\Data;

class DeferLoadJs implements ObserverInterface
{
    protected $_helper;

    public function __construct(
        Data $helper
    ) {
        $this->_helper = $helper;
    }

    /**
     * add script tags in the bottom of the page
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_helper->getIsDeferLoadingEnable()) {
            $response = $observer->getEvent()->getData('response');

            if (!$response) {
                return;
            }
            $html = $response->getBody();
            if ($html == '') {
                return;
            }

            $findScriptPattern = '@(?:<script type="text/javascript"|<script)(.*)</script>@msU';
            preg_match_all($findScriptPattern, $html, $matches);
            $combinedJs = implode('', $matches[0]);
            $html = preg_replace($findScriptPattern, '', $html);
            $html .= $combinedJs;

            $response->setBody($html);
        }
    }
}
