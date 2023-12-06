<?php
namespace Webkul\Giftcard\Block\Adminhtml\Sales;

class Totals extends \Magento\Framework\View\Element\Template
{
    protected $_helper;
   
    protected $_currency;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Directory\Model\Currency $currency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_currency = $currency;
    }
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }
    public function getCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getOrder();
        $this->getSource();
        /*if(!$this->getSource()->getGiftVoucherAmt()) {
            return $this;
        }*/
        $total = new \Magento\Framework\DataObject(
            [
                'code' => 'giftcard',
                'value' => 60,
                'label' => 'Giftcard',
            ]
        );
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');
        return $this;
    }
}