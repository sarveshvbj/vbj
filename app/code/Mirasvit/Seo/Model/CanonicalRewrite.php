<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-seo
 * @version   2.6.1
 * @copyright Copyright (C) 2023 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Seo\Model;

use Magento\Framework\Data\FormFactory;
use Magento\Framework\Model\Context;
use Mirasvit\Seo\Model\ResourceModel\CanonicalRewrite as CanonicalRewriteResource;
use Mirasvit\Seo\Model\ResourceModel\CanonicalRewrite\Collection;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Rule\Model\AbstractModel;
use Magento\Rule\Model\Action\CollectionFactory;
use Mirasvit\Seo\Api\Data\CanonicalRewriteInterface;
use Mirasvit\Seo\Model\CanonicalRewrite\Rule\Condition\CombineFactory;
use Mirasvit\Core\Service\SerializeService;

class CanonicalRewrite extends AbstractModel implements CanonicalRewriteInterface
{
    /**
     * @var CollectionFactory
     */
    private $ruleActionCollectionFactory;
    /**
     * @var CombineFactory
     */
    private $ruleConditionCombineFactory;
    /**
     * @var Collection
     */
    private $resourceCollection;
    /**
     * @var CanonicalRewriteResource
     */
    private $resource;
    /**
     * @var Registry
     */
    private $registry;
    /**
     * @var Context
     */
    private $context;

    /**
     * CanonicalRewrite constructor.
     * @param CombineFactory $ruleConditionCombineFactory
     * @param CollectionFactory $ruleActionCollectionFactory
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param TimezoneInterface $localeDate
     * @param CanonicalRewriteResource|null $resource
     * @param Collection|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        CombineFactory $ruleConditionCombineFactory,
        CollectionFactory $ruleActionCollectionFactory,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        TimezoneInterface $localeDate,
        CanonicalRewriteResource $resource = null,
        Collection $resourceCollection = null,
        array $data = []
    ) {
        $this->ruleConditionCombineFactory = $ruleConditionCombineFactory;
        $this->ruleActionCollectionFactory = $ruleActionCollectionFactory;
        $this->context                     = $context;
        $this->registry                    = $registry;
        $this->resource                    = $resource;
        $this->resourceCollection          = $resourceCollection;

        parent::__construct($context, $registry, $formFactory, $localeDate, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Mirasvit\Seo\Model\ResourceModel\CanonicalRewrite::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsActive($value)
    {
        return $this->setData(self::IS_ACTIVE, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getCanonical()
    {
        return $this->getData(self::CANONICAL);
    }

    /**
     * {@inheritdoc}
     */
    public function setCanonical($value)
    {
        return $this->setData(self::CANONICAL, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getRegExpression()
    {
        return $this->getData(self::REG_EXPRESSION);
    }

    /**
     * {@inheritdoc}
     */
    public function setRegExpression($value)
    {
        return $this->setData(self::REG_EXPRESSION, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getConditionsSerialized()
    {
        return $this->getData(self::CONDITIONS_SERIALIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function setConditionsSerialized($value)
    {
        return $this->setData(self::CONDITIONS_SERIALIZED, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getActionsSerialized()
    {
        return $this->getData(self::ACTIONS_SERIALIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function setActionsSerialized($value)
    {
        return $this->setData(self::ACTIONS_SERIALIZED, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * {@inheritdoc}
     */
    public function setSortOrder($value)
    {
        return $this->setData(self::SORT_ORDER, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getComments()
    {
        return $this->getData(self::COMMENTS);
    }

    /**
     * {@inheritdoc}
     */
    public function setComments($value)
    {
        return $this->setData(self::COMMENTS, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getConditionsInstance()
    {
        return $this->ruleConditionCombineFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function getActionsInstance()
    {
        return $this->ruleActionCollectionFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function getConditions()
    {
        if (empty($this->_conditions)) {
            $this->_resetConditions();
        }

        // Load rule conditions if it is applicable
        if ($this->hasConditionsSerialized()) {
            $conditions = $this->getConditionsSerialized();
            if (!empty($conditions)) {
                $conditions = SerializeService::decode($conditions);
                if (is_array($conditions) && !empty($conditions)) {
                    $this->_conditions->loadArray($conditions);
                }
            }
            $this->unsConditionsSerialized();
        }

        return $this->_conditions;
    }
}
