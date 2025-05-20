<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    public $table='cart';
    protected $fillable=['clientId','items','status','total_amount','expecting_delivery_date','payment_method','pref_address','delivery_details','delivery_hour','delivery_response'];
}
