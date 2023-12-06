<?php

namespace Vbj\Goldrateform\Plugin;

/**
 *
 */
class LoginPostPlugin
{

    /**
     * Change redirect after login to home instead of dashboard.
     *
     * @param \Magento\Customer\Controller\Account\LoginPost $subject
     * @param \Magento\Framework\Controller\Result\Redirect $result
     */
    public function afterExecute(
        \Magento\Customer\Controller\Account\LoginPost $subject,
        $result)
    {
        $customUrl = 'customer/account/forgotpassword';
        $result->setPath($customUrl);
        return $result;
    }

}