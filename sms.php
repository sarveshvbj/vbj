<?php
 $username = "vaibhavs";
 $password = "vaibhavs";
 $numbers = "7715878743s"; // mobile number 7639567324  8099639614  33359 Dheeraj Sena 
 $from = urlencode('STRIKR'); // assigned Sender_ID
 $sender = urlencode('VAIBAVww');//"vaibav";
 /*echo $message = 'Dear Dheeraj Sena, we have received your order no 33359 Circle of Life Rose Gold Pendant, Wish
you Gold Luck';*///'your message should be go from here'; // Message text required to deliver on mobile number
 $message = urlencode('Cron run for every minutes');
 $data =
"username="."$username"."&password="."$password"."&to="."$numbers"."&from="."$sender"."&msg="."$message"."&type=1";
//echo $data;
 $api_url = "https://www.smsstriker.com/API/sms.php?".$data;
 //echo $api_url;
 $ch = curl_init();
 curl_setopt($ch,CURLOPT_URL, $api_url);
 curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
 $response = curl_exec($ch);
  //echo gettype($response);
  //echo $converted_res = ($response) ? 'true' : 'false';
 //echo $response;
 //curl_close($ch);
 ?>