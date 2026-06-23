<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceData extends Model {
    protected $table = 'marketplace_data';

    protected $fillable = [
        'product_id', 'marketplace', 'current_price', 'original_price',
        'sold_count', 'rating', 'review_count', 'wishlist_count',
        'search_volume_estimate', 'scraped_at'
    ];

    protected $casts = [
        'scraped_at' => 'datetime',
    ];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}