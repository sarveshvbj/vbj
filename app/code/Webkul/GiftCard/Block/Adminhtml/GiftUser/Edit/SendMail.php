<?php
namespace Webkul\GiftCard\Block\Adminhtml\GiftUser\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class BackButton
 */
class SendMail extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    /*public function __construct(
        \Magento\Framework\App\RequestInterface $request
        ) {

        $this->request = $request;
        

    }*/
    public function getButtonData()
    {
        //$param = $this->request->getPost();
        return [
            'label' => __(''),
           'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'sendmail']],
                'form-role' => 'sendmail',
            ],
            'sort_order' => 12
        ];
        /*return [
            'label' => __('Send Email'),
            'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to mail this contact ?')
                    . '\', \'' . $this->getSendmail() . '\')',
            'sort_order' => 12
        ];*/
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getSendmail()
    {
        /*$isPost = $this->request->getParam();//$this->getRequest()->getPost();
        echo '<pre>';
        print_r($isPost);
        echo '</pre>';
        die();*/
        //return $this->getUrl('*/*/index');
        return $this->getUrl('giftcard/giftuser/sendmail');
    }
}