<?php

namespace App\Http\Controllers;


use App\Helpers\SignatureHelper;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Mail\KirimEmail;
use Illuminate\Support\Facades\Mail;

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

    public function updateStatus(Request $request) //Notification
    {

        $requestBody = $request->getContent();
        $targetPath = '/api/notify';


        $signatureHelper = new SignatureHelper();
        $result = $signatureHelper->validationSignature($requestBody, $targetPath);

        $signature = $result['signature'];
        $signatureRequest = $result['signatureRequest'];
        $finalSignature = 'HMACSHA256=' . $signature;

        if ($finalSignature == $signatureRequest) {

            $response = json_decode($requestBody, true);
            $invoice = $response['order']['invoice_number'];
            $statusTrx = $response['transaction']['status'];
            $cariin = json_decode(DB::table('customers')->where('invoices', $invoice)->get('invoices'), true);
            $id_cust = json_decode(DB::table('customers')->where('invoices', $invoice)->get('id'), true);
            $nama_cust = json_decode(DB::table('customers')->where('invoices', $invoice)->get('nama'), true);
            $kuantiti = json_decode(DB::table('customers')->where('invoices', $invoice)->get('kuantiti'), true);
            $emailcust = json_decode(DB::table('customers')->where('invoices', $invoice)->get('email'), true);


            $gambar1 = "../images/giphy.gif";


            $data = [
                'title' => 'Selamat datang!',
                'url' => 'http://127.0.0.1:8000/redirect/' . $cariin[0]['invoices'],
                'invoice' => $cariin[0]['invoices'],
                'nama' => $nama_cust[0]['nama'],
                'gambar' => $gambar1,
                'status' => $statusTrx
            ];


            if (isset($cariin[0]['invoices']) == $invoice && $statusTrx != "SUCCESS") {

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
                                'qr_string' => md5($x . $id_cust[0]['id'] . now(+7))
                            ],

                        );
                }

                Mail::to($emailcust)->send(new KirimEmail($data));

                return response()->json([
                    "pesan" => "Pembelian berhasil",
                    "email" => "Email terkirim ke " . $emailcust[0]['email'],
                    "data" => $cariin,
                    "status" => $response['transaction']['status']
                ]);

            } else {

                return response()->json([
                    "pesan" => "gagal update datanya ora ada",
                    "invoices" => $invoice
                ]);
            }




        } else {

            return response()->json([
                "pesan" => "invalid signature",
                "dataHeadr" => $signatureRequest,
                "dataBody" => $requestBody,
                "siganture_bikin" => $finalSignature
            ], 400);
        }
    }




    public function callback($request)
    {

        $sukses = "../images/75_smile.gif";
        $pending = "../images/waiting.gif";
        $gagal = "../images/fail.gif";
        $gambar1 = "../images/giphy.gif";
        $cekinvoice = json_decode(DB::table('customers')->where('invoices', $request)->get(), true);
        $dataQR = DB::table('qr_codes')
            ->join('customers', 'qr_codes.customer_id', '=', 'customers.id')
            ->where('customers.invoices', $request)->get();

        $banyak = DB::table('qr_codes')
            ->join('customers', 'qr_codes.customer_id', '=', 'customers.id')
            ->where('customers.invoices', $request)->count();


        if (isset($cekinvoice[0]['invoices']) == $request && $cekinvoice[0]['status_transaksi'] == "SUCCESS") {

            return view('print_ticket', [
                "invoice" => $request,
                "id" => $cekinvoice[0]['id'],
                "gambar" => $sukses,
                "title" => $cekinvoice[0]['status_transaksi'],
                "qr_strings" => $dataQR,
                "kuantiti" => $banyak,
                "body" => "Untuk kenyamanan Anda kami telah mengirimkan salinan e-voucher ke email yang telah teregistrasi. 
                Apabila dalam 1x24 jam Anda belum menerima e-voucher,silahkan menghubungi customer service melalui email test@test.com."
            ]);

        } elseif (isset($cekinvoice[0]['invoices']) == $request && $cekinvoice[0]['status_transaksi'] == "PENDING") {
            return view('redirect', [
                "invoice" => $request,
                "gambar" => $pending,
                "title" => $cekinvoice[0]['status_transaksi'],
                "body" => "Segera selesaikan Pembayaran anda untuk mendapatkan e-ticket"
            ]);
        } elseif (isset($cekinvoice[0]['invoices']) == $request && $cekinvoice[0]['status_transaksi'] == "FAILED") {
            return view('redirect', [
                "invoice" => $request,
                "gambar" => $gagal,
                "title" => $cekinvoice[0]['status_transaksi'],
                "body" => "Mohon coba kembali"
            ]);
        } else {
            return view('notfound', [
                "title" => "Data Tidak ditemukan",
                "body" => "Coba kontak customer service",
                "gambar" => $gambar1
            ]);

        }

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