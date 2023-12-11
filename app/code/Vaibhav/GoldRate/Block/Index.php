<?php

namespace Vaibhav\GoldRate\Block;

use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Template\Context;
use Vaibhav\GoldRate\Model\ResourceModel\Goldrate\CollectionFactory;


class Index extends Template
{

    public $collection;
    protected $_goldrateFactory;

    public function __construct(Context $context, CollectionFactory $collectionFactory, \Vaibhav\GoldRate\Model\GoldrateFactory $goldrateFactory, array $data = [])
    {
        $this->collection = $collectionFactory;
        $this->_goldrateFactory = $goldrateFactory;
        parent::__construct($context, $data);
    }

    public function getCollection()
    {
        //return $this->collection->create();
        $post = $this->_goldrateFactory->create();
        $collection = $post->getCollection();
        return $collection;
    }

}