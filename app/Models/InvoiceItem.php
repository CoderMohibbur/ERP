<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use SoftDeletes;

    // ✅ Fillable fields for mass assignment
    protected $fillable = [
        'invoice_id',
        'item_code',
        'item_name',
        'description',
        'quantity',
        'unit_price',
        'tax_percent',
        'total',
    ];

    // ✅ Define relationship to Invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'total' => 'decimal:2',
    ];
}
