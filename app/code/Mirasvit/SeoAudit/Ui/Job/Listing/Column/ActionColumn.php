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


declare(strict_types=1);


namespace Mirasvit\SeoAudit\Ui\Job\Listing\Column;

use Magento\Framework\UrlInterface as UrlBuilder;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Mirasvit\SeoAudit\Api\Data\JobInterface;

class ActionColumn extends Column
{
    private $urlBuilder;

    public function __construct(
        UrlBuilder $urlBuilder,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {

                $item[$this->getData('name')] = [
                    'details'   => [
                        'href'  => $this->urlBuilder->getUrl('seoaudit/job/details', [
                            JobInterface::ID => $item[JobInterface::ID],
                        ]),
                        'label' => __('See Details'),
                    ]
                ];
            }
        }

        return $dataSource;
    }
}
