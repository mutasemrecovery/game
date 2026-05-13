<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class OrderController extends Controller
{
    public function getPrice(Request $request)
    {
        $delivery = Delivery::findOrFail($request->id);
        return response()->json(['price' => $delivery->price]);
    }

    /** Page 1 — booked but not yet given to the client (status 1 or 2) */
    public function pendingDelivery(Request $request)
    {
        $query = Order::with(['user', 'delivery', 'orderProducts.product'])
            ->whereIn('order_status', [1, 2])
            ->orderBy('date', 'asc');

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }
        if ($request->filled('user_name')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->user_name . '%'));
        }

        $data = $query->paginate(PAGINATION_COUNT);
        $today = Carbon::today();

        return view('admin.orders.pending_delivery', compact('data', 'today'));
    }

    /** Page 2 — executed (given out) but characters not yet returned (status 6) */
    public function outNotReturned(Request $request)
    {
        $query = Order::with(['user', 'delivery', 'orderProducts.product'])
            ->where('order_status', 6)
            ->orderBy('date', 'asc');

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }
        if ($request->filled('user_name')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->user_name . '%'));
        }

        $data = $query->paginate(PAGINATION_COUNT);
        $today = Carbon::today();

        return view('admin.orders.out_not_returned', compact('data', 'today'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 

    public function index(Request $request)
    {
        // If new filter submitted, save to session
        if ($request->isMethod('get') && $request->anyFilled(['from_date', 'to_date', 'number', 'user_name', 'delivery_place'])) {
            session(['orders_filters' => $request->only(['from_date', 'to_date', 'number', 'user_name', 'delivery_place'])]);
        }

        // If reset button clicked, clear session
        if ($request->has('reset')) {
            session()->forget('orders_filters');
        }

        // Merge session filters with current request
        $filters = session('orders_filters', []);
        $request->mergeIfMissing($filters);

        $query = Order::query()->latest();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereDate('date', '>=', $request->from_date)
                ->whereDate('date', '<=', $request->to_date);
        }

        if ($request->filled('number')) {
            $query->where('number', 'like', '%' . $request->number . '%');
        }

        if ($request->filled('user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }

        if ($request->filled('delivery_place')) {
            $query->whereHas('delivery', function ($q) use ($request) {
                $q->where('place', 'like', '%' . $request->delivery_place . '%');
            });
        }

        $deliveries = Delivery::all();
        $data = $query->paginate(PAGINATION_COUNT);

        // Products that are in a status=6 (executed/unreturned) order AND also in
        // a future/current pending order — these are the real conflicts.
        $executedProductIds = OrderProduct::whereHas('order', function ($q) {
                $q->where('order_status', 6);
            })
            ->pluck('product_id')
            ->unique()
            ->toArray();

        $conflictedProductIds = [];
        if (!empty($executedProductIds)) {
            $conflictedProductIds = OrderProduct::whereIn('product_id', $executedProductIds)
                ->whereHas('order', function ($q) {
                    $q->whereIn('order_status', [1, 2])
                      ->whereDate('date', '>=', now()->toDateString());
                })
                ->pluck('product_id')
                ->unique()
                ->toArray();
        }

        return view('admin.orders.index', compact('data', 'deliveries', 'filters', 'conflictedProductIds'));
    }



    public function create()
    {
        $users = User::all();
        $deliveries = Delivery::all();
        
        return view('admin.orders.create', compact('users', 'deliveries'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string|max:1000',
            'address' => 'required',
            'user_id' => 'required|exists:users,id',
            'delivery_id' => 'nullable|exists:deliveries,id',
            'payment_type' => 'required|string',
            'payment_status' => 'required|in:1,2',
            'total_prices' => 'required|numeric|min:0',
            'total_discounts' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products_data' => 'required|string',
        ]);

        // Conflict check before saving
        $productsData = json_decode($request->products_data, true);
        $conflictError = $this->checkProductConflicts($productsData, $validatedData['date'], null);
        if ($conflictError) {
            return back()->withInput()->with('error', $conflictError);
        }

        try {
            DB::beginTransaction();

            $lastOrder = Order::latest('id')->first();
            $orderNumber = $lastOrder ? $lastOrder->number + 1 : 1;

            $delivery = Delivery::find($validatedData['delivery_id']);

            $order = Order::create([
                'number' => $orderNumber,
                'order_status' => 1,
                'date' => $validatedData['date'],
                'user_id' => $validatedData['user_id'],
                'address' => $validatedData['address'],
                'note' => $validatedData['note'] ?? null,
                'delivery_id' => $validatedData['delivery_id'],
                'delivery_fee' => $delivery ? $delivery->price : 0,
                'payment_type' => $validatedData['payment_type'],
                'payment_status' => $validatedData['payment_status'],
                'total_prices' => $validatedData['total_prices'],
                'total_discounts' => $validatedData['total_discounts'],
            ]);

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

            // Notify all admins of the new order
            try {
                $order->load('user');
                foreach (Admin::all() as $admin) {
                    $admin->notify(new NewOrderNotification($order));
                }
            } catch (\Exception $e) {
                Log::error('Admin new-order notification failed: ' . $e->getMessage());
            }

            return redirect()->route('orders.index')
                ->with('success', __('messages.Order created successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', __('messages.Error creating order: ') . $e->getMessage());
        }
    }

    public function getAvailableProducts(Request $request)
    {
        $selectedDate   = Carbon::parse($request->date)->toDateString();
        $currentOrderId = $request->order_id;

        // Rule 1: characters physically out (executed, not yet returned)
        $executedUnreturned = OrderProduct::whereHas('order', function ($q) use ($currentOrderId) {
                $q->where('order_status', 6);
                if ($currentOrderId) {
                    $q->where('id', '!=', $currentOrderId);
                }
            })
            ->pluck('product_id')
            ->unique()
            ->toArray();

        // Rule 2: characters already booked for this specific date
        $bookedSameDay = OrderProduct::whereHas('order', function ($q) use ($selectedDate, $currentOrderId) {
                $q->whereIn('order_status', [1, 2])
                  ->whereDate('date', $selectedDate);
                if ($currentOrderId) {
                    $q->where('id', '!=', $currentOrderId);
                }
            })
            ->pluck('product_id')
            ->unique()
            ->toArray();

        $blockedProductIds = array_unique(array_merge($executedUnreturned, $bookedSameDay));

        $currentDate = now();
        $products = Product::where('status', 1)
            ->whereNotIn('id', $blockedProductIds)
            ->with(['offers' => function ($q) use ($currentDate) {
                $q->where('start_at', '<=', $currentDate)
                  ->where('expired_at', '>=', $currentDate);
            }, 'productImages'])
            ->get()
            ->map(function ($product) {
                $offer = $product->offers->first();
                return [
                    'id'            => $product->id,
                    'name_en'       => $product->name_en,
                    'name_ar'       => $product->name_ar,
                    'selling_price' => $product->selling_price,
                    'image'         => asset('assets/admin/uploads/' . optional($product->productImages->first())->photo),
                    'offer_price'   => $offer ? $offer->price : null,
                ];
            });

        return response()->json(['products' => $products]);
    }

    public function quickUpdateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:3,6,7']);

        $order = Order::findOrFail($id);

        // Allow executing even if order date was yesterday; allow returning on same day or after
        $order->update(['order_status' => (int) $request->status]);

        return response()->json([
            'success' => true,
            'message' => __('messages.Order updated successfully'),
            'status'  => (int) $request->status,
        ]);
    }

    private function checkProductConflicts(array $productsData, string $date, ?int $excludeOrderId): ?string
    {
        if (empty($productsData)) {
            return null;
        }

        $productIds    = array_column($productsData, 'product_id');
        $requestedDate = Carbon::parse($date)->toDateString();

        // Conflict type 1: character physically out (executed, not returned)
        $conflict1 = OrderProduct::whereIn('product_id', $productIds)
            ->whereHas('order', function ($q) use ($excludeOrderId) {
                $q->where('order_status', 6);
                if ($excludeOrderId) {
                    $q->where('id', '!=', $excludeOrderId);
                }
            })
            ->with('product')
            ->get();

        // Conflict type 2: same date already booked (pending/processing)
        $conflict2 = OrderProduct::whereIn('product_id', $productIds)
            ->whereHas('order', function ($q) use ($requestedDate, $excludeOrderId) {
                $q->whereIn('order_status', [1, 2])
                  ->whereDate('date', $requestedDate);
                if ($excludeOrderId) {
                    $q->where('id', '!=', $excludeOrderId);
                }
            })
            ->with('product')
            ->get();

        $conflicting = $conflict1->merge($conflict2);

        if ($conflicting->isEmpty()) {
            return null;
        }

        $names = $conflicting->pluck('product.name_ar')->unique()->filter()->implode('، ');
        return 'يوجد تعارض في الشخصيات التالية: ' . $names;
    }


   public function show($id)
    {
        $order = Order::with(['user', 'delivery', 'orderProducts.product'])
                    ->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

   public function edit($id)
    {
        try {
            // Find the order with its related products
            $order = Order::with(['orderProducts.product', 'user', 'delivery'])->findOrFail($id);
            
            // Get users and deliveries
            $users = User::get(); // Adjust the role ID as needed
            $deliveries = Delivery::all();
            
            // Get only the products that are in this order
            $orderProducts = $order->orderProducts()->with('product')->get();
            
            return view('admin.orders.edit', compact('order', 'users', 'deliveries', 'orderProducts'));
        } catch (\Exception $e) {
            return redirect()->route('orders.index')
                ->with('error', __('messages.Error loading order: ') . $e->getMessage());
        }
    }

  public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string|max:1000',
            'address' => 'required',
            'user_id' => 'required|exists:users,id',
            'delivery_id' => 'nullable|exists:deliveries,id',
            'payment_type' => 'required|string',
            'payment_status' => 'required|in:1,2',
            'order_status' => 'required|in:1,2,3,4,5,6,7',
            'total_prices' => 'required|numeric|min:0',
            'total_discounts' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products_data' => 'required|string',
        ]);

        // Conflict check (skip for cancelled/returned orders)
        if (!in_array($validatedData['order_status'], [3, 7])) {
            $productsData = json_decode($request->products_data, true);
            $conflictError = $this->checkProductConflicts($productsData, $validatedData['date'], (int) $id);
            if ($conflictError) {
                return back()->withInput()->with('error', $conflictError);
            }
        }

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);

            $delivery = Delivery::find($validatedData['delivery_id']);
            $deliveryFee = $delivery ? $delivery->price : 0;

            $order->update([
                'date' => $validatedData['date'],
                'user_id' => $validatedData['user_id'],
                'address' => $validatedData['address'],
                'note' => $validatedData['note'] ?? null,
                'delivery_id' => $validatedData['delivery_id'],
                'delivery_fee' => $deliveryFee,
                'payment_type' => $validatedData['payment_type'],
                'payment_status' => $validatedData['payment_status'],
                'order_status' => $validatedData['order_status'],
                'total_prices' => $validatedData['total_prices'],
                'total_discounts' => $validatedData['total_discounts'],
            ]);
            
            // Delete all previous order products
            OrderProduct::where('order_id', $order->id)->delete();

            $productsData = $productsData ?? json_decode($request->products_data, true);

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
            
            return redirect()->route('orders.index')
                ->with('success', __('messages.Order updated successfully'));
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', __('messages.Error updating order: ') . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       try {

            $item_row = Product::select("name")->where('id','=',$id)->first();

            if (!empty($item_row)) {

        $flag = Product::where('id','=',$id)->delete();

        if ($flag) {
            return redirect()->back()
            ->with(['success' => '   Delete Succefully   ']);
            } else {
            return redirect()->back()
            ->with(['error' => '   Something Wrong']);
            }

            } else {
            return redirect()->back()
            ->with(['error' => '   cant reach fo this data   ']);
            }

       } catch (\Exception $ex) {

            return redirect()->back()
            ->with(['error' => ' Something Wrong   ' . $ex->getMessage()]);
            }
    }
}
