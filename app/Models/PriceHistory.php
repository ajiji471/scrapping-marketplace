<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model {
    protected $table = 'price_histories';

    protected $fillable = [
        'product_id', 'source', 'price', 'currency', 'note', 'recorded_at'
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}