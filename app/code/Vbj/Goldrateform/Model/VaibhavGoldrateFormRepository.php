<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vbj\Goldrateform\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormInterface;
use Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormInterfaceFactory;
use Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormSearchResultsInterfaceFactory;
use Vbj\Goldrateform\Api\VaibhavGoldrateFormRepositoryInterface;
use Vbj\Goldrateform\Model\ResourceModel\VaibhavGoldrateForm as ResourceVaibhavGoldrateForm;
use Vbj\Goldrateform\Model\ResourceModel\VaibhavGoldrateForm\CollectionFactory as VaibhavGoldrateFormCollectionFactory;

class VaibhavGoldrateFormRepository implements VaibhavGoldrateFormRepositoryInterface
{

    /**
     * @var VaibhavGoldrateForm
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var VaibhavGoldrateFormCollectionFactory
     */
    protected $vaibhavGoldrateFormCollectionFactory;

    /**
     * @var ResourceVaibhavGoldrateForm
     */
    protected $resource;

    /**
     * @var VaibhavGoldrateFormInterfaceFactory
     */
    protected $vaibhavGoldrateFormFactory;


    /**
     * @param ResourceVaibhavGoldrateForm $resource
     * @param VaibhavGoldrateFormInterfaceFactory $vaibhavGoldrateFormFactory
     * @param VaibhavGoldrateFormCollectionFactory $vaibhavGoldrateFormCollectionFactory
     * @param VaibhavGoldrateFormSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceVaibhavGoldrateForm $resource,
        VaibhavGoldrateFormInterfaceFactory $vaibhavGoldrateFormFactory,
        VaibhavGoldrateFormCollectionFactory $vaibhavGoldrateFormCollectionFactory,
        VaibhavGoldrateFormSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->vaibhavGoldrateFormFactory = $vaibhavGoldrateFormFactory;
        $this->vaibhavGoldrateFormCollectionFactory = $vaibhavGoldrateFormCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(
        VaibhavGoldrateFormInterface $vaibhavGoldrateForm
    ) {
        try {
            $this->resource->save($vaibhavGoldrateForm);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the vaibhavGoldrateForm: %1',
                $exception->getMessage()
            ));
        }
        return $vaibhavGoldrateForm;
    }

    /**
     * @inheritDoc
     */
    public function get($vaibhavGoldrateFormId)
    {
        $vaibhavGoldrateForm = $this->vaibhavGoldrateFormFactory->create();
        $this->resource->load($vaibhavGoldrateForm, $vaibhavGoldrateFormId);
        if (!$vaibhavGoldrateForm->getId()) {
            throw new NoSuchEntityException(__('vaibhav_goldrate_form with id "%1" does not exist.', $vaibhavGoldrateFormId));
        }
        return $vaibhavGoldrateForm;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->vaibhavGoldrateFormCollectionFactory->create();
        
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
    public function delete(
        VaibhavGoldrateFormInterface $vaibhavGoldrateForm
    ) {
        try {
            $vaibhavGoldrateFormModel = $this->vaibhavGoldrateFormFactory->create();
            $this->resource->load($vaibhavGoldrateFormModel, $vaibhavGoldrateForm->getVaibhavGoldrateFormId());
            $this->resource->delete($vaibhavGoldrateFormModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the vaibhav_goldrate_form: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($vaibhavGoldrateFormId)
    {
        return $this->delete($this->get($vaibhavGoldrateFormId));
    }
}

