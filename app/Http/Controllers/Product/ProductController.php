<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models Import
use App\Models\Product;
use App\Models\Product_image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['products'] = Product::get();
        $data['title'] = 'List Products';

        return view('products/list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = 'Create New Product';

        return view('products/new', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product['product_code'] = rand();
        $product['name'] = $request->name;
        $product['description'] = $request->description;
        $product['category'] = $request->category;
        $product['subcategory'] = $request->subcategory;
        $product['item'] = $request->item;
        $product['display_at'] =  json_encode($request->display);
        $product['price'] = $request->price;
        $product['stock'] = $request->stock;
        $product['discount'] = (!empty($request->discount) ? $request->discount : 0);
        $product['is_promo'] = $request->promosi;
        $product['specification'] = $request->specification;
        $product['default_image_id'] = $request->thumb_id;
        $product['status'] = 1;

        $imageIds = explode(',', $request->image_ids);

        $productId = Product::create($product)->id;

        Product_image::whereIn('id', $imageIds)->update(['product_id' => $productId]);

        return redirect()->route('product.list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['product'] = Product::findOrFail($id);
        $data['thumb'] = Product_image::findOrFail($data['product']['default_image_id']);
        $data['images'] = Product_image::where('product_id', $id)->get();
        $data['title'] = ucwords($data['product']['name']);

        return view('products/detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
