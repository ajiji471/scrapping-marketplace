<?php

namespace App\Services;

use Illuminate\Support\Collection;

class WPService {
    
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
        
        $saw = new SAWService();
        $matrix = (new \ReflectionMethod($saw, 'buildMatrix'))->invoke($saw, $products);

        if (empty($matrix)) return [];

        $results = [];
        foreach ($matrix as $productId => $values) {
            $score = 1;
            foreach ($values as $criteria => $value) {
                $w = $weights[$criteria] ?? 0;
                $type = $this->criteriaTypes[$criteria];
                
                // Untuk cost, gunakan inverse
                $safeValue = max($value, 0.0001);
                if ($type === 'cost') {
                    $safeValue = 1 / $safeValue;
                }
                
                $score *= pow($safeValue, abs($w));
            }
            
            $results[] = [
                'product_id' => $productId,
                'score' => round($score, 6),
                'details' => $values,
            ];
        }

        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);
        
        foreach ($results as $i => &$r) $r['rank'] = $i + 1;
        
        return $results;
    }
}