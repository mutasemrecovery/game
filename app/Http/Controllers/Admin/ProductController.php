<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data= Product::paginate(PAGINATION_COUNT);

        return view('admin.products.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Create a new product
            $product = new Product();
            $product->number = $request->input('number');
            $product->name_en = $request->input('name_en');
            $product->name_ar = $request->input('name_ar');
            $product->description_en = $request->input('description_en');
            $product->description_ar = $request->input('description_ar');
            $product->selling_price = $request->input('selling_price');
            $product->status = $request->input('status');
            
            // Save the product first to get an ID
            $product->save();
            
            // Now that the product has an ID, associate images
            if ($request->hasFile('photo')) {
                $photos = $request->file('photo');
                foreach ($photos as $photo) {
                    $photoPath = uploadImage('assets/admin/uploads', $photo); // Use the uploadImage function
                    if ($photoPath) {
                        // Create a record in the product_images table for each image
                        $productImage = new ProductImage();
                        $productImage->photo = $photoPath;
                        $productImage->product_id = $product->id; // Explicitly set the product_id
                        $productImage->save();
                    }
                }
            }
            
            return redirect()->route('products.index')->with(['success' => 'Product created']);
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        return view('admin.products.edit', ['data' => $data]);
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
                return redirect()->route('products.index')->with(['success' => 'Product updated']);
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

            $item_row = Product::select("id")->where('id','=',$id)->first();

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
