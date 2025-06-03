<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountScheme extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'value',
        'applies_to',
        'reference_id',
        'valid_from',
        'valid_to',
        'is_active',
        'conditions',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'value'       => 'float',
        'is_active'   => 'boolean',
        'valid_from'  => 'date',
        'valid_to'    => 'date',
        'conditions'  => 'array',   // JSON-based rules
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
    ];

    // 🧠 Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $today = now()->toDateString();
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', $today);
            })
            ->where(function ($q) {
                $today = now()->toDateString();
                $q->whereNull('valid_to')->orWhere('valid_to', '>=', $today);
            });
    }

    // 🔗 Optional Polymorphic/Custom Relations
    public function reference()
    {
        // For dynamic relationship with item, category, client, etc.
        return match ($this->applies_to) {
            'item'     => $this->belongsTo(InvoiceItem::class, 'reference_id'),
            'category' => $this->belongsTo(Category::class, 'reference_id'),
            'client'   => $this->belongsTo(Client::class, 'reference_id'),
            default    => null,
        };
    }

    // 👤 Created By / Updated By
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
