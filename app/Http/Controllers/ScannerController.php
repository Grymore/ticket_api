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
            ->get('qr_string'));
        
        $dataatem = DB::table('qr_codes')
            ->where('qr_string', $request)
            ->get();

        if ($qr == $data && $dataatem == NULL ) {

          
            DB::table('qr_codes')
                ->where('qr_string', $qr)
                ->update(['createAt' => now(+7)]);

            return response()->json([
                "status" => 200,
                "data" => "bisa bos" 
            ]);

        }
        else if($qr == $data  && $dataatem != NULL){
            return response()->json([
                "status" => 300,
                "data" => "udah pernah bos" 
            ]);
        }
   
        else {
            return response()->json([
                "status" => 400,
                "data" => $data[0]['qr_string'],
                "data1" => $request->qr_code,
                "data2" => $dataatem
            ]);
        }
    }

}