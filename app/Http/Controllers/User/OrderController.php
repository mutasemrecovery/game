<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'name' => 'required',
            'phone' => 'required',
            'delivery_id' => 'nullable|exists:deliveries,id',
            'payment_type' => 'required|string',
            'total_prices' => 'required|numeric|min:0',
            'total_discounts' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products_data' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Generate order number
            $lastOrder = Order::latest('id')->first();
            $orderNumber = $lastOrder ? $lastOrder->number + 1 : 1;

            $delivery = Delivery::find($validatedData['delivery_id']);

            $user = User::create([
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
            ]);
            // Create order
            $order = Order::create([
                'number' => $orderNumber,
                'order_status' => 1, // Pending
                'date' => $validatedData['date'],
                'user_id' => $user->id,
                'delivery_id' => $validatedData['delivery_id'],
                'delivery_fee' => $delivery ? $delivery->price : 0, 
                'payment_type' => $validatedData['payment_type'],
                'payment_status' => $validatedData['payment_status'] ?? 2,
                'total_prices' => $validatedData['total_prices'],
                'total_discounts' => $validatedData['total_discounts'],
            ]);

            // Create order products
            $productsData = json_decode($request->products_data, true);
            
            foreach ($productsData as $productData) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                    'total_price' => $productData['total_price'],
                    'discount_percentage' => $productData['discount_percentage'],
                    'discount_value' => $productData['discount_value'],
                ]);
            }

            DB::commit();

            return redirect()->route('user.order.success', $order->id)
            ->with('success', __('messages.Order created successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', __('messages.Error creating order: ') . $e->getMessage());
        }
    }

    public function getAvailableProductsForUser(Request $request)
    {
        $selectedDate = Carbon::parse($request->date);
        
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

        // Get available products with current offers
        $currentDate = now();
        $products = Product::where('status', 1) // Active products
            ->whereNotIn('id', $unavailableProductIdsArray)
            ->with(['offers' => function($query) use ($currentDate) {
                $query->where('start_at', '<=', $currentDate)
                      ->where('expired_at', '>=', $currentDate);
            }])
            ->get()
            ->map(function($product) {
                $offer = $product->offers->first();
                
                return [
                    'id' => $product->id,
                    'name_en' => $product->name_en,
                    'name_ar' => $product->name_ar,
                    'selling_price' => $product->selling_price,
                    'image' =>  asset('assets/admin/uploads/' . $product->productImages->first()->photo), 
                    'offer_price' => $offer ? $offer->price : null,
                ];
            });

        return response()->json([
            'products' => $products
        ]);
    }

    public function orderSuccess($orderId)
    {
        // Get the specific order
        $order = Order::with(['user', 'delivery', 'orderProducts.product'])
                      ->findOrFail($orderId);
        
        // Get all orders for this user based on phone number
        $userOrders = Order::with(['orderProducts', 'delivery'])
                           ->whereHas('user', function($query) use ($order) {
                               $query->where('phone', $order->user->phone);
                           })
                           ->where('id', '!=', $orderId) // Exclude current order
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        
        return view('layouts.order-success', compact('order', 'userOrders'));
    }
    
}
