<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryDeleteRequest;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductCollectionResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Util\Json;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = Category::with('parent')->with('childs');
        if($request->get('leaf')){
            $query=$query->leaf();
        }

        if($request->get('top')){
            $query=$query->top();
        }

        return new CategoryCollection($query->orderBy('id')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $new_data=[];
        $new_data=$request->only(['name','parent_id']);
        $base64_image = $request->input('image'); // your base64 encoded
        if($base64_image){
            @list($type, $file_data) = explode(';', $base64_image);
            @list(, $file_data) = explode(',', $file_data);
            $imgExt = str_replace('data:image/', '', $type);
            $filename = 'categories/'.uniqid().'.'.$imgExt;
            Storage::disk('public')->put($filename, base64_decode($file_data));
            $new_data['image']=$filename;
        }

        $category = new Category($new_data);

        $category->save();

        return response()->json($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category->load('parent')->load('childs')->load('childs.childs');
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {

        $new_data=[];
        $new_data=$request->only(['name','parent_id']);
        $base64_image = $request->input('image'); // your base64 encoded
        if($base64_image){
            @list($type, $file_data) = explode(';', $base64_image);
            @list(, $file_data) = explode(',', $file_data);
            $imgExt = str_replace('data:image/', '', $type);
            $filename = 'categories/'.uniqid().'.'.$imgExt;
            Storage::disk('public')->put($filename, base64_decode($file_data));
            $new_data['image']=$filename;
        }

        $category->update($new_data);

        $category->save();

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->childs->count()==0 && $category->products->count()==0){
            $category->delete();
        }else{
            return response()->json(array(
                'message'=>'Category Must be empty'
            ),422);
        }
    }

    public function products(Category $category)
    {
        return new ProductCollectionResource($category->products()->paginate());
    }
}
