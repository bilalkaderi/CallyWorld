<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productrate extends Model
{
    use HasFactory;
    public $table='productsrate';
    protected $fillable=['productId','clientId','rate'];
}
