<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class ConcertController extends Controller
{

    public function payment(Request $request)
    {


<<<<<<< HEAD
        $customer1 = DB::table('customers')
        -> where('invoices', '=', $request)
        -> get();
        
        if(!$customer1){
            return response()->json([
                "pesan" => "Ga ada no id nya coy"
            ]);
        }else {          
            return response()->json([
                "pesan" => "berhasil ngambil coy",
                "data" => $customer1
            ]);
        }
        
        

    }
=======
        $validator = Validator::make($request->all(), [
            "nama" => "required|string",
            "telpon" => "required|string",
            "email" => "required|string|unique:customers,email",
            "total" => "required|numeric",
            "invoices" => "required|string|unique:customers,invoices",
        ]);
>>>>>>> faisal


        if ($validator->fails()) {
            return response()->json([
                "message" => "failed",
                "errors" => $validator->errors()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        // $validated = $validator->validated();

        try {
            // $customer = Customer::create($validated);

            $customer = Customer::create([
                "nama" => $request->nama,
                "telpon" => $request->telpon,
                "email" => $request->email,
                "kuantiti" => $request->total,
                "total" => $request->total * 100000,
                "invoices" => $request->invoices,
                "status_transaksi" => $request->status_transaksi
            ]);


            $requestBody = array(
                'order' =>
                array(
                    'amount' => $request->total * 100000,
                    'invoice_number' => $request->invoices,
                    'disable_retry_payment' => true,
                    'callback_url' => 'http://127.0.0.1:8000/redirect/' . $request->invoices,
                    'line_items' =>
                    array(
                        0 =>
                        array(
                            'name' => 'Tiket konser',
                            'price' => 100000,
                            'quantity' => $request->total
                        ),
                    ),
                ),
                'payment' =>
                array(
                    'payment_due_date' => 60,
                ),
                'customer' =>
                array(
                    'name' => $request->nama,
                    'email' => $request->email,
                    'phone' => $request->hp
                ),
            );
            $requestId = rand(1, 100000);
            $dateTime = gmdate("Y-m-d H:i:s");
            $isoDateTime = date(DATE_ISO8601, strtotime($dateTime));
            $dateTimeFinal = substr($isoDateTime, 0, 19) . "Z";
            $clientId = 'BRN-0218-1668854741147';
            $secretKey = 'SK-S4fMVZXAjnPsqIeHaJqc';
            // $getUrl = 'https://api.doku.com';
            $getUrl = 'https://api-sandbox.doku.com';
            $targetPath = '/checkout/v1/payment';
            $url = $getUrl . $targetPath;

            // Generate digest
            $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));
            // Prepare signature component
            $componentSignature = "Client-Id:" . $clientId . "\n" .
                "Request-Id:" . $requestId . "\n" .
                "Request-Timestamp:" . $dateTimeFinal . "\n" .
                "Request-Target:" . $targetPath . "\n" .
                "Digest:" . $digestValue;
            // Generate signature
            // echo $componentSignature."<br>";
            $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));
            // Execute request
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
            // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Client-Id:' . $clientId,
                    'Request-Id:' . $requestId,
                    'Request-Timestamp:' . $dateTimeFinal,
                    'Signature:' . "HMACSHA256=" . $signature,
                )
            );
            // Set response json
            $responseJson = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            //  echo $responseJson; 

            if (is_string($responseJson) && $httpcode == 200) {
                //echo $responseJson;
                $content = json_decode($responseJson, true);
                $result = $content['response']['payment']['url'];


            }

            return Redirect::to($result);
        } catch (\Exception $e) {
            return response()->json([
                "massage" => "failed gans",
                "errors" => $e->getMessage()
            ]);
        }
    }



    public function directInquiry()
    {
        $notificationHeader = getallheaders();

        $requestId = $notificationHeader['Request-Id'];
        $dateTime = gmdate("Y-m-d H:i:s");
        $isoDateTime = date(DATE_ISO8601, strtotime($dateTime));
        $dateTimeFinal = substr($isoDateTime, 0, 19) . "Z";
        // $clientId = 'BRN-0236-1660639706256';
        // $secretKey = 'SK-ZVplbH03WHeeBry6XSAu';

        $clientId = 'BRN-0255-1648461011109';
        $secretKey = 'SK-q20IbleXrCVxMYYThh5H';

        $targetPath = '/api/inquiry';

        $requestBody = array (
            'order' => array (
                'invoice_number' => 'INV-20210124-0001',
                'min_amount' => 10000,
                'max_amount' => 200000
            ),
            'virtual_account_info' => array (
                'virtual_account_number' => '1899100712837192',
                'info1' => 'Merchant tot',
            ),
            'virtual_account_inquiry' => array(
                'status' => 'success'
            ),
            'customer' => array (
                'name' => 'iasjids',
                'email' => 'asdas@example.com',
            )
        );
        // Generate digest
        $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));



        $componentSignature = "Client-Id:" . $clientId . "\n" . 
                      "Request-Id:" . $requestId . "\n" .
                      "Request-Timestamp:" . $dateTimeFinal . "\n" . 
                      "Request-Target:" . $targetPath . "\n" .
                      "Digest:" . $digestValue;

        // Generate signature
        // echo $componentSignature."<br>";
        $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));



        // return response()->json([
        //     "order" => [
        //         "invoice_number" => "Okokdaja",
        //         "min_amount" => 0,
        //         "max_amount" => 20000
        //     ],
        //     "virtual_account_info" => [
        //         "virtual_account_number" => "1899100712837192",
        //         "info1" => "Thanks for shooping"
        //     ],
        //     "virtual_account_inquiry" => [
        //         "status" => "success"
        //     ],
        //     "customer" => [
        //         "name" => "ecudoar",
        //         "phone" => "6282240958466",
        //         "email" => "test@sk.com"
        //     ]
        // ], 200)
        return response($requestBody)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Client-Id' => $clientId,
                'Request-Id' => $requestId,
                'Response-Timestamp' => $dateTimeFinal,
                'Signature' => "HMACSHA256=" . $signature,
                'digis' => $digestValue,
                'waktu' => $notificationHeader['Request-Timestamp']
            ]);



    }
// }

// Signature gue = HMACSHA256=2fRHCYki5d6+1oiF0pzsj4sVeuryKU3C/xq+psUtMH4=
// doku = HMACSHA256=mg6EeDS39vtDdg3vhxTCiGFwlhyMieSNp9TnwTc3MZs=


// Client-Id: BRN-0218-1668854741147
// Request-Id:ONLINE_TO_OFFLINE_ALFA2226221125174215456107189774613000169006
// Response-Timestamp:2022-11-25T10:42:15Z
// Request-Target:/api/inquiry
// Digest:MgOZf1yUsgxMpfC1tY+ftqzhBx+CyzHQ6DbAj7eFi6Y= 


// Client-Id: BRN-0218-1668854741147
// Request-Id: ONLINE_TO_OFFLINE_ALFA2226221125174215456107189774613000169006
// Response-Timestamp: 2022-11-25T10:42:15Z
// Signature: HMACSHA256=2fRHCYki5d6+1oiF0pzsj4sVeuryKU3C/xq+psUtMH4=

    public function scanner(){
        return view('scanner', []);
    }



}