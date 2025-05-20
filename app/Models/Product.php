<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $table='product';
    protected $fillable=['name','userId','price','photo','description','width','height','categoryId','soldno','stock','expecting_delivery_time','delivery_time_type','promotion','validated','our_stock','sales_after_promotion'];
}
