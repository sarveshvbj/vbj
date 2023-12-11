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
namespace Webkul\StoreOptimization\Model\View\Asset;

use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product\Media\ConfigInterface;
use Magento\Catalog\Model\View\Asset\Image as ImageModel;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\View\Asset\ContextInterface;
use Magento\PageCache\Model\Config as PageCacheConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Image
 * Webkul\StoreOptimization\Model\View\Asset
 */
class Image extends ImageModel
{
    /**
     * @var \Webkul\StoreOptimization\Helper\Image
     */
    private $imageConfig;

    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    
    /**
     * Misc image params depend on size, transparency, quality, watermark etc.
     *
     * @var array
     */
    private $miscParams;

    /**
     * @var \Webkul\StoreOptimization\Model\Converter\Adapter
     */
    protected $imageAdapter;

    /**
     * @var Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var ContextInterface
     */
    protected $myContext;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $file;

    /**
     * Image constructor.
     * @param ConfigInterface $mediaConfig
     * @param ContextInterface $context
     * @param EncryptorInterface $encryptor
     * @param \Webkul\StoreOptimization\Helper\Image $imageConfig
     * @param ImageHelper $imageHelper
     * @param StoreManagerInterface $storeManager
     * @param string $filePath
     * @param array $miscParams
     */
    public function __construct(
        ConfigInterface $mediaConfig,
        ContextInterface $context,
        EncryptorInterface $encryptor,
        \Webkul\StoreOptimization\Helper\Image $imageConfig,
        ImageHelper $imageHelper,
        StoreManagerInterface $storeManager,
        $filePath,
        array $miscParams,
        \Webkul\StoreOptimization\Model\Converter\Adapter $imageAdapter,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Filesystem\Io\File $file
    ) {
        $this->imageConfig = $imageConfig;
        $this->imageHelper = $imageHelper;
        $this->storeManager = $storeManager;
        $this->miscParams = $miscParams;
        parent::__construct($mediaConfig, $context, $encryptor, $filePath, $miscParams);
        $this->imageAdapter = $imageAdapter;
        $this->registry = $registry;
        $this->myContext = $context;
        $this->file = $file;
    }

    /**
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getUrl()
    {
        if ($this->imageConfig->getIsImageOptimizationEnable() == false) {
            return parent::getUrl();
        }

        $originalImageUrl = $this->getOriginalImageUrl();
        $imagePath = $this->getPath();
        $cachePath = explode($this->myContext->getBaseUrl(), $originalImageUrl);
        $oiginalPath = $this->myContext->getPath().$cachePath[1];
        $destination = $this->getDestinationPath($imagePath);
        if (!$this->file->fileExists($destination)) {
            $options = [];
            try {
                $this->imageAdapter->convert(
                    $oiginalPath,
                    $destination,
                    $options,
                    $this->imageConfig->getImageCompressionType()
                );
                return $this->convertedImageUrl($originalImageUrl);
            } catch (\Exception $e) {
                $this->imageConfig->createLog($e);
                return $originalImageUrl;
            }
        } else {
            $this->imageConfig->createLog("file already exist: ".$originalImageUrl);
            return $this->convertedImageUrl($originalImageUrl);
        }
    }

    /**
     * get webp image url
     *
     * @param string $url
     * @return string
     */
    public function convertedImageUrl($url)
    {
        $urlArray = $this->file->getPathInfo($url);
        return $urlArray['dirname']. DIRECTORY_SEPARATOR .$urlArray['filename'] .
        ".".$this->imageConfig->getImageCompressionType();
    }

    /**
     * get destinatio path
     *
     * @param string $path
     * @return string
     */
    public function getDestinationPath($path)
    {
        $imagePathParts = $this->file->getPathInfo($path);
        return $imagePathParts['dirname']. DIRECTORY_SEPARATOR .$imagePathParts['filename'] .
        '.'.$this->imageConfig->getImageCompressionType();
    }

    /**
     * get original(jpg|png|gif) image url
     *
     * @return string
     */
    public function getOriginalImageUrl()
    {
        return parent::getUrl();
    }
}
