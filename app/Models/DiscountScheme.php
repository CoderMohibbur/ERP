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
        'discount_type_id',
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

    // 🧠 Scope: Only Active and Valid Discounts
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

    // 🔗 Applies to Dynamic Entity (invoice_item, category, client)
    public function reference()
    {
        return match ($this->applies_to) {
            'invoice_item' => $this->belongsTo(InvoiceItem::class, 'reference_id'),
            'client'       => $this->belongsTo(Client::class, 'reference_id'),
            default        => null,
        };
    }

    // 👤 Creator / Updater Relations
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // 🔗 Discount Type Relation
    public function type()
    {
        return $this->belongsTo(DiscountType::class, 'discount_type_id');
    }
}
