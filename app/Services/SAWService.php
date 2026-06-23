<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class SAWService {
    
    protected array $criteriaTypes = [
        'margin_percent' => 'benefit',
        'sold_count' => 'benefit',
        'rating' => 'benefit',
        'search_volume' => 'benefit',
        'competitor_count' => 'cost',
        'price_volatility' => 'cost',
        'shipping_complexity' => 'cost',
    ];

    public function calculate(Collection $products, ?array $weights = null): array {
        $weights = $weights ?? config('spk.default_weights');
        
        if ($products->isEmpty()) return [];

        $matrix = $this->buildMatrix($products);
        $normalized = $this->normalize($matrix);
        
        $results = [];
        foreach ($normalized as $productId => $values) {
            $score = 0;
            foreach ($values as $criteria => $value) {
                $score += $value * ($weights[$criteria] ?? 0);
            }
            $results[] = [
                'product_id' => $productId,
                'score' => round($score, 4),
                'details' => $matrix[$productId],
            ];
        }

        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);
        
        foreach ($results as $i => &$r) $r['rank'] = $i + 1;
        
        return $results;
    }

    protected function buildMatrix(Collection $products): array {
        $matrix = [];
        
        foreach ($products as $product) {
            $mp = $product->marketplaceData;
            $totalSold = $mp->sum('sold_count');
            $avgRating = $mp->avg('rating') ?? 0;
            $totalSearch = $mp->sum('search_volume_estimate');
            $competitorCount = $mp->count();
            $volatility = $this->calculateVolatility($product);
            $complexity = min(1, ($product->weight_kg ?? 0) / 10);

            $matrix[$product->id] = [
                'margin_percent' => max(0, $product->margin_percent),
                'sold_count' => $totalSold,
                'rating' => $avgRating,
                'search_volume' => $totalSearch,
                'competitor_count' => $competitorCount,
                'price_volatility' => $volatility,
                'shipping_complexity' => $complexity,
            ];
        }

        return $matrix;
    }

    protected function normalize(array $matrix): array {
        $normalized = [];
        
        $criteriaValues = [];
        foreach ($matrix as $id => $values) {
            foreach ($values as $c => $v) {
                $criteriaValues[$c][] = $v;
            }
        }

        $max = [];
        $min = [];
        foreach ($criteriaValues as $c => $values) {
            $max[$c] = max($values);
            $min[$c] = min($values) ?: 0.0001;
        }

        foreach ($matrix as $id => $values) {
            foreach ($values as $c => $v) {
                $type = $this->criteriaTypes[$c] ?? 'benefit';
                if ($type === 'benefit') {
                    $normalized[$id][$c] = $max[$c] > 0 ? $v / $max[$c] : 0;
                } else {
                    $normalized[$id][$c] = $v > 0 ? $min[$c] / $v : 0;
                }
            }
        }

        return $normalized;
    }

    protected function calculateVolatility(Product $product): float {
        $histories = $product->priceHistories()
            ->where('source', 'china')
            ->orderBy('recorded_at')
            ->pluck('price');

        if ($histories->count() < 2) return 0;

        $prices = $histories->toArray();
        $mean = array_sum($prices) / count($prices);
        $variance = array_sum(array_map(fn($p) => pow($p - $mean, 2), $prices)) / count($prices);
        
        return $mean > 0 ? sqrt($variance) / $mean : 0;
    }
}