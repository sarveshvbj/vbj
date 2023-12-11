<?php 
$userData = array("username" => "zohocrm", "password" => "Sarvesh@123");
$ch = curl_init("https://www.vaibhavjewellers.com/index.php/rest/V1/integration/admin/token");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));
$token = curl_exec($ch);
echo $token;
die();
/*$customerData = [
    'customer_id' => 416
];
$ch = curl_init("https://www.vaibhavjewellers.com/staging/index.php/rest/V1/carts");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($customerData));
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));

$result = curl_exec($ch);
$quote_id = json_decode($result, 1);
echo '<pre>';print_r($quote_id);
$productData = [
    'cart_item' => [
        'quote_id' => $quote_id,
        'sku' => 'JBF05930Q',
        'qty' => 1
    ]
];
$ch = curl_init("https://www.vaibhavjewellers.com/staging/index.php/rest/V1/carts/".$quote_id."/items");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($productData));
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));

$result = curl_exec($ch);

$result = json_decode($result, 1);
echo '<pre>';print_r($result);
$ch = curl_init("https://www.vaibhavjewellers.com/staging/index.php/rest/V1/carts/".$quote_id);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));

$result = curl_exec($ch);

$result = json_decode($result, 1);
echo '<pre>';print_r($result);*/
//registered users 
$userDatas = array("username" => "sarvesh", "password" => "Sarvesh@123");
$ch = curl_init("https://www.vaibhavjewellers.com/index.php/rest/V1/integration/customer/token");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userDatas));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userDatas))));
echo $tokens = curl_exec($ch);
$customerData = [
    'customer_id' => 416
];
$ch = curl_init("https://www.vaibhavjewellers.com/staging/index.php/rest/V1/carts/mine");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($tokens));
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($tokens)));
$result = curl_exec($ch);
$quote_id = json_decode($result, 1);
echo '<pre>';print_r($quote_id);
$productData = [
    'cart_item' => [
        'quote_id' => $quote_id,
        'sku' => 'JBF05930Q',
        'qty' => 1
    ]
];
$ch = curl_init("https://www.vaibhavjewellers.com/staging/index.php/rest/V1/carts/mine/items");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($productData));
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($tokens)));

$result = curl_exec($ch);

$result = json_decode($result, 1);
echo '<pre>';print_r($result);
$ch = curl_init("https://www.vaibhavjewellers.com/staging/index.php/rest/V1/carts/mine");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($tokens)));

$result = curl_exec($ch);

$result = json_decode($result, 1);
echo '<pre>';print_r($result);
die();
?>