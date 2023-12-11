<?php

/**
 * Class for NoFollowIndex SaveCmsFields
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;

class SaveCmsFields implements ObserverInterface
{
    protected $request;
    protected $_objectManager;
    protected $_helper;
    protected $_coreResource;

    public function __construct(
    Context $context,
    \FME\NoFollowIndex\Helper\Data $helper,
		\Magento\Framework\App\ResourceConnection $coreResource)
    {
        $this->_objectManager = $context->getObjectManager();
        $this->request = $context->getRequest();
        $this->_helper = $helper;
    		$this->_coreResource = $coreResource;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
      if ($this->_helper->enableNoFollowIndexExtension() == 1)
      {
        $postdata = $this->request->getPost();
        $enablevalue = $postdata['data']['cms']['nofollowindex_enable'];
        $followvalue = $postdata['data']['cms']['nofollowindex_followvalue'];
        $indexvalue = $postdata['data']['cms']['nofollowindex_indexvalue'];
        $noarchivevalue = $postdata['data']['cms']['nofollowindex_noarchivevalue'];
        $type = 'cms';
        $table = $this->_coreResource->getTableName('fme_nofollowindex');
        $connectionread = $this->_coreResource->getConnection('core_read');
        $connectionwrite = $this->_coreResource->getConnection('core_write');

        if ($postdata['page_id'])
        {
          // (existing page)
          $pageid = $postdata['page_id'];
  				$selectdatasql0 = 'select * from ' . $table . ' where (nofollowindex_itemid =' . $pageid . ') and (nofollowindex_itemtype=\'cms\')';
  				$result0 = $connectionread->fetchAll($selectdatasql0);
  				if (sizeof($result0) > 0)
  				{
  					$updatedatasql0 = "update " . $table . " SET nofollowindex_itemfollowvalue = '$followvalue', nofollowindex_itemindexvalue = '$indexvalue', nofollowindex_itemenablevalue = '$enablevalue', nofollowindex_itemnoarchivevalue = '$noarchivevalue' where nofollowindex_itemid = '$pageid' and nofollowindex_itemtype = 'cms'";
  					$result0 = $connectionwrite->query($updatedatasql0);
  				}
          else
          {
            $updatedatasql10 = "insert into " . $table . " (nofollowindex_itemid, nofollowindex_itemtype, nofollowindex_itemfollowvalue, nofollowindex_itemindexvalue, nofollowindex_itemenablevalue, nofollowindex_itemnoarchivevalue) VALUES ('".$pageid."','" . $type . "','". $followvalue . "','". $indexvalue . "','". $enablevalue . "','". $noarchivevalue . "')";
  					$result10 = $connectionwrite->query($updatedatasql10);
          }
        }
        else
        {
          // (new page)
          // fetch last cms page id and increment it with 1 cause that would be the id of next cms page
          $selectdatasql1 = 'select page_id from cms_page ORDER BY page_id DESC LIMIT 1';
					$result1 = $connectionread->fetchAll($selectdatasql1);
          $pageid = $result1[0]['page_id'];
          $pageid = $pageid + 1;
          // need to check your table that it would contain this id or not
          // if yes then it's mean that the last record in your table was not right
          // delete it first before insersion
          $selectdatasql2 = 'select * from '.$table.' where nofollowindex_itemid = '.$pageid. ' and nofollowindex_itemtype =\'cms\'';
          $result2 = $connectionread->fetchAll($selectdatasql2);
          if (sizeof($result2) > 0)
  				{
  					// means last entry was not correct delete it
            // delete record
            $insertdatasql0 = 'delete from '.$table.' where nofollowindex_itemid = '.$pageid. ' and nofollowindex_itemtype =\'cms\'';
  					$result2 = $connectionwrite->query($insertdatasql0);
  				}
          // insert new record
					$insertdatasql = "insert into " . $table . " (nofollowindex_itemid, nofollowindex_itemtype, nofollowindex_itemfollowvalue, nofollowindex_itemindexvalue, nofollowindex_itemenablevalue, nofollowindex_itemnoarchivevalue) VALUES ('".$pageid."','" . $type . "','". $followvalue . "','". $indexvalue . "','". $enablevalue . "','". $noarchivevalue . "')";
					$result3 = $connectionwrite->query($insertdatasql);
        }
      }
    }
}
