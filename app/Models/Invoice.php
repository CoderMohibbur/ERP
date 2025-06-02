<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'client_id',
        'project_id',
        'status',
        'currency',
        'issue_date',
        'due_date',
        'sub_total',
        'discount_type',
        'discount_value',
        'tax_rate',
        'total_amount',
        'paid_amount',
        'due_amount',
        'notes',
        'created_by',
    ];

    // 🔁 Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }


    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    protected $casts = [
        'issue_date' => 'datetime',
        'due_date' => 'datetime',
    ];
}
