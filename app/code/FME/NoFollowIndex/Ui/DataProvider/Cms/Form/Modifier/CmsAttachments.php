<?php

/**
 * Class for NoFollowIndex CmsAttachments
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Ui\DataProvider\Cms\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use FME\NoFollowIndex\Ui\DataProvider\Cms\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Checkbox;

class CmsAttachments extends AbstractModifier
{
  private $locator;
  protected $_registry;
  protected $_helper;
  protected $_coreResource;

  public function __construct(
      LocatorInterface $locator,
      \FME\NoFollowIndex\Helper\Data $helper,
      \Magento\Framework\Registry $registry,
      \Magento\Framework\App\ResourceConnection $coreResource
  ) {
      $this->locator = $locator;
      $this->_coreResource = $coreResource;
      $this->_helper = $helper;
      $this->_registry = $registry;
  }

    public function modifyData(array $data)
    {
      if ($this->_helper->enableNoFollowIndexExtension() == 1)
      {
        $result = $data;
        $pageid = '';
        foreach ($result as $key => $value)
        {
          $pageid = $value['page_id'];
        }
        $connectionread = $this->_coreResource->getConnection('core_read');
        $table = $this->_coreResource->getTableName('fme_nofollowindex');
        $selectdatasql0 = 'select * from ' . $table . ' where (nofollowindex_itemid =' . $pageid . ') and (nofollowindex_itemtype=\'cms\')';
        $result0 = $connectionread->fetchAll($selectdatasql0);
        if (sizeof($result0) > 0)
        {
          $enablevalue = $result0[0]['nofollowindex_itemenablevalue'];
          $followvalue = $result0[0]['nofollowindex_itemfollowvalue'];
          $indexvalue = $result0[0]['nofollowindex_itemindexvalue'];
          $noarchivevalue = $result0[0]['nofollowindex_itemnoarchivevalue'];
          $result[$pageid] += array('data' => array('cms' => array('nofollowindex_enable' => $enablevalue)));
          $result[$pageid]['data']['cms'] += array('nofollowindex_followvalue' => $followvalue);
          $result[$pageid]['data']['cms'] += array('nofollowindex_indexvalue' => $indexvalue);
          $result[$pageid]['data']['cms'] += array('nofollowindex_noarchivevalue' => $noarchivevalue);
          return $result;
        }
        return $result;
      }
      return $data;
    }

    public function modifyMeta(array $meta)
    {
      $meta['nofollowindex'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('No Follow Index'),
                        'sortOrder' => 1,
                        'collapsible' => true,
                        'componentType' => 'fieldset'
                    ]
                ]
            ],
            'children' => [
              'nofollowindex_enable' => [
                  'arguments' => [
                      'data' => [
                          'config' => [
                            'componentType' => Field::NAME,
                            'formElement' => Checkbox::NAME,
                            'prefer' => 'toggle',
                            'valueMap' => [
                                'true' => '1',
                                'false' => '0'
                            ],
                            'dataScope' => "data.cms.nofollowindex_enable",
                            'required' => 0,
                            'label' => __('Enable No Follow Index')
                          ]
                      ]
                  ]
              ],
                'nofollowindex_followvalue' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'select',
                                'componentType' => 'field',
                                'visible' => 1,
                                'options' => [
                                    ['value' => '1', 'label' => __('Follow')],
                                    ['value' => '2', 'label' => __('No Follow')]
                                ],
                                'dataScope' => "data.cms.nofollowindex_followvalue",
                                'required' => 0,
                                'label' => __('Follow Value')
                            ]
                        ]
                    ]
                ],
                'nofollowindex_indexvalue' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'select',
                                'componentType' => 'field',
                                'visible' => 1,
                                'options' => [
                                    ['value' => '1', 'label' => __('Index')],
                                    ['value' => '2', 'label' => __('No Index')]
                                ],
                                'dataScope' => "data.cms.nofollowindex_indexvalue",
                                'required' => 0,
                                'label' => __('Index Value')
                            ]
                        ]
                    ]
                ],
                'nofollowindex_noarchivevalue' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                              'componentType' => Field::NAME,
                              'formElement' => Checkbox::NAME,
                              'sortOrder' => 44,
                                'value' => '0',
                                'valueMap' => [
                                    'true' => '1',
                                    'false' => '0'
                                ],
                                'dataScope' => "data.cms.nofollowindex_noarchivevalue",
                                'required' => 0,
                                'label' => __('No Archive')
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return $meta;
    }
}
