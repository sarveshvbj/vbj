<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vbj\Goldrateform\Model;

use Magento\Framework\Model\AbstractModel;
use Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormInterface;

class VaibhavGoldrateForm extends AbstractModel implements VaibhavGoldrateFormInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Vbj\Goldrateform\Model\ResourceModel\VaibhavGoldrateForm::class);
    }

    /**
     * @inheritDoc
     */
    public function getVaibhavGoldrateFormId()
    {
        return $this->getData(self::VAIBHAV_GOLDRATE_FORM_ID);
    }

    /**
     * @inheritDoc
     */
    public function setVaibhavGoldrateFormId($vaibhavGoldrateFormId)
    {
        return $this->setData(self::VAIBHAV_GOLDRATE_FORM_ID, $vaibhavGoldrateFormId);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerName()
    {
        return $this->getData(self::CUSTOMER_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerName($customerName)
    {
        return $this->setData(self::CUSTOMER_NAME, $customerName);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerEmail($customerEmail)
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerArea()
    {
        return $this->getData(self::CUSTOMER_AREA);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerArea($customerArea)
    {
        return $this->setData(self::CUSTOMER_AREA, $customerArea);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerMobile()
    {
        return $this->getData(self::CUSTOMER_MOBILE);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerMobile($customerMobile)
    {
        return $this->setData(self::CUSTOMER_MOBILE, $customerMobile);
    }
}

