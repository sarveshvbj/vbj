<?php 
date_default_timezone_set("Asia/Calcutta"); 
ob_start();
use Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
ini_set('memory_limit', '1024M');
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$now = new \DateTime();
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$OrderFactory = $objectManager->create('Magento\Sales\Model\ResourceModel\Order\CollectionFactory');
$orderCollection = $OrderFactory->create()->addFieldToSelect(array('*'));
$yeserday=date('Y-m-d H:i:s',strtotime("-1 days"));
//$todaydate=date('Y-m-d H:i:s');
$orderCollection->addFieldToFilter('created_at', ['lteq' => $now->format('Y-m-d H:i:s')])->addFieldToFilter('created_at', ['gteq' => $yeserday]);

 $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
 $connection = $resource->getConnection();
foreach ($orderCollection->getData() as $getresult){
    
 // $invoice = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($getresult['increment_id']);// Your invoice increment id here
//echo "<pre>";
//print_r($invoice->getData());
 $order=$objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($getresult['increment_id']);
 $payment = $order->getPayment();
$method = $payment->getMethodInstance();
$methodTitle = $method->getTitle();
$customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
$customerId = $getresult['customer_id'];

$customer = $customerFactory->load($customerId);


$orderItems = $order->getAllItems();
    $itemQty = array();
    $i = 0;
    foreach ($orderItems as $item) {
       
        
        
         $itemQty[$i]['quantity'] = $item->getQtyOrdered();
         $itemQty[$i]['description'] = $item->getDescription();
         $itemQty[$i]['name'] = $item->getName();
         $itemQty[$i]['price'] = $item->getPrice();
         $itemQty[$i]['sku'] = $item->getSku();
         $itemQty[$i]['productId'] = $item->getProductId();
         $i++;
          
    }

$productIdsArray = array($itemQty[0]['productId']);
$products = $objectManager->create("Magento\Catalog\Model\Product")->getCollection()->addAttributeToFilter('entity_id',array('in'=> $productIdsArray));

$allCategories = array();
foreach ($products as $product) {
    $allCategories = array_merge($allCategories, $product->getCategoryIds());
}
if(!empty($allCategories)){
$getcat=implode(',',$allCategories);
$categeroyvalues = array();
foreach($allCategories as $category){
    $cat = $objectManager->create('Magento\Catalog\Model\Category')->load($category);
    array_push($categeroyvalues, $cat->getName());
    }
 $insertcategoryname=implode(',', $categeroyvalues);
}
else{
   $insertcategoryname=""; 
}




$BillingAddressObj = $order->getBillingAddress();

$BillingAddressArray = $BillingAddressObj->getData();
$countryname = $objectManager->create('\Magento\Directory\Model\Country')->load($BillingAddressArray['country_id'])->getName();

$name=$BillingAddressArray['firstname'].' '.$BillingAddressArray['lastname'];
$email=$BillingAddressArray['email'];
$telephone=$BillingAddressArray['telephone']; 

$shippingAddressObj = $order->getShippingAddress();
$getshippping=$shippingAddressObj->getData();  


//echo "<pre>";
//print_r($getshippping);
//exit;

if(empty($getshippping) ){
    $Shipping_Address="";
}
else{
 $Shipping_Address=$getshippping['firstname'].' '.$getshippping['lastname'].' '.$getshippping['street'].' '.$getshippping['city'].'-'.$getshippping['postcode'].' '.$getshippping['telephone'];
 
}


$createDate=date('Y-m-d H:i:s');
$updateDate=date('Y-m-d H:i:s');


$sql = "SELECT * FROM `zoho_send_order_detail` where increment_id=".$getresult['increment_id']."";
$tableresults = $connection->fetchAll($sql);


if(count($tableresults)<=0){


$sqlinsert = "INSERT INTO zoho_send_order_detail(name, Country, Lead_Source, Email, Phone, Amount, Item_Amount, Product_Name, Product_SKU, deal_Lead_Source, Lead_Type, Currency, Payment_Mode, Category, Stage, Description, Shipping_Address, Invoice_Number, increment_id, flag, created_at, update_at, contact_state, contact_city, contact_leadtype, postcode) VALUES ('".$name."', '".$countryname."', 'Magneto Order', '".$email."', '".$telephone."', '".$getresult['grand_total']."', '".$itemQty[0]['price']."', '".$itemQty[0]['name']."', '".$itemQty[0]['sku']."', 'Magneto', 'Web Lead', 'INR', '".$methodTitle."', '".$insertcategoryname."', '".$getresult['status']."', 'null', '".$Shipping_Address."', '0', '".$getresult['increment_id']."', 'Y', '".$createDate."', '".$updateDate."', '".$getshippping['region']."', '".$getshippping['city']."', 'Web Lead', '".$getshippping['postcode']."')";
$connection->query($sqlinsert);
}
}

