<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ibnab\MegaMenu\Block\Html;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Theme\Block\Html\Topmenu;
use Magento\Cms\Model\BlockRepository;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Registry;

/**
 * Html page top menu block
 */
class Topmega extends Topmenu {

    /**
     * Cache identities
     *
     * @var array
     */
    protected $identities = [];

    /**
     * Top menu data tree
     *
     * @var \Magento\Framework\Data\Tree\Node
     */
    protected $_menu;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $registry;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Block factory
     *
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $_blockFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var \Ibnab\CategoriesUrl\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param Template\Context $context
     * @param NodeFactory $nodeFactory
     * @param TreeFactory $treeFactory
     * @param array $data
     */
    public function __construct(
    Template\Context $context, NodeFactory $nodeFactory, TreeFactory $treeFactory, CategoryFactory $categoryFactory, \Magento\Cms\Model\Template\FilterProvider $filterProvider, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Cms\Model\BlockFactory $blockFactory, Registry $registry, \Ibnab\MegaMenu\Helper\Data $dataHelper, array $data = []
    ) {
        parent::__construct($context, $nodeFactory, $treeFactory, $data);
        $this->categoryFactory = $categoryFactory;
        $this->_filterProvider = $filterProvider;
        $this->_storeManager = $storeManager;
        $this->_blockFactory = $blockFactory;
        $this->coreRegistry = $registry;
        $this->dataHelper = $dataHelper;
        $this->_menu = $this->getMenu();
    }

