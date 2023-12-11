<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_StoreOptimization
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\StoreOptimization\Plugin\Catalog\Block\Product;

use Magento\Catalog\Block\Product\Image;

class ImageResponsiveRatio
{

    /**
     * @var Webkul\StoreOptimization\Helper\Image
     */
    protected $imageConfig;

    /**
     * @var Webkul\StoreOptimization\Helper\Data
     */
    protected $helper;

    /**
     *
     * @param \Webkul\StoreOptimization\Helper\Image $imageConfig
     * @param \Webkul\StoreOptimization\Helper\Data $helper
     */
    public function __construct(
        \Webkul\StoreOptimization\Helper\Image $imageConfig,
        \Webkul\StoreOptimization\Helper\Data $helper
    ) {
        $this->imageConfig = $imageConfig;
        $this->helper = $helper;
    }

    /**
     * Adjust srcset if required
     *
     * @param Image $subject
     */
    public function beforeToHtml(Image $subject)
    {
        if ($this->imageConfig->getIsImageSrcSetEnable()) {
        
            $imageUrl = $subject->getData('image_url');
            $originalImageUrl = $subject->getData('original_image_url');
            $pixels = $this->imageConfig->getIsImagePixels();
            $pixelsArray = explode(',', $pixels);
            $glue = (strpos($imageUrl, '?') !== false) ? '&' : '?';
            $srcSet = [];
            foreach ($pixelsArray as $pixel) {
                $ratio = 'dpr=' . $pixel . ' ' . $pixel . 'x';
                $srcSet[] = $imageUrl . $glue . $ratio;
            }

            $subject->setData(
                'custom_attributes',
                sprintf('srcset="%s" onerror="this.src=\'%s\'"', implode(',', $srcSet), $originalImageUrl)
            );

        }
    }
    
    /**
     * overwrite template file
     *
     * @param Image $subject
     * @param string $template
     * @return []
     */
    public function beforeSetTemplate(Image $subject, $template)
    {
        if ($template == "Magento_Catalog::product/image_with_borders.phtml") {
            return ["Webkul_StoreOptimization::catalog/product/image_with_borders.phtml"];
        } elseif ($template == "Magento_Catalog::product/image.phtml") {
            return ["Webkul_StoreOptimization::catalog/product/image.phtml"];
        } else {
            return [$template];
        }
    }

    /**
     * @param CatalogImage $subject
     * @param \Closure $proceed
     * @return mixed
     */
    public function aroundToHtml(
        Image $subject,
        \Closure $proceed
    ) {
        if (!$this->helper->getIsLazyLoadingEnable()) {
            return $proceed();
        }
        
        $orgImageUrl = $subject->getImageUrl();
        $subject->setImageUrl($subject->getViewFileUrl("images/loader-2.gif"));

        $customAttributes = trim(
            $subject->getCustomAttributes() . 'wk-data-original'
        );

        $subject->setCustomAttributes($customAttributes);

        $result = $proceed();

        $find = [
            'img class="',
            'wk-data-original'
        ];

        $replace = [
            'img class="wk_lazy new-lazy ',
            sprintf(' data-original="%s"', $orgImageUrl),
        ];

        return str_replace($find, $replace, $result);
    }
}
