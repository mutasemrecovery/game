<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Unit;
use App\Models\User;
use App\Notifications\NewOrderNotification;
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
            'order_time' => 'nullable|string',
            'note' => 'nullable|string|max:1000',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'delivery_id' => 'nullable|exists:deliveries,id',
            'total_prices' => 'required|numeric|min:0',
            'total_discounts' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products_data' => 'required|string',
        ]);

        // Combine date + time
        $time = $request->order_time ?: '00:00:00';
        $datetime = \Carbon\Carbon::parse($validatedData['date'] . ' ' . $time);

        try {
            DB::beginTransaction();

            $lastOrder = Order::latest('id')->first();
            $orderNumber = $lastOrder ? $lastOrder->number + 1 : 1;

            $delivery = Delivery::find($validatedData['delivery_id']);

            $user = User::firstOrCreate(
                ['phone' => $validatedData['phone']],
                ['name'  => $validatedData['name']]
            );

            $order = Order::create([
                'number' => $orderNumber,
                'order_status' => 1,
                'date' => $datetime,
                'user_id' => $user->id,
                'address' => $validatedData['address'],
                'note' => $validatedData['note'] ?? null,
                'delivery_id' => $validatedData['delivery_id'],
                'delivery_fee' => $delivery ? $delivery->price : 0,
                'payment_type' => 'cash',
                'payment_status' => 2,
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

            // Notify all admins of the new booking
            try {
                $order->load('user');
                foreach (Admin::all() as $admin) {
                    $admin->notify(new NewOrderNotification($order));
                }
            } catch (\Exception $e) {
                Log::error('New-order notification failed: ' . $e->getMessage());
            }

            return redirect()->route('user.order.success', $order->id)
            ->with('success', __('messages.Order created successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', __('messages.Error creating order: ') . $e->getMessage());
        }
    }

   public function getAllProducts()
    {
        $currentDate = now();

        $products = Product::where('status', 1)
            ->with(['offers' => function($query) use ($currentDate) {
                $query->where('start_at', '<=', $currentDate)
                    ->where('expired_at', '>=', $currentDate);
            }, 'productImages'])
            ->get()
            ->map(function($product) {
                $offer = $product->offers->first();

                return [
                    'id' => $product->id,
                    'name_en' => $product->name_en,
                    'name_ar' => $product->name_ar,
                    'selling_price' => $product->selling_price,
                    'image' => asset('assets/admin/uploads/' . $product->productImages->first()->photo),
                    'photos' => $product->productImages->map(fn($img) => asset('assets/admin/uploads/' . $img->photo))->values()->toArray(),
                    'offer_price' => $offer ? $offer->price : null,
                    'booked' => false,
                ];
            });

        return response()->json(['products' => $products]);
    }

    public function getAvailableProductsForUser(Request $request)
    {
        $selectedDate = Carbon::parse($request->date)->toDateString();

        // A product is "booked" if it's in any active order (not cancelled/returned)
        // whose date is on or before the requested date (characters still "out")
        $bookedProductIds = OrderProduct::whereHas('order', function ($q) use ($selectedDate) {
                $q->whereNotIn('order_status', [3, 7])
                  ->whereDate('date', '<=', $selectedDate);
            })
            ->pluck('product_id')
            ->unique()
            ->toArray();

        $currentDate = now();
        $products = Product::where('status', 1)
            ->with(['offers' => function($query) use ($currentDate) {
                $query->where('start_at', '<=', $currentDate)
                      ->where('expired_at', '>=', $currentDate);
            }, 'productImages'])
            ->get()
            ->map(function($product) use ($bookedProductIds) {
                $offer = $product->offers->first();

                return [
                    'id' => $product->id,
                    'name_en' => $product->name_en,
                    'name_ar' => $product->name_ar,
                    'selling_price' => $product->selling_price,
                    'image' => asset('assets/admin/uploads/' . $product->productImages->first()->photo),
                    'photos' => $product->productImages->map(fn($img) => asset('assets/admin/uploads/' . $img->photo))->values()->toArray(),
                    'offer_price' => $offer ? $offer->price : null,
                    'booked' => in_array($product->id, $bookedProductIds),
                ];
            });

        return response()->json(['products' => $products]);
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
