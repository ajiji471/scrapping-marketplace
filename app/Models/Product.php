<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model {
    protected $fillable = [
        'name', 'category', 'subcategory',
        'price_china_cny', 'price_indonesia_idr',
        'shipping_cost_idr', 'tax_estimate_idr',
        'weight_kg', 'dimensions', 'source_url_china'
    ];

    protected $casts = [
        'dimensions' => 'array',
        'price_china_cny' => 'decimal:2',
        'price_indonesia_idr' => 'decimal:2',
    ];

    protected $appends = ['total_cost_from_china', 'margin_percent', 'margin_absolute'];

    public function marketplaceData(): HasMany {
        return $this->hasMany(MarketplaceData::class);
    }

    public function priceHistories(): HasMany {
        return $this->hasMany(PriceHistory::class);
    }

    public function getTotalCostFromChinaAttribute(): float {
        $rate = config('spk.exchange_rate_cny_idr', 2200);
        return ($this->price_china_cny * $rate) + $this->shipping_cost_idr + $this->tax_estimate_idr;
    }

    public function getMarginPercentAttribute(): float {
        $cost = $this->total_cost_from_china;
        if ($cost <= 0) return 0;
        return (($this->price_indonesia_idr - $cost) / $cost) * 100;
    }

    public function getMarginAbsoluteAttribute(): float {
        return $this->price_indonesia_idr - $this->total_cost_from_china;
    }
}