<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://magezon.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  Magezon
 * @package   Magezon_LazyLoad
 * @copyright Copyright (C) 2018 Magezon (https://magezon.com)
 */

namespace Magezon\LazyLoad\Block\Product;

use Magento\Catalog\Model\Product\Image\NotLoadInfoImageException;
use Magento\Framework\App\ObjectManager;

class ImageBuilder extends \Magento\Catalog\Block\Product\ImageBuilder
{
    /**
     * Create image block
     *
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function create()
    {
        $dataHelper = ObjectManager::getInstance()->get(\Magezon\LazyLoad\Helper\Data::class);
        if (!$dataHelper->isEnable() || !$dataHelper->getConfig('general/lazy_load_images') || $this->imageId === 'product_base_image') {
            return parent::create();
        }

        /** @var \Magento\Catalog\Helper\Image $helper */
        $helper                 = $this->helperFactory->create()->init($this->product, $this->imageId);

        $attributes             = $this->attributes;
        $attributes['data-src'] = $helper->getUrl();
        $this->setAttributes($attributes);
        

        $template = $helper->getFrame()
            ? 'Magezon_LazyLoad::product/image.phtml'
            : 'Magezon_LazyLoad::product/image_with_borders.phtml';

        try {
            $imagesize = $helper->getResizedImageInfo();
        } catch (NotLoadInfoImageException $exception) {
            $imagesize = [$helper->getWidth(), $helper->getHeight()];
        }

        $placeHolderUrl = $dataHelper->getPlaceHolderUrl();

        if ($dataHelper->getConfig('general/preview')) {
            $attrs = ['width' => $helper->getWidth(), 'height' => $helper->getHeight()];
            $helper2        = $this->helperFactory->create()->init($this->product, $this->imageId, $attrs)->setQuality(5);
            $placeHolderUrl = $helper2->getUrl();
        }

        $data = [
            'data' => [
                'template'             => $template,
                'image_url'            => $placeHolderUrl,
                'width'                => $helper->getWidth(),
                'height'               => $helper->getHeight(),
                'label'                => $helper->getLabel(),
                'ratio'                => $this->getRatio($helper),
                'custom_attributes'    => $this->getCustomAttributes(),
                'resized_image_width'  => $imagesize[0],
                'resized_image_height' => $imagesize[1],
            ],
        ];

        return $this->imageFactory->create($data);
    }
}