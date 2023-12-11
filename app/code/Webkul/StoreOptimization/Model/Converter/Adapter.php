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
namespace Webkul\StoreOptimization\Model\Converter;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use WebPConvert\WebPConvert;
use Webkul\StoreOptimization\Model\JpegCompressor;

class Adapter
{
    /**
     * @var  \Webkul\StoreOptimization\Helper\Image
     */
    protected $imageHelper;

    public function __construct(
        \Webkul\StoreOptimization\Helper\Image $imageHelper
    ) {
        $this->imageHelper = $imageHelper;
    }
    
    /**
     * convert image to webp
     *
     * @param string $imagePath
     * @param string $destination
     * @param array $options
     * @param string $type
     * @return void
     */
    public function convert($imagePath, $destination, $options, $type = "jpeg")
    {
        if ($type == 'webp') {
            WebPConvert::convert($imagePath, $destination, $options);
        } elseif ($type == 'jpeg') {
            JpegCompressor::convert($imagePath, $destination);
        }
    }
}
