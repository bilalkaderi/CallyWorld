<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $table='orders';
    protected $fillable=['productId','clientId','userId','quantity','total_price','status','expecting_delivery_date','cartId','visible'];
}
