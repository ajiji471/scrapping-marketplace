<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['marketplaceData', 'priceHistories']);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by min margin - gunakan collection filter, bukan raw query
        // Karena margin_percent adalah accessor, tidak bisa query langsung
        $perPage = min((int) $request->input('per_page', 10), 100);
        $products = $query->paginate($perPage);

        // Transform data untuk response
        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category,
                'subcategory' => $product->subcategory,
                'price_china_cny' => (float) $product->price_china_cny,
                'price_indonesia_idr' => (float) $product->price_indonesia_idr,
                'shipping_cost_idr' => (float) $product->shipping_cost_idr,
                'tax_estimate_idr' => (float) $product->tax_estimate_idr,
                'total_cost_from_china' => (float) $product->total_cost_from_china,
                'margin_percent' => round((float) $product->margin_percent, 2),
                'margin_absolute' => (float) $product->margin_absolute,
                'weight_kg' => (float) $product->weight_kg,
                'marketplace_summary' => [
                    'total_sold' => $product->marketplaceData->sum('sold_count'),
                    'avg_rating' => round($product->marketplaceData->avg('rating') ?? 0, 2),
                    'marketplaces' => $product->marketplaceData->pluck('marketplace')->unique()->values(),
                ],
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });

        // Filter min_margin di collection level (setelah transform)
        if ($request->filled('min_margin')) {
            $minMargin = (float) $request->min_margin;
            $filtered = $products->getCollection()->filter(function ($product) use ($minMargin) {
                return $product['margin_percent'] >= $minMargin;
            });
            $products->setCollection($filtered->values());
        }

        return response()->json($products);
    }

    public function show(Product $product)
    {
        $product->load(['marketplaceData', 'priceHistories']);

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'category' => $product->category,
            'subcategory' => $product->subcategory,
            'price_china_cny' => (float) $product->price_china_cny,
            'price_indonesia_idr' => (float) $product->price_indonesia_idr,
            'shipping_cost_idr' => (float) $product->shipping_cost_idr,
            'tax_estimate_idr' => (float) $product->tax_estimate_idr,
            'total_cost_from_china' => (float) $product->total_cost_from_china,
            'margin_percent' => round((float) $product->margin_percent, 2),
            'margin_absolute' => (float) $product->margin_absolute,
            'weight_kg' => (float) $product->weight_kg,
            'source_url_china' => $product->source_url_china,
            'marketplace_data' => $product->marketplaceData,
            'price_history' => $product->priceHistories,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ]);
    }

    public function store(Request $request)
    {
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

        // Catat price history
        $product->priceHistories()->create([
            'source' => 'china',
            'price' => $product->price_china_cny,
            'currency' => 'CNY',
            'recorded_at' => now(),
        ]);
        $product->priceHistories()->create([
            'source' => 'indonesia',
            'price' => $product->price_indonesia_idr,
            'currency' => 'IDR',
            'recorded_at' => now(),
        ]);

        return response()->json($product->fresh()->load(['marketplaceData', 'priceHistories']), 201);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:100',
            'subcategory' => 'nullable|string|max:100',
            'price_china_cny' => 'sometimes|numeric|min:0',
            'price_indonesia_idr' => 'sometimes|numeric|min:0',
            'shipping_cost_idr' => 'nullable|numeric|min:0',
            'tax_estimate_idr' => 'nullable|numeric|min:0',
            'weight_kg' => 'nullable|numeric|min:0',
            'source_url_china' => 'nullable|url',
        ]);

        $product->update($validated);

        return response()->json($product->fresh()->load(['marketplaceData', 'priceHistories']));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }

    public function updatePrice(Request $request, Product $product)
    {
        $validated = $request->validate([
            'price_china_cny' => 'nullable|numeric|min:0',
            'price_indonesia_idr' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        if (isset($validated['price_china_cny'])) {
            $product->update(['price_china_cny' => $validated['price_china_cny']]);

            $product->priceHistories()->create([
                'source' => 'china',
                'price' => $validated['price_china_cny'],
                'currency' => 'CNY',
                'note' => $validated['note'] ?? null,
                'recorded_at' => now(),
            ]);
        }

        if (isset($validated['price_indonesia_idr'])) {
            $product->update(['price_indonesia_idr' => $validated['price_indonesia_idr']]);

            $product->priceHistories()->create([
                'source' => 'indonesia',
                'price' => $validated['price_indonesia_idr'],
                'currency' => 'IDR',
                'note' => $validated['note'] ?? null,
                'recorded_at' => now(),
            ]);
        }

        return response()->json($product->fresh()->load(['marketplaceData', 'priceHistories']));
    }
}