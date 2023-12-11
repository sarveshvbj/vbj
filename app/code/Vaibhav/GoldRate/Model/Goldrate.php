<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vaibhav\GoldRate\Model;

use Magento\Framework\Model\AbstractModel;
use Vaibhav\GoldRate\Api\Data\GoldrateInterface;

class Goldrate extends AbstractModel implements GoldrateInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Vaibhav\GoldRate\Model\ResourceModel\Goldrate::class);
    }

    /**
     * @inheritDoc
     */
    public function getGoldrateId()
    {
        return $this->getData(self::GOLDRATE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setGoldrateId($goldrateId)
    {
        return $this->setData(self::GOLDRATE_ID, $goldrateId);
    }

    /**
     * @inheritDoc
     */
    public function getCity()
    {
        return $this->getData(self::CITY);
    }

    /**
     * @inheritDoc
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * @inheritDoc
     */
    public function getTwentytwoRate()
    {
        return $this->getData(self::TWENTYTWO_RATE);
    }

    /**
     * @inheritDoc
     */
    public function setTwentytwoRate($twentytwoRate)
    {
        return $this->setData(self::TWENTYTWO_RATE, $twentytwoRate);
    }

    /**
     * @inheritDoc
     */
    public function getTwentyfourRate()
    {
        return $this->getData(self::TWENTYFOUR_RATE);
    }

    /**
     * @inheritDoc
     */
    public function setTwentyfourRate($twentyfourRate)
    {
        return $this->setData(self::TWENTYFOUR_RATE, $twentyfourRate);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}

