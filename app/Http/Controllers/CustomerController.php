<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;



class CustomerController extends Controller
{
    public function index() {
        $customer = Customer::all();

        return response()->json( [
            "message" => "Succesfully fetched",
            "datalist" => $customer
        ], Response::HTTP_OK);
    }  
    
    public function store(Request $request) {
        $validator = Validator::make($request -> all(), [
           "nama" => "required|string", 
           "telpon" => "required|string", 
           "email" => "required|string|unique:customers,email", 
           "total" => "required|numeric", 
        ]);


        if ($validator->fails()){
            return response()->json([
                "message" => "failed",
                "errors" => $validator->errors()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
        $validated = $validator->validated();

        try {
            $createdCustomer = Customer::create($validated);
        }
        catch(\Exception $e){
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





    
    public function update(Request $request, $id){

        $validator = Validator::make($request -> all(), [
            "nama" => "string", 
            "telpon" => "string", 
            "email" => "string", 
            "total" => "numeric", 
         ]);
 
 
         if ($validator->fails()){
             return response()->json([
                 "message" => "failed",
                 "errors" => $validator->errors()
             ], Response::HTTP_NOT_ACCEPTABLE);
         }
         $validated = $validator->validated();
 
         try {

            $customerUpdate = Customer::findOrFail($id);
            $customerUpdate->update($validated);
         }
         catch(\Exception $e){
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

    public function show($id){
        $customer = Customer::findOrFail($id);


        return response()->json([
            "pesan" => "berhasil ngambil coy",
            "data" => $customer
        ]);
    }

    public function destroy($id){

        $customer = Customer::findOrFail($id);
        $customer->delete();
        return response()->json([
            "pesan" => "berhasil ngapusin coy data {$id}",
           
        ]);
    }

    
}
