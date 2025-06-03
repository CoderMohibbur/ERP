<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItemField extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_item_id',
        'field_name',
        'field_value',
        'data_type',
        'is_required',
        'group',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'sort_order' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    // 🔗 Relation with InvoiceItem
    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    // 🧑 Created By
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // 🧑 Updated By
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
