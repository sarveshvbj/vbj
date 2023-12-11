<?php
namespace Iksula\Complaint\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
   /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {

        $this->_objectManager = $objectManager;
        parent::__construct($context);
    }

   public function send_sms($mobile,$template,$templateId)
    {
        if($mobile && $template)
        {
           $username = "vaibhav";
           $password = "vaibhav";
           $numbers = "7715878743"; // mobile number
           if(!is_numeric($mobile))
            {
                return null;
            }
            else if(strlen($mobile)==10)
            {
                $mobile_no = $mobile;    //  PREPEND COUNTRY CODE
            }
            else
            {
                $mobile_no = $mobile;
            }
           //$from = urlencode('STRIKR'); // assigned Sender_ID
           $sender = urlencode('VAIBAV');//"vaibav";
           $message = urlencode($template); // Message text required to deliver on mobile number
           $data =
           "username="."$username"."&password="."$password"."&to="."$mobile_no"."&from="."$sender"."&msg="."$message"."&type=1&template_id="."$templateId";
            //echo $data;
             $api_url = "https://www.smsstriker.com/API/sms.php?".$data;
             //echo $api_url;
             $ch = curl_init();
             curl_setopt($ch,CURLOPT_URL, $api_url);
             curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            $response = curl_exec($ch);
        }
    }
  }