    /**
     * Prepare Content HTML
     *
     * @return string
     */
    public function getBlockHtml($id) {
        $blockId = $id;
        $html = '';
        if ($blockId) {
            $storeId = $this->_storeManager->getStore()->getId();
            /** @var \Magento\Cms\Model\Block $block */
            $block = $this->_blockFactory->create();
            $block->setStoreId($storeId)->load($blockId);
            if ($block->isActive()) {
                $html = $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent());
            }
        }
        return $html;
    }

    protected function getCategory($category_id) 
    {
        $category = $this->categoryFactory->create();
        $category->load($category_id);
        return $category;
    }

    protected function getStyleAttributes($category_id){
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/categoryproducts.log');
        $logger = new \Zend\Log\Logger(); 
        $logger->addWriter($writer);
        $styleIds=array();
        $stylevalues=array();
        $styledata=array();
        if($category_id){
            $logger->info("category-is valid Category -id ".$category_id); 
        $products = $this->getCategory($category_id)->getProductCollection();
        $products->addAttributeToSelect('*');
        
        //print_r($products);

        foreach ($products as $product) {
            if($product->getData('style'))
            {
                $keys=array_keys($styledata);
                if(!in_array($product->getData('style'), $keys)){               
             $styledata[$product->getData('style')]=$product->getAttributeText('style');
                }
            }
       }
      
        }
       // print_r($styledata);

        return $styledata;

    }

      protected function getMetalAttributes($category_id){
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/metal.log');
        $logger = new \Zend\Log\Logger(); 
        $logger->addWriter($writer);
        $metaldata=array();
        if($category_id){
        $products = $this->getCategory($category_id)->getProductCollection();
        $products->addAttributeToSelect('*');
        
        //print_r($products);

        foreach ($products as $product) {
            if($product->getData('metal'))
            {
                $keys=array_keys($metaldata);
                if(!in_array($product->getData('metal'), $keys)){               
             $metaldata[$product->getData('metal')]=$product->getAttributeText('metal');
                }
            }
       }
      
        }
       // print_r($styledata);

        return  $metaldata;

    }

    /**
     * Add sub menu HTML code for current menu item
     *
     * @param \Magento\Framework\Data\Tree\Node $child
     * @param string $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string HTML code
     */
    protected function _addSubMenu2($child, $childLevel, $childrenWrapClass, $limit) {

        if ($this->dataHelper->allowExtension()) {
            $html = '';
            if (!$child->hasChildren()) {
                return $html;
            }

            $colStops = null;
            if ($childLevel == 0 && $limit) {
                $colStops = $this->_columnBrake($child->getChildren(), $limit);
            }


            $category = "";
            if ($childLevel == 0) {
               
                $category = $this->coreRegistry->registry('current_categry_top_level');
                if ($category != null) {
                    // if ($category->getUseStaticBlock()) {

                    //     if ($category->getUseStaticBlockTop() && $category->getStaticBlockTopValue() != "") {
                    //         $html .= '<div class="topstatic" >';
                    //         $html .= $this->getBlockHtml($category->getStaticBlockTopValue());
                    //         $html .= '</div>';
                    //     }
                    //     if ($category->getUseStaticBlockLeft() && $category->getStaticBlockLeftValue() != "") {
                    //         $html .= '<div class="leftstatic" >';
                    //         $html .= $this->getBlockHtml($category->getStaticBlockLeftValue());
                    //         $html .= '</div>';
                    //     }
                    // }
                    if ($category->getUseLabel()) {
                        if ($category->getLabelValue() != "") {
                            $child->setData('name', $category->getLabelValue());
                        }
                    }
                }
                if (!$category->getDisabledChildren() && $childLevel == 0) {

                    // $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
                     
                     
                     $arrayId = explode('-', $child->getId());
                     if (isset($arrayId[2])) {
                         
                        $id = $arrayId[2];
                       $styleData= $this->getStyleAttributes($id);
                  //     if(!empty($styleData)) {
                  //       $html .= '<h5>SHOP BY STYLE</h5><ul class="row">';
                  //         foreach($styleData as $key => $value){
                  //       if($value){
                  //             $html.='<li class="col-md-6"><a href="'.$child->getUrl().'?style='.$key.'">
                  //                                       <span>'.$value.'</span>
                  //                                  </a></li>';
                  //       } 
                      
                  //     }
                  // } else {


                    $html .= '<h5>SHOP BY CATEGORY</h5><ul class="row vaibhav_submenu">';

                   
                     
                     $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);

                  $html .= '</ul> </div>';


                  $metalData=$this->getMetalAttributes($id);
                      if(!empty($metalData)) {
                          foreach($metalData as $key => $value){
                        if($value){

                                                    $html .= '<div class="col-md-3">
                                                <h5>SHOP BY METAL & STONE</h5>
                                                <ul> <li>
                                                      <a href="'.$child->getUrl().'?metal='.$key.'">
                                                        <img src="https://www.vaibhavjewellers.com/pub/media/Home Page (72).jpg" alt="">
                                                        <span>'.$value.'</span>
                                                        </a>
                                                    </li>
                                                
                                                </ul>
                                            </div>';
                        } 
                      
                      }
                  }

                    
                      
                    }
                    
                }

              

                // $html .= '<div class="bottomstatic" ></div>';
               




               


              $html .='  <div class="col-md-3">
                                                <h5>SHOP BY</h5>
                                                <ul>
                                                    <li>
                                                        FOR MEN
                                                    </li>
                                                    <li>
                                                        <a href="'.$child->getUrl().'?price=0-10000">
                                                        Under 10k
                                                        </a>
                                                    </li>
                                                    <li>
                                                       <a href="'.$child->getUrl().'?price=10000-20000"> 10k to 20k</a>
                                                    </li>
                                                    <li>
                                                        <a href="'.$child->getUrl().'?price=20000-30000"> 20k to 30k</a>
                                                    </li>
                                                    <li>
                                                     <a href="'.$child->getUrl().'?price=30000-1067526">Above 30k</a>
                                                        
                                                    </li>
                                                </ul>
                                            </div>';

                  $html .='</div>
                            </div>';


                   $html .=' <div class="col-md-4">';

                   $html .= '<div class="rightstatic" >';
                            $html .= ' <a href="'.$child->getUrl().'"><img class="w-100" src="https://www.vaibhavjewellers.com/pub/media/Home Page (60).jpg" alt=""></a>';
                            $html .= '</div>';

                  if ($category != null) {
                    if ($category->getUseStaticBlock()) {
                        if ($category->getUseStaticBlockRight() && $category->getStaticBlockRightValue() != "") {
                            $html .= '<div class="rightstatic" >';
                            $html .= $this->getBlockHtml($category->getStaticBlockRightValue());
                            $html .= '</div>';
                        }
                    }
                }

                $html .=' </div>
                                </div>
                            </div>';


            } else {
                $html .= '<ul>';
                $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
                $html .= '</ul>';
            }
            return $html;
        } else {
            return parent::_addSubMenu($child, $childLevel, $childrenWrapClass, $limit);
        }
    }

    /**
     * Returns array of menu item's classes
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return array
     */
    protected function _getMenuItemClasses(\Magento\Framework\Data\Tree\Node $item) {

        $classes = [];
        $level = 'level' . $item->getLevel();
        $classes[] = $level;

        $position = $item->getPositionClass();
        $positionArray = explode("-", $position);
        $classes[] = $position;

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        if ($item->getIsActive()) {
            $classes[] = 'active';
        } elseif ($item->getHasActive()) {
            $classes[] = 'has-active';
        }

        if ($item->getIsLast()) {
            $classes[] = 'last';
        }

        if ($item->getClass()) {
            $classes[] = $item->getClass();
        }

        if ($item->hasChildren()) {
            $classes[] = 'parent';
        }

        if ($level == 'level1' && count($positionArray) == 3) {
            $category = $this->coreRegistry->registry('current_categry_top_level');
            if(!is_null($category)){
               $classes[] = $category->getLevelColumnCount();
            }
        }
        return $classes;
    }
    
    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param \Magento\Framework\Data\Tree\Node $menuTree
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $colBrakes
     * @return string
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    
    protected function _getHtml(
    \Magento\Framework\Data\Tree\Node $menuTree, $childrenWrapClass, $limit, $colBrakes = []
    ) {
        $html = '';
        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $counter = 1;
        $itemPosition = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        foreach ($children as $child) {
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $child->setClass($outermostClass);
            }
            if(is_array($colBrakes) || is_object($colBrakes)){
            if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
                $html .= '</ul></li><li class="column"><ul>';
            }
            }
            // $html .= '<li ' . '' . '>';
            $html.='<li class="col-md-6">';
            if ($child->getCategoryIsLink()) {
                $html .= '<a href="' . $child->getUrl() . '" ' . '' . '>';
            }else{
                 $html .= '<a href="' . $child->getUrl() . '" ' . '' . '>';
            }
            $html .= '<img src="https://www.vaibhavjewellers.com/pub/media/Home Page (61).jpg" alt=""><span>' . $this->escapeHtml(
                            $child->getName()
                    ) . '</span>';

                $html .= '</a></li>';
            

            $html .= $this->_addSubMenu(
                $child,
                $childLevel,
                $childrenWrapClass,
                $limit
            );
            $itemPosition++;
            $counter++;

        }
        if(is_array($colBrakes) || is_object($colBrakes)){
        if (count($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }
        }
        return $html;
    }

       protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit)
    {
        $html = '';
        if (!$child->hasChildren()) {
            return $html;
        }

        $colStops = [];
        if ($childLevel == 0 && $limit) {
            $colStops = $this->_columnBrake($child->getChildren(), $limit);
        }

        // $html .= '<ul class="">';
        $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
        // $html .= '</ul>';

        return $html;
    }


    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param \Magento\Framework\Data\Tree\Node $menuTree
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $colBrakes
     * @return string
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getHtml2(
    \Magento\Framework\Data\Tree\Node $menuTree, $childrenWrapClass, $limit, $colBrakes = []
    ) {
        if ($this->dataHelper->allowExtension()) {
            $html = '';
            $children = $menuTree->getChildren();
            $parentLevel = $menuTree->getLevel();
            $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

            $counter = 1;
            $itemPosition = 1;
            $childrenCount = $children->count();

            $parentPositionClass = $menuTree->getPositionClass();
            $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

            foreach ($children as $child) {
                $child->setLevel($childLevel);
                $child->setIsFirst($counter == 1);
                $child->setIsLast($counter == $childrenCount);
                $child->setPositionClass($itemPositionClassPrefix . $counter);

                $outermostClassCode = '';
                $outermostClass = $menuTree->getOutermostClass();

                if ($childLevel == 0 && $outermostClass) {
                    $outermostClassCode = ' class="' . $outermostClass . '" ';
                    $child->setClass($outermostClass);
                }
                if ($childLevel == 0) {
                    $arrayId = explode('-', $child->_getData('id'));
                    $category = null;
                    if (isset($arrayId[2])) {
                        $id = $arrayId[2];
                         //$this->getStyleAttributes($id);
                        $category = $this->categoryFactory->create();
                        $category->load($id);
                        $this->coreRegistry->unregister('current_categry_top_level');
                        $this->coreRegistry->register('current_categry_top_level', $category);
                    }
                }
                if(is_array($colBrakes) || is_object($colBrakes)){
                if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
                    $html .= '</ul></li><li><ul>';
                }
                }
                $html .= '<li class="categories"> ';

                if ($childLevel == 0) {
                     $name = $child->getName();
                    $arrayId = explode('-', $child->getId());
                     if (isset($arrayId[2])) {
                        $id = $arrayId[2];
                        //$this->getStyleAttributes($id);
                    }
                   
                   
                    $category = $this->coreRegistry->registry('current_categry_top_level');
                    if ($category != null) {
                        if ($category->getUseLabel()) {
                            if ($category->getLabelValue() != "") {
                                $name = $category->getLabelValue();
                            } else {
                                $name = $child->getName();
                            }
                        } else {
                            $name = $child->getName();
                        }
                    }
                    if ($category->getCategoryIsLink()) {
                        $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '>';
                    }else{
                        // $html .= '<a ' . $outermostClasscolBrakesCode . '>';
                        $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '>';
                    }
                    $html .= '<span>' . $this->escapeHtml(
                                    $name
                            ) . '</span>';
                        $html .= '</a><div class="menu-dropdown container-fluid">
                                <div class=" row">
                                    <div class="col-md-8 menu-list">
                                        <div class="row">
                                            <div class="col-md-6">';


                    $html .= $this->_addSubMenu2($child, $childLevel, $childrenWrapClass, $limit
                            ) . '</li>';

                } else {
                    $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                                    $child->getName()
                            ) . '</span></a>' . $this->_addSubMenu2(
                                    $child, $childLevel, $childrenWrapClass, $limit
                            ) . '</li>';
                }
                $itemPosition++;
                $counter++;
            }
            if(is_array($colBrakes) || is_object($colBrakes)){
            if (count($colBrakes) && $limit) {
                $html = '<li class="column"><ul>' . $html . '</ul></li>';
            }
            }
            return $html;
        } else {
            return parent::_getHtml(
                            $menuTree, $childrenWrapClass, $limit, $colBrakes
            );
        }
    }

    /**
     * Get top menu html
     *
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string
     */
    public function getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0) {
        if ($childrenWrapClass == "mega") {
            $childrenWrapClass = "submenu";
            $this->_eventManager->dispatch(
                    'page_block_html_topmenu_gethtml_before', ['menu' => $this->_menu, 'block' => $this]
            );

            $this->_menu->setOutermostClass($outermostClass);
            $this->_menu->setChildrenWrapClass($childrenWrapClass);

            $html = $this->_getHtml2($this->_menu, $childrenWrapClass, $limit);

            $transportObject = new \Magento\Framework\DataObject(['html' => $html]);
            $this->_eventManager->dispatch(
                    'page_block_html_topmenu_gethtml_after', ['menu' => $this->_menu, 'transportObject' => $transportObject]
            );
            $html = $transportObject->getHtml();
            return $html;
        } else {
            return parent::getHtml($outermostClass, $childrenWrapClass, $limit);
        }
    }

    public function allowExtension() {
        return $this->dataHelper->allowExtension();
    }

}
