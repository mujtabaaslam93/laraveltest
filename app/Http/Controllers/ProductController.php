<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('index', compact('products'));

    }

    public function store(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        $this->saveToJson();

        return response()->json($product);
    }

    public function edit(Request $request)
    {
        $product = Product::find($request->id);
        return response()->json($product);
    }

    public function update(Request $request)
    {
        $product = Product::find($request->id);
        $product->update($request->all());

        $this->saveToJson();

        return response()->json($product);
    }

    private function saveToJson()
    {
        $products = Product::all();
        file_put_contents(storage_path('products.json'), $products->toJson());
    }
}
