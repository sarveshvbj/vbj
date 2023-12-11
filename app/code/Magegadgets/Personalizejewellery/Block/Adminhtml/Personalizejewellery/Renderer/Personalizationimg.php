<?php
 
namespace Magegadgets\Personalizejewellery\Block\Adminhtml\Personalizejewellery\Renderer;
 
use Magento\Framework\DataObject;
 
class Personalizationimg extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
 
    /**
     * get category name
     * @param  DataObject $row
     * @return string
     */
    public function render(DataObject $row)
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$mediaurl = $objectManager->get('Magento\Store\Model\StoreManagerInterface')
                    ->getStore()
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $imagename = $row->getImage();
		if($imagename != '')
		{
			$imgsrc = "<a href='".$mediaurl.$imagename."' target=_blank' ><img src='".$mediaurl.$imagename."' style='height:100px; width:100px;' /></a>";
		} 
		else
		{
			$imgsrc = "<img src='".$mediaurl."personalizejewellery/upload-image.png' style='height:100px; width:100px;' />";
			
		}
        return $imgsrc;
    }
}