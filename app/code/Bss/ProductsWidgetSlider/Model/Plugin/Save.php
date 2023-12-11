<?php
/**
 * Bss Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   Bss
 * @package    Bss_ProductsWidgetSlider
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 Bss Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */

namespace  Bss\ProductsWidgetSlider\Model\Plugin;

class Save extends \Magento\Widget\Controller\Adminhtml\Widget\Instance\Save
{

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    public $redirectFactory;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Widget\Model\Widget\InstanceFactory $widgetFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Math\Random $mathRandom
     * @param \Magento\Framework\Translate\InlineInterface $translateInline
     * @param \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Widget\Model\Widget\InstanceFactory $widgetFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Math\Random $mathRandom,
        \Magento\Framework\Translate\InlineInterface $translateInline,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
    ) {
        $this->redirectFactory = $redirectFactory;
        parent::__construct($context, $coreRegistry, $widgetFactory, $logger, $mathRandom, $translateInline);
    }

    /**
     * Execute Function
     * @param \Magento\Widget\Controller\Adminhtml\Widget\Instance\Save $subject
     * @param mixed $proceed
     * @return \Magento\Framework\App\ResponseInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundExecute($subject, $proceed)
    {
        $parameters = $this->getRequest()->getParams();
        $code = $parameters['code'];
        if ($code == 'bss_products_widget_bestseller' || $code =='bss_products_widget_mostview') {
            $error = $this->bssBestSellerChecker();
            /*Return checker Validator*/
            return $this->checker($error, $proceed);
        }
        if ($code == 'bss_products_widget_onsale') {
            $error = $this->bssOnSaleChecker();
            /*Return checker Validator*/
            return $this->checker($error, $proceed);
        }
        return $proceed();
    }

    /**
     * Validate Widget Function for On Sale
     * @return bool
     */
    public function bssOnSaleChecker()
    {
        $error = $this->sliderChecker();
        if (!$error) {
            $error = $this->pagerChecker();
            if (!$error) {
                return false;
            }
            return true;
        }
        return true;
    }

    /**
     * Validate Widget Function for BestSeller and Mostviewed
     * @return bool
     */
    public function bssBestSellerChecker()
    {
        $error = $this->dateChecker();
        if (!$error) {
            $error = $this->sliderChecker();
            if (!$error) {
                $error = $this->pagerChecker();
                if (!$error) {
                    return false;
                }
                return true;
            }
            return true;
        }
        return true;
    }

    /**
     * @param string $string
     * @return bool
     */
    public function notEmptyValidate($string)
    {
        $empty = $string;
        if (!empty($empty)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $int
     * @return bool
     */
    public function intValidate($int)
    {
        if (filter_var($int, FILTER_VALIDATE_INT)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param float $float
     * @return bool
     */
    public function floatValidate($float)
    {
        if (filter_var($float, FILTER_VALIDATE_FLOAT)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $date
     * @return bool
     */
    public function dateValidate($date)
    {
        // match the format of the date
        if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
            // check whether the date is valid or not
            if (checkdate($parts[2], $parts[3], $parts[1])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function dateChecker()
    {
        $value = $this->getRequest()->getParam('parameters');
        $fromDate = $value['from_date'];
        $toDate = $value['to_date'];
        if ($this->notEmptyValidate($fromDate) && $this->dateValidate($fromDate) != true) {
            $this->messageManager
                ->addErrorMessage(__('Incorrect YYYY-MM-DD format or the date is invalid,
                please check From Date field'));
            return true;
        }
        if ($this->notEmptyValidate($toDate) && $this->dateValidate($toDate) != true) {
            $this->messageManager
                ->addErrorMessage(__('Incorrect YYYY-MM-DD format or the date is invalid, please check To Date field'));
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function sliderChecker()
    {
        $value = $this->getRequest()->getParam('parameters');
        $showSlider = $value['show_as_slider'];
        if ($showSlider == 1) {
            $productPerSlide = $value['products_per_slide'];
            if ($this->notEmptyValidate($productPerSlide)) {
                if ($this->intValidate($productPerSlide)) {
                    if ($productPerSlide < 0) {
                        $this->messageManager
                            ->addErrorMessage(__('Number(s) of Product per Slider must be great than 0'));
                        return true;
                    }
                } else {
                    $this->messageManager->addErrorMessage(__('Product per Slide input must be type of integer'));
                    return true;
                }
            } else {
                $this->messageManager->addErrorMessage(__('Please fill Number of Product per Slide'));
                return true;
            }
            $autoEvery = $value['auto_every'];
            if ($this->notEmptyValidate($autoEvery)) {
                if ($this->floatValidate($autoEvery) || $this->intValidate($autoEvery)) {
                    if ($autoEvery < 0) {
                        $this->messageManager
                            ->addErrorMessage(__('Auto Transition Time must be great than or equal to 0'));
                        return true;
                    }
                } else {
                    $this->messageManager
                        ->addErrorMessage(__('Auto Transition Time input must be type of float or integer'));
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function pagerChecker()
    {
        $value = $this->getRequest()->getParam('parameters');
        $showSlider = $value['show_as_slider'];
        $productCount = $value['products_count'];
        if ($showSlider == 0) {
            $showPager = $value['show_pager'];
            if ($showPager == 1) {
                $productPerPage = $value['products_per_page'];
                if ($this->notEmptyValidate($productPerPage)) {
                    if ($this->intValidate($productPerPage)) {
                        if ($productPerPage <= 0) {
                            $this->messageManager->addErrorMessage(__('Products per Page must be great than 0'));
                            return true;
                        }
                    } else {
                        $this->messageManager
                            ->addErrorMessage(__(
                                'Products per Page to Display input must be type of  integer and digits only'
                            ));
                        return true;
                    }
                } else {
                    $this->messageManager->addErrorMessage(__('Please fill Products per Page'));
                    return true;
                }
            }
        }
        if ($this->notEmptyValidate($productCount)) {
            if ($this->intValidate($productCount)) {
                if ($productCount <= 0) {
                    $this->messageManager->addErrorMessage(__('Number of Products must be great than 0'));
                    return true;
                }
            } else {
                $this->messageManager
                    ->addErrorMessage(__(
                        'Number of Products to Display input must be type of integer and digits only'
                    ));
                return true;
            }
        } else {
            $this->messageManager->addErrorMessage(__('Please fill Number of Product'));
            return true;
        }

        return false;
    }
    /**
     * @param bool $error
     * @param mixed $proceed
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function checker($error, $proceed)
    {
        if ($error) {
            return $this->_redirect('adminhtml/*/edit', ['_current' => true]);
        } else {
            return $proceed();
        }
    }
}
