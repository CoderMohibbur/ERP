<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
        'is_active',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
    ];

    // 🔗 Reverse Relation (if needed)
    public function discountSchemes()
    {
        return $this->hasMany(DiscountScheme::class);
    }
}
