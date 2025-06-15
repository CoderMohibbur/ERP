<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'invoice_type',
        'status',
        'client_id',
        'project_id',
        'terms_id',
        'issued_by',
        'approved_by',
        'currency',
        'currency_rate',
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
        'metadata',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'issue_date'       => 'date',
        'due_date'         => 'date',
        'sub_total'        => 'decimal:4',
        'discount_value'   => 'decimal:4',
        'tax_rate'         => 'decimal:2',
        'total_amount'     => 'decimal:4',
        'paid_amount'      => 'decimal:4',
        'due_amount'       => 'decimal:4',
        'currency_rate'    => 'decimal:4',
        'metadata'         => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔗 Relationships
    |--------------------------------------------------------------------------
    */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function terms()
    {
        return $this->belongsTo(TermAndCondition::class, 'terms_id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔍 Scopes
    |--------------------------------------------------------------------------
    */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeDue($query)
    {
        return $query->where('due_amount', '>', 0);
    }

    public function scopeFinal($query)
    {
        return $query->where('invoice_type', 'final');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /*
    |--------------------------------------------------------------------------
    | 🧠 Accessors
    |--------------------------------------------------------------------------
    */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date < now() && $this->due_amount > 0;
    }
}
