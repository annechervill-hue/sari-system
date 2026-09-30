<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['receipt_no', 'subtotal', 'discount', 'total'];
}