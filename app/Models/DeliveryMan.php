<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryMan extends Model
{
    use HasFactory;
    public $table='deliveryman';
    protected $fillable = ['username','password','phone'];
    // Never include the password hash when a courier is turned into JSON.
    protected $hidden = ['password'];
}
