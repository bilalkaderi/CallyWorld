<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $table='product';
    protected $fillable=['name','userId','price','photo','description','width','height','categoryId','soldno','stock','expecting_delivery_time','delivery_time_type','promotion','validated','our_stock','sales_after_promotion'];

    // Price after the promotion percentage, the amount a buyer actually pays.
    public function finalPrice(): float
    {
        $promo = (float) ($this->promotion ?? 0);
        return round($this->price - ($promo / 100) * $this->price, 2);
    }
}
