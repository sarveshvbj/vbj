<?php
/**
 * MageGadgets Extensions
 * Stonemanager Extension
 * 
 * @package    		Magegadgets_Stonemanager
 * @copyright  		Copyright (c) 2017 MageGadgets Extensions (http://www.magegadgets.com/)
 * @contactEmail   	support@magegadgets.com
*/

namespace Magegadgets\Stonemanager\Block\Adminhtml\Form\Field;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form;
use Magento\Framework\Escaper;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magegadgets\Stonemanager\Api\StonemanagerTableInterface;

class Import extends AbstractElement
{
    protected $stonemanagerTableInterface;

    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        StonemanagerTableInterface $stonemanagerTableInterface,
        array $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);

        $this->stonemanagerTableInterface = $stonemanagerTableInterface;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setType('file');
    }

    public function getElementHtml()
    {
        $res = parent::getElementHtml();

        $rowsCount = $this->stonemanagerTableInterface->getRowsCount();

        if ($rowsCount) {
            $res .= '<br /><p class="note">' . __('You have <strong>%1</strong> Stone(s) information ', [$rowsCount]) . '</p>';
        } else {
            $res .= '<br /><p class="note">' . __('Stone information table is empty') . '</p>';
        }

        return $res;
    }
}
