<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductCollectionResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Product::with('category')->with('images');
        return new ProductCollectionResource($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $new_data=$request->only(['name','description','category_id','weight','usd_price']);
        $product = new Product($new_data);
        $product->save();

        foreach ($request->post('images') as $base64_image){
            if($base64_image){
                @list($type, $file_data) = explode(';', $base64_image);
                @list(, $file_data) = explode(',', $file_data);
                $imgExt = str_replace('data:image/', '', $type);
                $filename = 'products/'.uniqid().'.'.$imgExt;

                Storage::disk('public')->put($filename, base64_decode($file_data));
                $product->images()->create(['path'=>$filename]);
            }
        }

        return response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load('category')->load('images');
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $new_data=$request->only(['name','description','category_id','weight','usd_price']);
        $product->update($new_data);
        $product->save();

        foreach ($request->post('images') as $index=>$base64_image){
            if($base64_image){
                @list($type, $file_data) = explode(';', $base64_image);
                @list(, $file_data) = explode(',', $file_data);
                $imgExt = str_replace('data:image/', '', $type);
                $filename = 'products/'.uniqid().'.'.$imgExt;

                Storage::disk('public')->put($filename, base64_decode($file_data));

                //delete the previous
                if($product->images->count()>$index){
                    Storage::disk('public')->delete($product->images[$index]->path);
                    $product->images[$index]->delete();
                }

                $product->images()->create(['path'=>$filename]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }
}
