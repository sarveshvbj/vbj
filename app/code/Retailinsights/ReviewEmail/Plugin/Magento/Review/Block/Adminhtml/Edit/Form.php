<?php

namespace Retailinsights\ReviewEmail\Plugin\Magento\Review\Block\Adminhtml\Edit;

class Form extends \Magento\Review\Block\Adminhtml\Edit\Form
{
    public function beforeSetForm(\Magento\Review\Block\Adminhtml\Edit\Form $object, $form) {

        $review = $object->_coreRegistry->registry('review_data');

        $fieldset = $form->addFieldset(
            'review_details_extra',
            ['legend' => __('Review Details Extra Data'), 'class' => 'fieldset-wide']
        );

        $fieldset->addField(
            'email',
            'text',
            ['label' => __('E-mail'), 'required' => false, 'name' => 'email']
        );

        $form->setValues($review->getData());

        return [$form];
    }
}