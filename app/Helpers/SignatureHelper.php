<?php

namespace App\Helpers;

use GuzzleHttp\Psr7\Request;

class SignatureHelper
{
    public static function generateSignature($requestBody, $targetPath)
    {
        $requestId = rand(1, 100000);
        $dateTime = gmdate("Y-m-d H:i:s");
        $isoDateTime = date(DATE_ISO8601, strtotime($dateTime));
        $dateTimeFinal = substr($isoDateTime, 0, 19) . "Z";
        $clientId = 'MCH-0008-1218873017641';
        $secretKey = 'SK-ZI9HpN300mPpok4igmit';
        $getUrl = 'https://api-sandbox.doku.com';
        $url = $getUrl . $targetPath;
        $digestValue = null;

        // Inisialisasi dengan nilai null

        if ($requestBody) {
            // Generate digest jika $requestBody ada
            $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));
        }

        // Prepare signature component
        $componentSignature = "Client-Id:" . $clientId . "\n" .
            "Request-Id:" . $requestId . "\n" .
            "Request-Timestamp:" . $dateTimeFinal . "\n" .
            "Request-Target:" . $targetPath;

        if ($digestValue) {
            // Tambahkan digest ke signature component jika $digestValue ada
            $componentSignature .= "\nDigest:" . $digestValue;
        }

        // Generate signature
        $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));

        return [
            'signature' => $signature,
            'requestId' => $requestId,
            'clientId' => $clientId,
            'requesttime' => $dateTimeFinal,
            'url' => $url
        ];
    }

    public function directInquiryResponse($requestBody)
    {

        $notificationHeader = getallheaders();
        $requestId = $notificationHeader['Request-Id'];
        $dateTimeFinal = $notificationHeader['Request-Timestamp'];
        $clientId = 'MCH-0008-1218873017641';
        $secretKey = 'SK-ZI9HpN300mPpok4igmit';
        $targetPath = '/api/inquiry';

        // Generate digest
        $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));

        $componentSignature =
            "Client-Id:" . $clientId . "\n" .
            "Request-Id:" . $requestId . "\n" .
            "Response-Timestamp:" . $dateTimeFinal . "\n" .
            "Request-Target:" . $targetPath . "\n" .
            "Digest:" . $digestValue;

        $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));
        // $finalsignature = "HMACSHA256=" . $signature;

        return [
            'requestId' => $requestId,
            'requesttime' => $dateTimeFinal,
            'clientId' => $clientId,
            'signature' => $signature,
        ];
    }

    public function validationSignature($requestBody, $targetPath)
    {

        //data dari DOKU
        $notificationHeader = getallheaders();
        $signatureRequest = $notificationHeader['Signature'];
        $requestId = $notificationHeader['Request-Id'];
        $requestTime = $notificationHeader['Request-Timestamp'];
        $clientId = $notificationHeader['Client-Id'];
        $secretKey = 'SK-ZI9HpN300mPpok4igmit';


        // $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));
        $digestValue = base64_encode(hash('sha256', $requestBody, true));

        // Prepare signature component
        $componentSignature =
            "Client-Id:" . $clientId . "\n" .
            "Request-Id:" . $requestId . "\n" .
            "Request-Timestamp:" . $requestTime . "\n" .
            "Request-Target:" . $targetPath . "\n" .
            "Digest:" . $digestValue;

        // Generate signature
        $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));

        return [
            'signature' => $signature,
            'requestId' => $requestId,
            'clientId' => $clientId,
            'signatureRequest' => $signatureRequest
        ];
    }




}