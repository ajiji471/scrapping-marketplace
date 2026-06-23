<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\SAWService;
use App\Services\WPService;
use Illuminate\Http\Request;

class SpkController extends Controller
{
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'method' => 'required|in:saw,wp,both',
            'category' => 'nullable|string',
            'weights' => 'nullable|array',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Product::with('marketplaceData');
        
        if (!empty($validated['category'])) {
            $query->where('category', $validated['category']);
        }

        $products = $query->get();
        $limit = $validated['limit'] ?? 20;
        $response = [];

        if (in_array($validated['method'], ['saw', 'both'])) {
            $sawService = new SAWService();
            $sawResults = $sawService->calculate($products, $validated['weights'] ?? null);
            $response['saw'] = array_slice($sawResults, 0, $limit);
        }

        if (in_array($validated['method'], ['wp', 'both'])) {
            $wpService = new WPService();
            $wpResults = $wpService->calculate($products, $validated['weights'] ?? null);
            $response['wp'] = array_slice($wpResults, 0, $limit);
        }

        return response()->json([
            'meta' => [
                'total_products' => $products->count(),
                'method' => $validated['method'],
                'weights_used' => $validated['weights'] ?? 'default',
            ],
            'data' => $response,
        ]);
    }

    public function criteria()
    {
        return response()->json([
            'criteria' => [
                ['key' => 'margin_percent', 'label' => 'Margin Keuntungan (%)', 'type' => 'benefit', 'default_weight' => 0.25],
                ['key' => 'sold_count', 'label' => 'Jumlah Terjual', 'type' => 'benefit', 'default_weight' => 0.20],
                ['key' => 'rating', 'label' => 'Rating Rata-rata', 'type' => 'benefit', 'default_weight' => 0.15],
                ['key' => 'search_volume', 'label' => 'Volume Pencarian', 'type' => 'benefit', 'default_weight' => 0.15],
                ['key' => 'competitor_count', 'label' => 'Jumlah Pesaing', 'type' => 'cost', 'default_weight' => 0.10],
                ['key' => 'price_volatility', 'label' => 'Volatilitas Harga', 'type' => 'cost', 'default_weight' => 0.10],
                ['key' => 'shipping_complexity', 'label' => 'Kompleksitas Pengiriman', 'type' => 'cost', 'default_weight' => 0.05],
            ],
        ]);
    }
}