<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
