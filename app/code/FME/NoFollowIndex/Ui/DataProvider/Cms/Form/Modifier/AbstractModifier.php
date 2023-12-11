<?php

/**
 * Class for NoFollowIndex AbstractModifier
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Ui\DataProvider\Cms\Form\Modifier;

abstract class AbstractModifier extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    const FORM_NAME = 'cms_page_form';
    const DATA_SOURCE_DEFAULT = 'page';
    const DATA_SCOPE_PRODUCT = 'page';
    const DEFAULT_GENERAL_PANEL = 'cms-details';
}
