<?php
declare(strict_types=1);

namespace Vaibhav\Exchangerate\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vaibhav\Exchangerate\Api\Data\FourteenrateInterface;
use Vaibhav\Exchangerate\Api\Data\FourteenrateInterfaceFactory;
use Vaibhav\Exchangerate\Api\Data\FourteenrateSearchResultsInterfaceFactory;
use Vaibhav\Exchangerate\Api\FourteenrateRepositoryInterface;
use Vaibhav\Exchangerate\Model\ResourceModel\Fourteenrate as ResourceFourteenrate;
use Vaibhav\Exchangerate\Model\ResourceModel\Fourteenrate\CollectionFactory as FourteenrateCollectionFactory;

class FourteenrateRepository implements FourteenrateRepositoryInterface
{

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var Fourteenrate
     */
    protected $searchResultsFactory;

    /**
     * @var FourteenrateInterfaceFactory
     */
    protected $fourteenrateFactory;

    /**
     * @var ResourceFourteenrate
     */
    protected $resource;

    /**
     * @var FourteenrateCollectionFactory
     */
    protected $fourteenrateCollectionFactory;


    /**
     * @param ResourceFourteenrate $resource
     * @param FourteenrateInterfaceFactory $fourteenrateFactory
     * @param FourteenrateCollectionFactory $fourteenrateCollectionFactory
     * @param FourteenrateSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceFourteenrate $resource,
        FourteenrateInterfaceFactory $fourteenrateFactory,
        FourteenrateCollectionFactory $fourteenrateCollectionFactory,
        FourteenrateSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->fourteenrateFactory = $fourteenrateFactory;
        $this->fourteenrateCollectionFactory = $fourteenrateCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(FourteenrateInterface $fourteenrate)
    {
        try {
            $this->resource->save($fourteenrate);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the fourteenrate: %1',
                $exception->getMessage()
            ));
        }
        return $fourteenrate;
    }

    /**
     * @inheritDoc
     */
    public function get($fourteenrateId)
    {
        $fourteenrate = $this->fourteenrateFactory->create();
        $this->resource->load($fourteenrate, $fourteenrateId);
        if (!$fourteenrate->getId()) {
            throw new NoSuchEntityException(__('fourteenrate with id "%1" does not exist.', $fourteenrateId));
        }
        return $fourteenrate;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->fourteenrateCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(FourteenrateInterface $fourteenrate)
    {
        try {
            $fourteenrateModel = $this->fourteenrateFactory->create();
            $this->resource->load($fourteenrateModel, $fourteenrate->getFourteenrateId());
            $this->resource->delete($fourteenrateModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the fourteenrate: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($fourteenrateId)
    {
        return $this->delete($this->get($fourteenrateId));
    }
}

