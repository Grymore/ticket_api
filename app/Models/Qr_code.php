<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qr_code extends Model
{
    use HasFactory;
    protected $table = "qr_codes";

    protected $guarded = [
        "id",
        "created_at",
        "update_at",
        "customer_id",
        "attemp"
    ];


    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    
}
