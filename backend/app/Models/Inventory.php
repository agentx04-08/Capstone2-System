<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'name', 'unit', 'stock', 'reorder_level', 'lead_time_days', 'consumption_rate_per_day'
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class);
    }
}