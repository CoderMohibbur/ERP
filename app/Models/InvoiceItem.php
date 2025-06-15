<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'item_code',
        'item_name',
        'description',
        'quantity',
        'unit',
        'item_category_id',
        'unit_price',
        'tax_percent',
        'total',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'quantity'       => 'integer',
        'unit_price'     => 'decimal:2',
        'tax_percent'    => 'decimal:2',
        'total'          => 'decimal:2',
        'created_by'     => 'integer',
        'updated_by'     => 'integer',
    ];

    // 🔗 Relationships

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // 📐 Accessors & Mutators (if needed)

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2);
    }

    // 🧠 Scopes (example)
    public function scopeForInvoice($query, $invoiceId)
    {
        return $query->where('invoice_id', $invoiceId);
    }
}
