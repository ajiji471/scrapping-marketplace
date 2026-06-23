// app/Http/Controllers/Api/ProductController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    
    public function index(Request $request) {
        $query = Product::with(['marketplaceData', 'priceHistories']);

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        if ($request->has('min_margin')) {
            $rate = config('spk.exchange_rate_cny_idr', 2200);
            $query->whereRaw(
                '((price_indonesia_idr - ((price_china_cny * ?) + shipping_cost_idr + tax_estimate_idr)) / 
                ((price_china_cny * ?) + shipping_cost_idr + tax_estimate_idr) * 100) >= ?',
                [$rate, $rate, $request->min_margin]
            );
        }

        return $query->paginate(50);
    }

    public function show(Product $product) {
        return $product->load(['marketplaceData', 'priceHistories']);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'subcategory' => 'nullable|string|max:100',
            'price_china_cny' => 'required|numeric|min:0',
            'price_indonesia_idr' => 'required|numeric|min:0',
            'shipping_cost_idr' => 'nullable|numeric|min:0',
            'tax_estimate_idr' => 'nullable|numeric|min:0',
            'weight_kg' => 'nullable|numeric|min:0',
            'source_url_china' => 'nullable|url',
        ]);

        $product = Product::create($validated);

        $product->priceHistories()->create([
            'source' => 'china', 'price' => $product->price_china_cny,
            'currency' => 'CNY', 'recorded_at' => now(),
        ]);
        $product->priceHistories()->create([
            'source' => 'indonesia', 'price' => $product->price_indonesia_idr,
            'currency' => 'IDR', 'recorded_at' => now(),
        ]);

        return response()->json($product, 201);
    }

    public function updatePrice(Request $request, Product $product) {
        $validated = $request->validate([
            'price_china_cny' => 'nullable|numeric|min:0',
            'price_indonesia_idr' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        if (isset($validated['price_china_cny'])) {
            $product->update(['price_china_cny' => $validated['price_china_cny']]);
            $product->priceHistories()->create([
                'source' => 'china', 'price' => $validated['price_china_cny'],
                'currency' => 'CNY', 'note' => $validated['note'] ?? null,
                'recorded_at' => now(),
            ]);
        }

        if (isset($validated['price_indonesia_idr'])) {
            $product->update(['price_indonesia_idr' => $validated['price_indonesia_idr']]);
            $product->priceHistories()->create([
                'source' => 'indonesia', 'price' => $validated['price_indonesia_idr'],
                'currency' => 'IDR', 'note' => $validated['note'] ?? null,
                'recorded_at' => now(),
            ]);
        }

        return $product->fresh();
    }

    public function destroy(Product $product) {
        $product->delete();
        return response()->noContent();
    }
}