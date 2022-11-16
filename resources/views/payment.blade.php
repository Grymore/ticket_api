<?php
$requestBody = array (
    'order' =>
        array (
            'amount' => 210000,
            'invoice_number' => 'INV'.time(),
            // 'currency' => 'IDR',
            'callback_url' => 'http://127.0.0.1:8000//callback.php',
            'line_items' =>
            array (
            0 =>
            array (
                'name' => 'Tiket konser',
                'price' => 210000,
                'quantity' => 1
            ),
            
            ),
        ),

    // 'additional_info' =>
    // array (
    //     'hold_settlement'=> 'true',
    //     'settlement' =>
    //     array (
    //     0 =>
    //     array (
    //         'bank_account_settlement_id' => 'SBA-0081-1909877819840',
    //         'value' => 10,
    //         'type' => 'PERCENTAGE'
    //     ),
    //     1 =>
    //     array (
    //         'bank_account_settlement_id' => 'SBS-0003-20210915155101796',
    //         'value' => 90,
    //         'type' => 'PERCENTAGE'
    //     )
    //     ),
    // ),

    'payment' =>
    array (
        'payment_due_date' => 60,
        // 'payment_method_types' =>
        // array (
        //   0 => 'VIRTUAL_ACCOUNT_BCA',
          // 1 => 'VIRTUAL_ACCOUNT_BANK_MANDIRI',
          // 2 => 'VIRTUAL_ACCOUNT_BANK_SYARIAH_MANDIRI',
          // 3 => 'VIRTUAL_ACCOUNT_DOKU',
          // 4 => 'ONLINE_TO_OFFLINE_ALFA',
          // 0 => 'CREDIT_CARD',
          // 6 => 'DIRECT_DEBIT_BRI',
        // ),
    ),
    'customer' =>
    array (
        'id' => '112222233',
        'name' => 'Doku Test',
        'email' => 'test@doku.com',
        'phone' => '6287805586273',
        'address' => 'Jalan Sunset road Kuta Bali',
        'country' => 'ID',
    ),
);;
$requestId = rand(1, 100000); // You can use UUID or anything
// echo $requestId;
$dateTime = gmdate("Y-m-d H:i:s");
// echo $dateTime."<br>";
$isoDateTime = date(DATE_ISO8601, strtotime($dateTime));
// echo $isoDateTime."<br>";
$dateTimeFinal = substr($isoDateTime, 0, 19) . "Z";
// echo $dateTimeFinal."<br>";
// echo $dateTimeFinal; exit;
// $clientId = 'MCH-0098-xxx'; // Change with your Client ID
$clientId = 'BRN-0295-1662445176970';
// $secretKey = 'SK-xxx'; // Change with your Secret Key
$secretKey = 'SK-SifuCF735QJoC6okzTlc';
// $getUrl = 'https://api.doku.com';
$getUrl = 'https://api-sandbox.doku.com';
$targetPath = '/checkout/v1/payment';
$url = $getUrl . $targetPath;

// Generate digest
$digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));
// Prepare signature component
$componentSignature = "Client-Id:".$clientId ."\n".
                    "Request-Id:".$requestId . "\n".
                    "Request-Timestamp:".$dateTimeFinal ."\n".
                    "Request-Target:".$targetPath ."\n".
                    "Digest:".$digestValue;
// Generate signature
// echo $componentSignature."<br>";
$signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));
// Execute request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Client-Id:' . $clientId,
    'Request-Id:' . $requestId,
    'Request-Timestamp:' . $dateTimeFinal,
    'Signature:' . "HMACSHA256=" . $signature,
));
// Set response json
$responseJson = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

//  echo $responseJson; 
// Echo the response
if (is_string($responseJson) && $httpcode == 200) {
    //echo $responseJson;
    $content=json_decode($responseJson,true);
    $result = $content['response']['payment']['url'];

    
    // echo $result;
    return $result;

?>

<?php
} else {
    header('/');
}
?>
