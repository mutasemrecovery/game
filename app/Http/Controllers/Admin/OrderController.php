<?php

namespace App\Http\Controllers\Admin;

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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data= Order::paginate(PAGINATION_COUNT);

        return view('admin.orders.index',compact('data'));
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
            'user_id' => 'required|exists:users,id',
            'delivery_id' => 'nullable|exists:deliveries,id',
            'payment_type' => 'required|string',
            'payment_status' => 'required|in:1,2',
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

            // Create order
            $order = Order::create([
                'number' => $orderNumber,
                'order_status' => 1, // Pending
                'date' => $validatedData['date'],
                'user_id' => $validatedData['user_id'],
                'delivery_id' => $validatedData['delivery_id'],
                'delivery_fee' => $delivery ? $delivery->price : 0, 
                'payment_type' => $validatedData['payment_type'],
                'payment_status' => $validatedData['payment_status'],
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


    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Product::findOrFail($id); // Retrieve the category by ID
        return view('admin.orders.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $product->number = $request->input('number');
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->selling_price = $request->input('selling_price');
            $product->status = $request->input('status');
            $product->is_featured = $request->input('is_featured');

            if ($request->hasFile('photo')) {
                $photos = $request->file('photo');
                foreach ($photos as $photo) {
                    $photoPath = uploadImage('assets/admin/uploads', $photo); // Use the uploadImage function
                    if ($photoPath) {
                        // Create a record in the product_images table for each image using the relationship
                        $productImage = new ProductImage();
                        $productImage->photo = $photoPath;

                        $product->productImages()->save($productImage); // Associate the image with the product
                    }
                }
            }

            if ($product->save()) {
                return redirect()->route('orders.index')->with(['success' => 'Product updated']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong while updating the product']);
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
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
