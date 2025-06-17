<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Branch;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
    public function index()
    {
      $locale = App::getLocale();
      $deliveries = Delivery::get();
    //   {{$locale === 'ar' ? $subjectTeacher->name : $subjectTeacher->foreign_name}}
    //   {{ asset('assets/admin/uploads/' . $product->productImages->first()->photo) }}

        return view('layouts.user',compact('deliveries'));
    }

    public function showProductsToUser(Request $request)
    {
        $search = $request->get('search');
        
        $products = Product::with('productImages')
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name_ar', 'LIKE', "%{$search}%")
                    ->orWhere('name_en', 'LIKE', "%{$search}%")
                    ->orWhere('description_ar', 'LIKE', "%{$search}%")
                    ->orWhere('description_en', 'LIKE', "%{$search}%")
                    ->orWhere('number', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('layouts.product', compact('products', 'search'));
    }
   

}
