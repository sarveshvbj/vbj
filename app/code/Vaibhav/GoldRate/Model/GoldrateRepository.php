<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vaibhav\GoldRate\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vaibhav\GoldRate\Api\Data\GoldrateInterface;
use Vaibhav\GoldRate\Api\Data\GoldrateInterfaceFactory;
use Vaibhav\GoldRate\Api\Data\GoldrateSearchResultsInterfaceFactory;
use Vaibhav\GoldRate\Api\GoldrateRepositoryInterface;
use Vaibhav\GoldRate\Model\ResourceModel\Goldrate as ResourceGoldrate;
use Vaibhav\GoldRate\Model\ResourceModel\Goldrate\CollectionFactory as GoldrateCollectionFactory;

class GoldrateRepository implements GoldrateRepositoryInterface
{

    /**
     * @var ResourceGoldrate
     */
    protected $resource;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var GoldrateCollectionFactory
     */
    protected $goldrateCollectionFactory;

    /**
     * @var Goldrate
     */
    protected $searchResultsFactory;

    /**
     * @var GoldrateInterfaceFactory
     */
    protected $goldrateFactory;


    /**
     * @param ResourceGoldrate $resource
     * @param GoldrateInterfaceFactory $goldrateFactory
     * @param GoldrateCollectionFactory $goldrateCollectionFactory
     * @param GoldrateSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceGoldrate $resource,
        GoldrateInterfaceFactory $goldrateFactory,
        GoldrateCollectionFactory $goldrateCollectionFactory,
        GoldrateSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->goldrateFactory = $goldrateFactory;
        $this->goldrateCollectionFactory = $goldrateCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(GoldrateInterface $goldrate)
    {
        try {
            $this->resource->save($goldrate);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the goldrate: %1',
                $exception->getMessage()
            ));
        }
        return $goldrate;
    }

    /**
     * @inheritDoc
     */
    public function get($goldrateId)
    {
        $goldrate = $this->goldrateFactory->create();
        $this->resource->load($goldrate, $goldrateId);
        if (!$goldrate->getId()) {
            throw new NoSuchEntityException(__('Goldrate with id "%1" does not exist.', $goldrateId));
        }
        return $goldrate;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->goldrateCollectionFactory->create();
        
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
    public function delete(GoldrateInterface $goldrate)
    {
        try {
            $goldrateModel = $this->goldrateFactory->create();
            $this->resource->load($goldrateModel, $goldrate->getGoldrateId());
            $this->resource->delete($goldrateModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Goldrate: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($goldrateId)
    {
        return $this->delete($this->get($goldrateId));
    }
}

