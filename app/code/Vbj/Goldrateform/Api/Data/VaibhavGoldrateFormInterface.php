<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vbj\Goldrateform\Api\Data;

interface VaibhavGoldrateFormInterface
{

    const VAIBHAV_GOLDRATE_FORM_ID = 'vaibhav_goldrate_form_id';
    const CUSTOMER_MOBILE = 'customer_mobile';
    const CUSTOMER_AREA = 'customer_area';
    const CUSTOMER_NAME = 'customer_name';
    const CUSTOMER_EMAIL = 'customer_email';

    /**
     * Get vaibhav_goldrate_form_id
     * @return string|null
     */
    public function getVaibhavGoldrateFormId();

    /**
     * Set vaibhav_goldrate_form_id
     * @param string $vaibhavGoldrateFormId
     * @return \Vbj\Goldrateform\VaibhavGoldrateForm\Api\Data\VaibhavGoldrateFormInterface
     */
    public function setVaibhavGoldrateFormId($vaibhavGoldrateFormId);

    /**
     * Get customer_name
     * @return string|null
     */
    public function getCustomerName();

    /**
     * Set customer_name
     * @param string $customerName
     * @return \Vbj\Goldrateform\VaibhavGoldrateForm\Api\Data\VaibhavGoldrateFormInterface
     */
    public function setCustomerName($customerName);

    /**
     * Get customer_email
     * @return string|null
     */
    public function getCustomerEmail();

    /**
     * Set customer_email
     * @param string $customerEmail
     * @return \Vbj\Goldrateform\VaibhavGoldrateForm\Api\Data\VaibhavGoldrateFormInterface
     */
    public function setCustomerEmail($customerEmail);

    /**
     * Get customer_area
     * @return string|null
     */
    public function getCustomerArea();

    /**
     * Set customer_area
     * @param string $customerArea
     * @return \Vbj\Goldrateform\VaibhavGoldrateForm\Api\Data\VaibhavGoldrateFormInterface
     */
    public function setCustomerArea($customerArea);

    /**
     * Get customer_mobile
     * @return string|null
     */
    public function getCustomerMobile();

    /**
     * Set customer_mobile
     * @param string $customerMobile
     * @return \Vbj\Goldrateform\VaibhavGoldrateForm\Api\Data\VaibhavGoldrateFormInterface
     */
    public function setCustomerMobile($customerMobile);
}

