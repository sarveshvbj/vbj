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



namespace Mirasvit\Seo\Ui\CanonicalRewrite\Form\Component;

use Magento\Ui\Component\HtmlContent;
use Magento\Ui\Component\AbstractComponent;
use Mirasvit\Seo\Api\Data\CanonicalRewriteInterface;

class RuleHtmlContent extends HtmlContent
{
    /**
     * Prepare component configuration
     *
     * @return void
     */
    public function prepare()
    {
        $config = (array)$this->getData('config');
        $html = $this->block->toHtml();
        $html = preg_replace('/data-form-part=""/ims',
            'data-form-part="' . CanonicalRewriteInterface::RULE_FORM_NAME . '"',
            $html);
        $config['content'] = $html;
        $this->setData('config', $config);
        AbstractComponent::prepare();
    }
}
