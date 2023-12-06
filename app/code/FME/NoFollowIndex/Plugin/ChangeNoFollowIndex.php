<?php

/**
 * Class for NoFollowIndex ChangeNoFollowIndex
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Plugin;

class ChangeNoFollowIndex
{
	protected $_helper;
  protected $scopeConfig;
  protected $request;
  protected $_page;
	protected $_objectManager;
	protected $nofollowFactory;

	public function __construct (
		\FME\NoFollowIndex\Helper\Data $helper,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Cms\Model\Page $page,
    \Magento\Framework\App\Request\Http $request,
		\Magento\Framework\ObjectManagerInterface $objectmanager,
		\FME\NoFollowIndex\Model\NoFollowFactory $nofollowFactory
	)
	{
		$this->_helper = $helper;
    $this->_page = $page;
    $this->request = $request;
    $this->scopeConfig = $scopeConfig;
		$this->_objectManager = $objectmanager;
		$this->nofollowFactory = $nofollowFactory;
	}

  public function afterGetRobots($subject, $result)
  {
    if ($this->_helper->enableNoFollowIndexExtension() == 1)
    {
      // extension is enabled so returning nofollowindex value based on your field values
      $fullactionname = $this->request->getfullactionname();

      // ***** nofollowindex tags for CMS pages
      if ($fullactionname == 'cms_page_view' || $fullactionname == 'cms_index_index')
      {
        // if our configuration is set to Yes then we'll look for fields value
        if ($this->_helper->enableNoFollowIndexForCMS() == 1)
        {
					$followFactory = $this->nofollowFactory->create();
					$nofollow = $followFactory->getCollection()
											->addIdandTypeFilter($this->_page->getId(), 'cms');
					$nofollow = $nofollow->getData();

          // if our table has a record that means fields are saved
          if (sizeof($nofollow) > 0)
          {
						// if value of enable field is set to yes then we'll grab tag value from fields and set it
						if ($nofollow[0]['nofollowindex_itemenablevalue'] == 1)
						{
							if ($nofollow[0]['nofollowindex_itemnoarchivevalue'] == 1)
							{
								return $this->getFollowValue($nofollow[0]['nofollowindex_itemfollowvalue'])
									. ',' . $this->getIndexValue($nofollow[0]['nofollowindex_itemindexvalue'])
									. ',' . $this->getArchiveValue($nofollow[0]['nofollowindex_itemnoarchivevalue']);
							}
							return $this->getFollowValue($nofollow[0]['nofollowindex_itemfollowvalue'])
								. ',' . $this->getIndexValue($nofollow[0]['nofollowindex_itemindexvalue']);
						}
          }
        }
      }
      // ***** nofollowindex tags are added to CMS pages

      // ***** nofollowindex tags for Category pages
      if ($fullactionname == 'catalog_category_view')
      {
				// if our configuration is set to Yes then we'll look for fields value
        if ($this->_helper->enableNoFollowIndexForCategories() == 1)
        {
					$category = $this->_objectManager->get('Magento\Framework\Registry')->registry('current_category');
					//echo '<pre>';
					$filter = $category->getData();
					//print_r($filter);
					//echo '</pre>';
					if(array_key_exists("weight_ragens",$filter) || array_key_exists("price_filter",$filter) || array_key_exists("purity",$filter) || array_key_exists("product_type",$filter) || array_key_exists("product_category_type",$filter)){
						return $this->getFollowValue(0)
								. ',' . $this->getIndexValue(0);
					}
					
					//die('sarvesh');
					$followFactory = $this->nofollowFactory->create();
					$nofollow = $followFactory->getCollection()
											->addIdandTypeFilter($category->getId(), 'category');
					$nofollow = $nofollow->getData();

          // if our table has a record that means fields are saved
          if (sizeof($nofollow) > 0)
          {
						// if value of enable field is set to yes then we'll grab tag value from fields and set it
						if ($nofollow[0]['nofollowindex_itemenablevalue'] == 1)
						{
							if ($nofollow[0]['nofollowindex_itemnoarchivevalue'] == 1)
							{
								return $this->getFollowValue($nofollow[0]['nofollowindex_itemfollowvalue'])
									. ',' . $this->getIndexValue($nofollow[0]['nofollowindex_itemindexvalue'])
									. ',' . $this->getArchiveValue($nofollow[0]['nofollowindex_itemnoarchivevalue']);
							}
							return $this->getFollowValue($nofollow[0]['nofollowindex_itemfollowvalue'])
								. ',' . $this->getIndexValue($nofollow[0]['nofollowindex_itemindexvalue']);
						}
          }
        }
      }
      // ***** nofollowindex tags are added to Category pages

      // ***** nofollowindex tags for Product pages
      if ($fullactionname == 'catalog_product_view')
      {
				// if our configuration is set to Yes then we'll look for fields value
        if ($this->_helper->enableNoFollowIndexForProducts() == 1)
        {
					$product = $this->_objectManager->get('Magento\Framework\Registry')->registry('current_product');
					$followFactory = $this->nofollowFactory->create();
					$nofollow = $followFactory->getCollection()
											->addIdandTypeFilter($product->getId(), 'product');
					$nofollow = $nofollow->getData();

          // if our table has a record that means fields are saved
          if (sizeof($nofollow) > 0)
          {
            // if value of enable field is set to yes then we'll grab tag value from fields and set it
            if ($nofollow[0]['nofollowindex_itemenablevalue'] == 1)
            {
							if ($nofollow[0]['nofollowindex_itemnoarchivevalue'] == 1)
							{
								return $this->getFollowValue($nofollow[0]['nofollowindex_itemfollowvalue'])
									. ',' . $this->getIndexValue($nofollow[0]['nofollowindex_itemindexvalue'])
									. ',' . $this->getArchiveValue($nofollow[0]['nofollowindex_itemnoarchivevalue']);
							}
              return $this->getFollowValue($nofollow[0]['nofollowindex_itemfollowvalue'])
								. ',' . $this->getIndexValue($nofollow[0]['nofollowindex_itemindexvalue']);
            }
            else
            {
							$nofollow = $this->getCategoryConfigForCurrentProductPriorityBase($product->getCategoryIds());
							if (sizeof($nofollow) > 0)
							{
								foreach ($nofollow as $key => $value)
								{
									// if value of enable field is set to yes then we'll grab tag value from fields and set it
									if ($nofollow[$key]['nofollowindex_itemenablevalue'] == 1
									    && $nofollow[$key]['nofollowindex_enableonproducts'] == 1)
									{
										if ($nofollow[$key]['nofollowindex_itemnoarchivevalue'] == 1)
										{
											return $this->getFollowValue($nofollow[$key]['nofollowindex_itemfollowvalue'])
												. ',' . $this->getIndexValue($nofollow[$key]['nofollowindex_itemindexvalue'])
												. ',' . $this->getArchiveValue($nofollow[$key]['nofollowindex_itemnoarchivevalue']);
										}
										return $this->getFollowValue($nofollow[$key]['nofollowindex_itemfollowvalue'])
											. ',' . $this->getIndexValue($nofollow[$key]['nofollowindex_itemindexvalue']);
									}
								}
							}
            }
          }
          else
          {
						$nofollow = $this->getCategoryConfigForCurrentProductPriorityBase($product->getCategoryIds());
						if (sizeof($nofollow) > 0)
						{
							foreach ($nofollow as $key => $value)
							{
								// if value of enable field is set to yes then we'll grab tag value from fields and set it
								if ($nofollow[$key]['nofollowindex_itemenablevalue'] == 1
								    && $nofollow[$key]['nofollowindex_enableonproducts'] == 1)
								{
									if ($nofollow[$key]['nofollowindex_itemnoarchivevalue'] == 1)
									{
										return $this->getFollowValue($nofollow[$key]['nofollowindex_itemfollowvalue'])
											. ',' . $this->getIndexValue($nofollow[$key]['nofollowindex_itemindexvalue'])
											. ',' . $this->getArchiveValue($nofollow[$key]['nofollowindex_itemnoarchivevalue']);
									}
									return $this->getFollowValue($nofollow[$key]['nofollowindex_itemfollowvalue'])
										. ',' . $this->getIndexValue($nofollow[$key]['nofollowindex_itemindexvalue']);
								}
							}
					  }
          }
        }
      }
      // ***** nofollowindex tags are added to Product pages

			if ($this->getNoFollowIndexForCurrentUrl() != '') {
				return $this->getNoFollowIndexForCurrentUrl();
			}

			return $this->scopeConfig->getValue(
          'design/search_engine_robots/default_robots',
          \Magento\Store\Model\ScopeInterface::SCOPE_STORE
      );
    }
    else
    {
      // extension not enabled so return default nofollowindex value which is coming from magento configurations
      return $this->scopeConfig->getValue(
          'design/search_engine_robots/default_robots',
          \Magento\Store\Model\ScopeInterface::SCOPE_STORE
      );
    }
  }

	// get follow value
	public function getFollowValue($followvaluefromtable)
	{
		if ($followvaluefromtable == 1)
		{
			return 'follow';
		}
		else
		{
			return 'nofollow';
		}
	}

	public function getIndexValue($indexvaluefromtable)
	{
		if ($indexvaluefromtable == 1)
		{
			return 'index';
		}
		else
		{
			return 'noindex';
		}
	}

	public function getArchiveValue($archivevaluefromtable)
	{
		if ($archivevaluefromtable == 1)
		{
			return 'noarchive';
		}
		return '';
	}

	public function getCategoryConfigForCurrentProductPriorityBase($productcategories)
	{
		$followFactory = $this->nofollowFactory->create();
		$temp = [];
		foreach ($productcategories as $key => $value)
		{
			$nofollow = $followFactory->getCollection()
									->addIdandTypeFilter($value, 'category');
			$nofollow = $nofollow->getData();
			if (!empty($nofollow))
			{
				$nofollow = $nofollow[0];
				if ($nofollow['nofollowindex_priority'] !== '' && $nofollow['nofollowindex_priority'] !== 0)
				{
					$temp[] = $nofollow;
				}
			}
		}
		if (!empty($temp))
		{
			$priorityarray = [];
			foreach ($temp as $key => $value)
			{
				$priorityarray[] = $value['nofollowindex_priority'];
			}
			if (!empty($priorityarray))
			{
				foreach ($temp as $key => $value)
				{
					if ($value['nofollowindex_priority'] !== min($priorityarray))
					{
						unset($temp[$key]);
					}
				}
			}
			if (sizeof($temp) > 1)
			{
				$temp = $temp[0];
			}
		}
		return $temp;
	}

	public function getNoFollowIndexForCurrentUrl()
	{
		$urlInterface = $this->_objectManager->get('Magento\Framework\UrlInterface');
		$customurls = $this->_helper->getCustomUrl();
		if (empty($customurls))
		{
			return '';
		}
		
	}
}
