<?php

namespace Webkul\GiftCard\Block\Adminhtml;

class Bulkupload extends \Magento\Backend\Block\Template{
	public function __construct(\Magento\Framework\Data\Form\FormKey $formKey,
		\Magento\Backend\Block\Widget\Context $context)
	{
		$this->formKey = $formKey;
		parent::__construct($context);
	}
	public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    public function getDownloadUrl(){
    	return $this->getBaseUrl().'hicare-giftvoucher.csv';
    }

    // public function getZipcodeDownloadUrl(){
    // 	return $this->getBaseUrl().'hicare-zipcode.csv';
    // }
}
