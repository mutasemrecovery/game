<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=[];


 

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class)->whereDate('expired_at', '>', now());
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
            ->withPivot('quantity', 'unit_price', 'total_price', 'discount_percentage', 'discount_value')
            ->withTimestamps();
    }
}
