<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class ScannerController extends Controller
{
    public function scanner()
    {

        return view('scanner');
    }


    public function validasi(Request $request)
    {
        $qr = $request->qr_code;
        $data = json_decode(DB::table('qr_codes')
            ->where('qr_string', $qr)
            ->get('qr_string'), true);
        
        $dataatem = json_decode(DB::table('qr_codes')
            ->where('qr_string', $qr)
            ->get('attemp'),true);

        if (isset($data[0]['qr_string']) == $qr && isset($dataatem[0]['attemp']) == NULL ) {

          
            DB::table('qr_codes')
                ->where('qr_string', $qr)
                ->update([
                    'attemp'=> + 1,
                    'createAt' => now(+7)
                ]);

            return response()->json([
                "status" => 200,
                "data" => "bisa bos",
                "data1" =>  $data,
                "data2" => $dataatem
            ]);

        }
        else if (isset($data[0]['qr_string']) == $qr && isset($dataatem[0]['attemp']) != NULL ){
            return response()->json([
                "status" => 300,
                "data" => "udah pernah bos",
                "data2" => $data[0],
                "data3" => $dataatem[0]

            ]);
        }
        else if(isset($data[0]['qr_string']) != $qr){

            return response()->json([
                "status" => 400,
                "data" => "salah bos qr nya"

            ]);
        }
   
        else {
            return response()->json([
                "status" => 500,
                "data" => $data[0]['qr_string'],
                "data1" => $request->qr_code,
                "data2" => $dataatem
            ]);
        }
    }

}