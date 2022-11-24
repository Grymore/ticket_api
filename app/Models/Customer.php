<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [
        "id",
        "created_at",
        "update_at"
    ];

    protected $table = "customers";

    public function qr_code(){
        return $this->hasOne('App\Models\Qr_code', 'customer_id', 'id');
    }


}
