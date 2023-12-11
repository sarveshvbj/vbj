<?php
namespace Webkul\GiftCard\Model;

use Webkul\GiftCard\Model\ResourceModel\GiftUser\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $contactCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $contactCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        $this->loadedData = array();

        foreach ($items as $giftUserData) {
            $this->loadedData[$giftUserData->getGiftcodeid()]['giftuser_form'] = $giftUserData->getData();
        }
        return $this->loadedData;

    }
}