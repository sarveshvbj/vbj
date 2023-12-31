<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_SocialLogin
 * @copyright   Copyright (c) Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\SocialLogin\Helper;

use Magento\Framework\App\RequestInterface;
use Mageplaza\Core\Helper\AbstractData as CoreHelper;

/**
 * Class Data
 *
 * @package Mageplaza\SocialLogin\Helper
 */
class Data extends CoreHelper
{
    const CONFIG_MODULE_PATH = 'sociallogin';
    const XML_PATH_GENERAL_ENABLED = 'sociallogin/general/is_enabled';
    const XML_PATH_GENERAL = 'sociallogin/general/';

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param                                         $formId
     * @return string
     */
    public function captchaResolve(RequestInterface $request, $formId)
    {
        $captchaParams = $request->getPost(\Magento\Captcha\Helper\Data::INPUT_NAME_FIELD_VALUE);

        return isset($captchaParams[$formId]) ? $captchaParams[$formId] : '';
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function canSendPassword($storeId = null)
    {
        return $this->getGeneralConfig('send_password', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getPopupEffect($storeId = null)
    {
        return $this->getGeneralConfig('popup_effect', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getStyleManagement($storeId = null)
    {
        $style = $this->getGeneralConfig('style_management', $storeId);
        if ($style == 'custom') {
            return $this->getCustomColor($storeId);
        }

        return $style;
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getCustomColor($storeId = null)
    {
        return $this->getGeneralConfig('custom_color', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getCustomCss($storeId = null)
    {
        return $this->getGeneralConfig('custom_css', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function requireRealEmail($storeId = null)
    {
        return $this->getGeneralConfig('fake_email_require', $storeId);
    }

    /**
     * @return mixed
     */
    public function isSecure()
    {
        $isSecure = $this->getConfigValue('web/secure/use_in_frontend');

        return $isSecure;
    }
    public function isEnabled($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GENERAL_ENABLED, $storeId);
    }
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GENERAL . $code, $storeId);
    }
}
