<?php
namespace Magegadgets\Pricemanger\Block\Product;

class Specification extends \Magento\Catalog\Block\Product\View
{
     /**
      * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory
      */
     protected $groupCollectionFactory;

     public function __construct(
         \Magento\Catalog\Block\Product\Context $context,
         \Magento\Framework\Url\EncoderInterface $urlEncoder,
         \Magento\Framework\Json\EncoderInterface $jsonEncoder,
         \Magento\Framework\Stdlib\StringUtils $string,
         \Magento\Catalog\Helper\Product $productHelper,
         \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
         \Magento\Framework\Locale\FormatInterface $localeFormat,
         \Magento\Customer\Model\Session $customerSession,
         \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
         \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
         \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory $groupCollectionFactory,
         array $data = []
    ) {
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string,
        $productHelper, $productTypeConfig, $localeFormat, $customerSession,
        $productRepository, $priceCurrency, $data);
        $this->groupCollectionFactory = $groupCollectionFactory;
    }


    public function getAttributeLabels()
     {
		$product = $this->getProduct();

		$attributeSetId = $product->getAttributeSetId();

		$groupCollection = $this->groupCollectionFactory->create()
        ->setAttributeSetFilter($attributeSetId)
        ->setSortOrder()
        ->load();
		return $groupCollection;
	}
}