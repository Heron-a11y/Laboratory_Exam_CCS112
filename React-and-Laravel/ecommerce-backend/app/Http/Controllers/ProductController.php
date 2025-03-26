<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // List all products
    public function index()
    {
        return Product::all();
    }

    // Store a new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|string',
        ]);

        return Product::create($request->all());
    }

    // Show a single product
    public function show($id)
    {
        return Product::findOrFail($id);
    }

    // Update a product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return $product;
    }

    // Delete a product (if not in orders)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Check if product is in any orders before deleting
        if ($product->orders()->exists()) {
            return response()->json(['message' => 'Cannot delete, product is in an order.'], 400);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted.'], 200);
    }
}
