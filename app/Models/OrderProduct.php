<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;
    
    protected $guarded=[];
    protected $casts = [
        'unit_price' => 'double',
        'total_price' => 'double',
        'discount_percentage' => 'double',
        'discount_value' => 'double',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
