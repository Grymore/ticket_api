<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\IsNull;



class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::all();

        return response()->json([
            "message" => "Succesfully fetched",
            "datalist" => $customer
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nama" => "required|string",
            "telpon" => "required|string",
            "email" => "required|string|unique:customers,email",
            "total" => "required|numeric",
        ]);


        if ($validator->fails()) {
            return response()->json([
                "message" => "failed",
                "errors" => $validator->errors()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
        $validated = $validator->validated();

        try {
            $createdCustomer = Customer::create($validated);
        } catch (\Exception $e) {
            return response()->json([
                "massage" => "failed gans",
                "errors" => $e->getMessage()
            ]);
        }

        return response()->json([
            "pesan" => "berhasil coy",
            "terdaftar" => $createdCustomer
        ]);

    }



    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            "nama" => "string",
            "telpon" => "string",
            "email" => "string",
            "total" => "numeric",
        ]);


        if ($validator->fails()) {
            return response()->json([
                "message" => "failed",
                "errors" => $validator->errors()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
        $validated = $validator->validated();

        try {

            $customerUpdate = Customer::findOrFail($id);
            $customerUpdate->update($validated);
        } catch (\Exception $e) {
            return response()->json([
                "massage" => "failed gans",
                "errors" => $e->getMessage()
            ]);
        }

        return response()->json([
            "pesan" => "berhasil update coy",
            "data" => $customerUpdate
        ]);

    }



    public function show($id)
    {
        $customer = Customer::findOrFail($id);


        return response()->json([
            "pesan" => "berhasil ngambil coy",
            "data" => $customer
        ]);
    }

    public function destroy($id)
    {

        $customer = Customer::findOrFail($id);
        $customer->delete();
        return response()->json([
            "pesan" => "berhasil ngapusin coy data {$id}",

        ]);
    }

    public function updateStatus()
    {

        $notificationHeader = getallheaders();
        $notificationBody = file_get_contents('php://input');
        $notificationPath = '/api/notify'; // Adjust according to your notification path
        // $clientId = 'BRN-0295-1662445176970';
        $secretKey = 'SK-SifuCF735QJoC6okzTlc';

        $digest = base64_encode(hash('sha256', $notificationBody, true));
        $rawSignature = "Client-Id:" . $notificationHeader['Client-Id'] . "\n"
            . "Request-Id:" . $notificationHeader['Request-Id'] . "\n"
            . "Request-Timestamp:" . $notificationHeader['Request-Timestamp'] . "\n"
            . "Request-Target:" . $notificationPath . "\n"
            . "Digest:" . $digest;

        $signature = base64_encode(hash_hmac('sha256', $rawSignature, $secretKey, true));
        $finalSignature = 'HMACSHA256=' . $signature;


        if ($finalSignature == $notificationHeader['Signature']) {
           
            $response = json_decode($notificationBody, true);
            $invoice = $response['order']['invoice_number'];    
            $cariin = json_decode(DB::table('customers')->where('invoices', $invoice)->get('invoices'),true);

            if (isset($cariin[0]['invoices']) == $invoice){
                
                DB::table('customers')
                    ->where('invoices', $invoice)
                    ->update(['status_transaksi' => $response['transaction']['status']]);
                    
                    return response()->json([
                        "pesan" => "berhasil update",
                        "data" => $cariin,
                        "status" => $response['transaction']['status']
                    ]);

            }else {

                return response()->json([
                    "pesan" => "gagal update datanya ora ada",
                    "invoices" => $response['order']['invoice_number']
                ]);
            }
        } else {
        
            return response()->json([
                "dataHeadr" => $notificationHeader['Signature'],
                "dataBody" => $notificationBody,
                "digest" => $digest,
                "siganture" => $finalSignature
            ],400);
        }
    }


}