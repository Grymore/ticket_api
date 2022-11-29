<?php

namespace App\Http\Controllers;


use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Customer;
use App\Models\Qr_code;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;



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

    public function updateStatus() //Notification

    {

        $notificationHeader = getallheaders();
        $notificationBody = file_get_contents('php://input');
        $notificationPath = '/api/notify';
        // $clientId = 'BRN-0218-1668854741147';
        $secretKey = 'SK-S4fMVZXAjnPsqIeHaJqc';

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
           
            $cariin = DB::table('customers')->where('invoices', $invoice)->get('invoices');
         
        

            if($cariin[0] == null){

                DB::table('customers')
                    ->where('invoices', $invoice)
                    ->update(['status_transaksi' => $statusTrx]);

                return response()->json([
                    "pesan" => "status berhasil diupdate",
                    "data" => $cariin,
                    "status" => $statusTrx
                ]);

            } elseif (isset($cariin[0]['invoices']) == $invoice && $statusTrx == "SUCCESS") {

                DB::table('customers')
                    ->where('invoices', $invoice)
                    ->update([
                        'status_transaksi' => $statusTrx,
                        'updated_at' => now(+7)
                    ]);

                for ($x = 1; $x <= $kuantiti[0]['kuantiti']; $x++) {
                    $waktu = now(+7);
                    DB::table('qr_codes')
                        ->insert(
                            [
                                'customer_id' => $id_cust[0]['id'],
                                'created_at' => $waktu,
                                'qr_string' => md5($id_cust[0]['id'].now(+7))
                            ]

                        );
                }

                return response()->json([
                    "pesan" => "status berhasil diupdate",
                    "data" => $cariin,
                    "status" => $response['transaction']['status']
                ]);

            } else {

                return response()->json([
                    "pesan" => "gagal update datanya ora ada",
                    "cariin" => $cariin[0],
                    "invoice" => $invoice,
                    "json obj" => $response['transaction']['status']
                ]); 
                
            }




        } else {

            return response()->json([
                "pesan" => "invalid signature",
                "dataHeadr" => $notificationHeader['Signature'],
                "dataBody" => $notificationBody,
                "digest" => $digest,
                "siganture" => $finalSignature
            ], 400);
        }
    }




        // $affected = DB::table('users')
        //     ->where('id', 1)
        //     ->update(['votes' => 1]);


        // return response()->json([
        //     "pesan" => "berhasil ngapusin coy data {$id}",

        // ]);
    }


    public function print($request)
    {

        $path = base_path("public/images/banner.webp");
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $image = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $dataQR = DB::table('qr_codes')
            ->join('customers', 'qr_codes.customer_id', '=', 'customers.id')
            ->where('customers.invoices', $request)->get();

        $banyak = DB::table('qr_codes')
            ->join('customers', 'qr_codes.customer_id', '=', 'customers.id')
            ->where('customers.invoices', $request)->count();

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadview("download", [
            "body" => $request,
            "qr_strings" => $dataQR,
            "kuantiti" => $banyak,
            "banner" => $image
        ])->setPaper('A4', 'portrait');


        // return view('print_ticket',([
        //     'qr_strings' => $dataQR
        // ]));


        // return $pdf->stream('invoice.pdf');

        // <div class="ticket-id"> <img src="data:image/png;base64, {!! base64_encode(QrCode::size(100)->generate($qrcode->qr_string)) !!} "></div>


        return $pdf->download('laporan-pdf');



    }

}