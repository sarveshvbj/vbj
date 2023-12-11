<?php
declare(strict_types=1);

namespace Vaibhav\Exchangerate\Model;

use Magento\Framework\Model\AbstractModel;
use Vaibhav\Exchangerate\Api\Data\FourteenrateInterface;

class Fourteenrate extends AbstractModel implements FourteenrateInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Vaibhav\Exchangerate\Model\ResourceModel\Fourteenrate::class);
    }

    /**
     * @inheritDoc
     */
    public function getFourteenrateId()
    {
        return $this->getData(self::FOURTEENRATE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setFourteenrateId($fourteenrateId)
    {
        return $this->setData(self::FOURTEENRATE_ID, $fourteenrateId);
    }

    /**
     * @inheritDoc
     */
    public function getFourteenRate()
    {
        return $this->getData(self::FOURTEEN_RATE);
    }

    /**
     * @inheritDoc
     */
    public function setFourteenRate($fourteenRate)
    {
        return $this->setData(self::FOURTEEN_RATE, $fourteenRate);
    }

    /**
     * @inheritDoc
     */
    public function getEighteenRate()
    {
        return $this->getData(self::EIGHTEEN_RATE);
    }

    /**
     * @inheritDoc
     */
    public function setEighteenRate($eighteenRate)
    {
        return $this->setData(self::EIGHTEEN_RATE, $eighteenRate);
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
}

