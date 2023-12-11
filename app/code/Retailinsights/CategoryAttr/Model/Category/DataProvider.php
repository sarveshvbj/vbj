<?php
namespace Retailinsights\CategoryAttr\Model\Category;
 
class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
 
	protected function getFieldsMap()
	{
    	$fields = parent::getFieldsMap();
        $fields['content'][] = 'promo_banner'; // custom image field
        $fields['content'][] = 'promo_banner_1';
        $fields['content'][] = 'promo_banner_2';
        $fields['content'][] = 'promo_banner_3';
        $fields['content'][] = 'mobile_banner';
        $fields['content'][] = 'mainbanner_alttext';
        $fields['content'][] = 'promobanner_alttext';
        $fields['content'][] = 'promobanner_alttext2';
        $fields['content'][] = 'promobanner_alttext3';
        $fields['content'][] = 'promobanner_alttext4';
    	return $fields;
	}
}