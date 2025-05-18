<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductSelectionController extends Controller
{
    public function index()
    {
        $users = User::get(); // Assuming you have a role field
        return view('admin.products.selection', compact('users'));
    }

    public function getFilteredProducts(Request $request)
    {
        $selectedDate = Carbon::parse($request->date);
        $showAll = $request->show_all == 'true';
        
        // Define date range: one day before, selected day, one day after
        $startDate = $selectedDate->copy()->subDay()->startOfDay();
        $endDate = $selectedDate->copy()->addDay()->endOfDay();
        
        // Get products that don't have pending orders in the date range
        $unavailableProductIds = Order::where('order_status', 1) // Pending orders
            ->whereBetween('date', [$startDate, $endDate])
            ->pluck('id');
        
        $unavailableProductIdsArray = OrderProduct::whereIn('order_id', $unavailableProductIds)
            ->pluck('product_id')
            ->unique()
            ->toArray();
        
        // Get products query
        $query = Product::where('status', 1); // Active products
        
        // If not showing all, filter out unavailable products
        if (!$showAll) {
            $query = $query->whereNotIn('id', $unavailableProductIdsArray);
        }
        
        // Get current date for offers
        $currentDate = now();
        
        $products = $query->with(['productImages', 'offers' => function($query) use ($currentDate) {
                $query->where('start_at', '<=', $currentDate)
                      ->where('expired_at', '>=', $currentDate);
            }])
            ->get()
            ->map(function($product) use ($unavailableProductIdsArray) {
                $offer = $product->offers->first();
                $isAvailable = !in_array($product->id, $unavailableProductIdsArray);
                
                return [
                    'id' => $product->id,
                    'name_en' => $product->name_en,
                    'name_ar' => $product->name_ar,
                    'description_en' => $product->description_en,
                    'description_ar' => $product->description_ar,
                    'selling_price' => $product->selling_price,
                    'image' => $product->productImages->first() 
                        ? asset('assets/admin/uploads/' . $product->productImages->first()->photo)
                        : asset('assets/admin/uploads/default.jpg'),
                    'offer_price' => $offer ? $offer->price : null,
                    'available' => $isAvailable,
                ];
            });
        
        return response()->json([
            'products' => $products
        ]);
    }
}