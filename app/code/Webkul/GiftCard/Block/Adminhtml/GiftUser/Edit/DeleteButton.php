<?php
namespace Webkul\GiftCard\Block\Adminhtml\GiftUser\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\UrlInterface $url) {

        $this->request = $request;
        $this->url = $url;

    }

    public function getButtonData()
    {

        $id = $this->request->getParam('id');
        $data = [];
        if ($id) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this contact ?')
                    . '\', \'' . $this->getDeleteUrl($id) . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl($id)
    {
        return $this->url->getUrl('*/*/delete',['id' => $id]);
    }
}