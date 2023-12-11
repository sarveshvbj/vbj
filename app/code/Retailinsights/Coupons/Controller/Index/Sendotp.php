<?php
namespace Retailinsights\Coupons\Controller\Index;

class Sendotp extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
		$mob = $this->getRequest()->getPost('param');

        $mobile= $mob;
        $token = mt_rand(100000, 999999);
        try {
					$password = "vaibhav";//40850740";
					$username = "vaibhav";//"alsvetcare";
					//$smsMessage = "Mr. Senthil has requested for $token and for $token area to contact this customer please call on $token or send an email on $token.";
					$smsMessage = "Your Vaibhav verification OTP code is $token. Code valid for 10 minutes only, one time use. Please DO NOT share this OTP with anyone.";
					/*$messagetype = "N";
					$DReports = "Y";*/
          $from = urlencode('STRIKR'); // assigned Sender_ID
          $sender = urlencode('VAIBAV');
					$smsurl = "https://www.smsstriker.com/API/sms.php?";
					//$mobilenumber = "91".$mobile;
					$numbers = $mobile;
          $message = urlencode($smsMessage);
					$data =
"username="."$username"."&password="."$password"."&to="."$numbers"."&from="."$sender"."&msg="."$message"."&type=1";
					//?User=$USERNAME&passwd=$PASSWORD&mobilenumber=$mobile&message=$smsMessage&mtype=N&DR=Y";
					$templateId="1407162513779268909";
					 $ch = curl_init(); 
					 if (!$ch){
						$result['error']     = true;
						$result['message'] =  "Couldn't initialize a cURL handle";
					 }
					 
					 $ret = curl_setopt($ch, CURLOPT_URL,$smsurl);
					 curl_setopt ($ch, CURLOPT_POST, 1);
					 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);          
					 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					 curl_setopt ($ch, CURLOPT_POSTFIELDS,"username="."$username"."&password="."$password"."&to="."$numbers"."&from="."$sender"."&msg="."$message"."&type=1"."&template_id="."$templateId");
					 $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					 
					// commented for testin uncommet it afetr deplyin on server 
					 $curlresponse = curl_exec($ch); // execute
				
					//print_r($curlresponse);
					//exit;
					if(curl_errno($ch))
						$result['error']     = true;
						$result['message'] = curl_error($ch);
					
					 if (empty($ret)) {
						// some kind of an error happened
						$result['error']     = true;
						$result['message'] =  curl_error($ch);
						curl_close($ch); // close cURL handler
						
					 } else {
						$info = curl_getinfo($ch);
						$result['success']     = true;
						$result['message'] =  json_encode($info);
						//echo $info;
						curl_close($ch); // close cURL handler
					 }
				//}
				

                    $result['success'] = true;
                    $result['message'] = "OTP sent";

					$_SESSION['opt_coupon'] = $token; 
					$_SESSION['opt_mob'] = $mobile; 
                } catch (\Exception $e) {
                    //print_r( $e->getMessage());
                    $result['error'] = true;
                    $result['message'] = $e->getMessage();
                }
          
	}
}