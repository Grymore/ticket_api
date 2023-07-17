<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Helpers\SignatureHelper;

class ConcertController extends Controller
{

    public function payment(Request $request)
    {


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

            $requestBody = [
                'order' => [
                    'amount' => $request->total * 100000,
                    'invoice_number' => $request->invoices,
                    'disable_retry_payment' => true,
                    'callback_url' => 'http://127.0.0.1:8000/redirect/' . $request->invoices,
                    'line_items' => [
                        [
                            'name' => 'Tiket konser',
                            'price' => 100000,
                            'quantity' => $request->total
                        ],
                    ],
                ],
                'payment' => [
                    'payment_due_date' => 60,
                ],
                'customer' => [
                    'name' => $request->nama,
                    'email' => $request->email,
                    'phone' => $request->hp
                ],
            ];
            

            $targetPath = '/checkout/v1/payment';
            //generate signature ambil dari helpers
            $result = SignatureHelper::generateSignature($requestBody, $targetPath);
            $signature = $result['signature'];
            $requestId = $result['requestId'];
            $clientId = $result['clientId'];
            $requesttime = $result['requesttime'];
            $url = $result['url'];


            // Execute request
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Client-Id' => $clientId,
                'Request-Id' => $requestId,
                'Request-Timestamp' => $requesttime,
                'Signature' => "HMACSHA256=" . $signature,
            ])->post($url, $requestBody);

            if ($response->ok()) {
                $result = $response->json()['response']['payment']['url'];
                Customer::create([
                    "nama" => $request->nama,
                    "telpon" => $request->telpon,
                    "email" => $request->email,
                    "kuantiti" => $request->total,
                    "total" => $request->total * 100000,
                    "invoices" => $request->invoices,
                    "status_transaksi" => $request->status_transaksi
                ]);
            }
            return Redirect::to($result);


        } catch (\Exception $e) {
            // return response()->json([
            //     "massage" => "failed gans",
            //     "errors" => $e
            // ]);
            return redirect('http://127.0.0.1:8000')->with('alert', 'Pesan alert di sini');
            //harus halaman gagal
        }
    }

    public function directInquiry(Request $request)
    {
        
        $inv = 'inv' . time();

        $requestBody = [
            'order' => [
                'invoice_number' => $inv,
                'amount' => 100000,
            ],
            'virtual_account_info' => [
                'virtual_account_number' => '1899104608577701',
                'info1' => 'fakriji',
            ],
            'virtual_account_inquiry' => [
                'status' => 'success',
            ],
            'customer' => [
                'name' => 'faisal',
                'email' => 'customer@gmail.com',
            ],
        ];

        //jika dinamic bisa dipanggil seperti ini
        $signatureHelper = new SignatureHelper();
        $result = $signatureHelper->directInquiryResponse($requestBody);

        $clientId = $result['clientId'];
        $requestId = $result['requestId'];
        $requesttime = $result['requesttime'];
        $signature = $result['signature'];

        return response()->json($requestBody)
            ->withHeaders([
                'Client-Id' => $clientId,
                'Request-Id' => $requestId,
                'Response-Timestamp' => $requesttime,
                'Signature' => "HMACSHA256=" .$signature
            ]);
    }


    public function checkStatus(Request $request, $invoice)
    {
        $targetPath = '/orders/v1/status/' . $invoice;

        //generate signature ambil dari helpers
        $result = SignatureHelper::generateSignature(null, $targetPath);
        $signature = $result['signature'];
        $clientId = $result['clientId'];
        $requesttime = $result['requesttime'];
        $url = $result['url'];
        $requestId = $result['requestId'];



        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $requesttime,
            'Signature' => "HMACSHA256=" . $signature,
        ])->get($url);

        $responseJson = $response->json();
        return response()->json([
            'response' => $responseJson
        ]);

    }

}