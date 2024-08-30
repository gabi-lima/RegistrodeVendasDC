<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = new Product();
        $product->user_id = $request->user_id;
        $product->name = $request->name;
        $product->unit_price = $request->unit_price;
        
        $product->save();

        return redirect()->route('products.create')->with('msg', 'Produto cadastrado com sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $user = User::where('id', $product->id)->first();
        $products = $user->products()->get();
        return view('admin.product.show', compact('products'));
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.product.edit', compact('product'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product->name = $request->name;
        $product->unit_price = $request->unit_price;

        $product->save();
    return redirect()->route('products.show', ['product' => auth()->user()->id])->with('success', 'Produto atualizado com sucesso.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
    public function autocomplete(Request $request)
    {
        $term = $request->input('term');

        $products = Product::where('name', 'like', '%' . $term . '%')->get();

        $formattedProducts = $products->map(function ($product) {
            return [
                'label' => $product->name, 
                'value' => $product->id, 
            ];
        });

        return response()->json($formattedProducts);
    }
}