$sql = "SELECT * FROM `zoho_send_order_detail` where flag='Y' LIMIT 50";
$tableresults = $connection->fetchAll($sql);


if(count($tableresults)>0){
    
foreach ($tableresults as $getresultall){
    
    $curl_access1= curl_init('https://accounts.zoho.com/oauth/v2/token?refresh_token=1000.e8856a9d23e08fc994b7f6a34353f57e.ecf33d7586812b0a21941a08dd660dc3&client_id=1000.JNJ5X1SHVCC3EAHX8J4DNAXPQ5SYYH&client_secret=cbe1963424157859fc22285e7eb01e17dd1fc4d039&grant_type=refresh_token');
            curl_setopt($curl_access1, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl_access1, CURLOPT_HEADER, 0);
            curl_setopt($curl_access1, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_access1, CURLOPT_HTTPHEADER, array("Content-Length:0"));
            curl_setopt($curl_access1, CURLOPT_RETURNTRANSFER, true);
            $jsonobj1 = curl_exec($curl_access1);
            $arr_ref1 = json_decode($jsonobj1,true);
            $reftoken = $arr_ref1['access_token'];
     $fields = array("data" => array(["Last_Name" => $getresultall['name'],"Mailing_Country" => $getresultall['Country'],"Mailing_City" => $getresultall['contact_city'],"Mailing_State" => $getresultall['contact_state'],"Mailing_Zip" => $getresultall['postcode'],"Lead_Type" => $getresultall['contact_leadtype'],"Lead_Source" => $getresultall['Lead_Source'],"Email" => $getresultall['Email'],"Phone" => $getresultall['Phone']]));
       

$curl = curl_init('https://www.zohoapis.com/crm/v2/Contacts');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Content-Type:application/json",
                    "Authorization: Zoho-oauthtoken ".$reftoken
                    ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $jsonobj = curl_exec($curl);
            $getres=json_decode($jsonobj, true);
            
            
            
           if($getres['data'][0]['code'] =='SUCCESS'){
               
               $id=$getres['data'][0]['details']['id'];
               $owneridget=$getres['data'][0]['details']['Created_By']['id'];
          $fields = array("data" => array(["Owner" => array("id" => $owneridget),"Deal_Name" => $getresultall['name'],"Amount" => $getresultall['Amount'],"Item_Amount" => $getresultall['Item_Amount'],"Product_Name" => $getresultall['Product_Name'],"Product_SKU" => $getresultall['Product_SKU'],"Lead_Source" => $getresultall['deal_Lead_Source'],"Lead_Type" => $getresultall['Lead_Type'],"Currency" => $getresultall['Currency'],"Payment_Mode" => $getresultall['Payment_Mode'],"Category" => $getresultall['Category'],"Stage" => $getresultall['Stage'],"Description" => $getresultall['Description'],"Shipping_Address" => $getresultall['Shipping_Address'],"Invoice_Number" => $getresultall['Invoice_Number'],"Contact_Name" => array("id"=>$id)]));
     
          $curl = curl_init('https://www.zohoapis.com/crm/v2/Deals');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Content-Type:application/json",
                    "Authorization: Zoho-oauthtoken ".$reftoken
                    ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $jsonobj = curl_exec($curl);
            $getresdeals=json_decode($jsonobj, true);
          if($getresdeals['data'][0]['code'] =='SUCCESS'){
$sql1= "Update zoho_send_order_detail Set flag ='U' where increment_id = '" . $getresultall['increment_id'] . "'";
$connection->query($sql1);
echo $getresultall['increment_id']." value has been updated".PHP_EOL;
          }               
            }
    
}

}
else{
    echo "No New Orders, all the orders are updated";
}

?>
