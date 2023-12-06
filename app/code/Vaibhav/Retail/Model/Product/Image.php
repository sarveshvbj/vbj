<?php 
namespace Vaibhav\Retail\Model\Product;

class Image extends \Magento\Catalog\Model\Product\Image {

    protected function _construct() {
        $this->_quality = 95;

        parent::_construct();
    }
}