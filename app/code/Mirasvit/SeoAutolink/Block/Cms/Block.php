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



namespace Mirasvit\SeoAutolink\Block\Cms;

class Block extends \Magento\Cms\Block\Block
{
    /**
     * @var \Mirasvit\SeoAutolink\Model\Config
     */
    protected $config;

    /**
     * @var \Mirasvit\SeoAutolink\Service\TextProcessorService
     */
    protected $seoAutolinkData;

    /**
     * @var \Magento\Framework\View\Element\Context
     */
    protected $context;

    /**
     * @param \Magento\Framework\View\Element\Context    $context
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Cms\Model\BlockFactory            $blockFactory
     * @param \Mirasvit\SeoAutolink\Model\Config         $config
     * @param \Magento\Framework\ObjectManagerInterface  $objectManager
     * @param array                                      $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Mirasvit\SeoAutolink\Model\Config $config,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->config          = $config;
        $this->seoAutolinkData = $objectManager->get('\Mirasvit\SeoAutolink\Service\TextProcessorService');
        $this->context         = $context;
        parent::__construct($context, $filterProvider, $storeManager, $blockFactory, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
    }

    /**
     * @return \Mirasvit\SeoAutolink\Model\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Prepare Content HTML.
     * @return string
     */
    protected function _toHtml()
    {
        if (!in_array(\Mirasvit\SeoAutolink\Model\Config\Source\Target::CMS_BLOCK, $this->getConfig()->getTarget())) {
            return parent::_toHtml();
        }
        $html = parent::_toHtml();
        $html = $this->seoAutolinkData->addLinks($html);

        return $html;
    }
}
